<?php declare(strict_types = 1);

namespace App\System\Application\Validation;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use App\System\Application\Exceptions\ValidationRuleAlreadyExistsException;
use App\System\Application\Exceptions\ValidationException;

abstract class Validator
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var array
     */
    protected $rules;

    /**
     * @param array $params
     * @return self
     */
    public function validate(array $params): self
    {
        $this->clearErrors();

        foreach ($this->getRules() as $field => $rule) {
            try {
                $param = $params[$field] ?? null;

                $rule->setName(ucfirst($field))->assert($param);
            } catch (NestedValidationException $e) {
                $this->addError($field, $e->getMessages());
            }
        }

        if ($this->failed()) {
            throw ValidationException::withMessages($this->getErrors());
        }

        return $this;
    }

    /**
     * @return boolean
     */
    public function passed(): bool
    {
        return empty($this->errors);
    }

    /**
     * @return boolean
     */
    public function failed(): bool
    {
        return !$this->passed();
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param string $field
     * @param \Respect\Validation\Validator $rule
     * @return void
     */
    protected function extendRule(string $field, v $rule): void
    {
        if (isset($this->rules[$field])) {
            throw new ValidationRuleAlreadyExistsException($field);
        }

        $this->rules[$field] = $rule;
    }

    /**
     * @param array $rules
     * @return void
     */
    protected function extendRules(array $rules): void
    {
        foreach ($rules as $field => $rule) {
            $this->extendRule($field, $rule);
        }
    }

    /**
     * @return void
     */
    private function clearErrors(): void
    {
        $this->errors = [];
    }

    /**
     * @param string $field
     * @param array $messages
     * @return void
     */
    private function addError(string $field, array $messages): void
    {
        $this->errors[$field] = $messages;
    }
}

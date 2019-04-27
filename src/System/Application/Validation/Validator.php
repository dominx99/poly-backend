<?php declare (strict_types = 1);

namespace Wallet\System\Application\Validation;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use Wallet\System\Application\Exceptions\ValidationRuleAlreadyExistsException;

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
        foreach ($this->getRules() as $field => $rule) {
            try {
                $rule->setName(ucfirst($field))->assert($params[$field]);
            } catch (NestedValidationException $e) {
                $this->errors[$field] = $e->getMessages();
            }
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
     * @return null|array
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
}

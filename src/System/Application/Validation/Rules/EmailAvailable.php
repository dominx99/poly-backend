<?php declare(strict_types = 1);

namespace App\System\Application\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\User\Contracts\UserQueryRepository;

class EmailAvailable extends AbstractRule
{
    /**
     * @var \App\User\Contracts\UserQueryRepository
     */
    private $users;

    /**
     * @param \App\User\Contracts\UserQueryRepository $users
     * @return void
     */
    public function __construct(UserQueryRepository $users)
    {
        $this->users = $users;
    }

    /**
     * @param mixed $input
     * @return bool
     */
    public function validate($input): bool
    {
        return !$this->users->emailExist($input);
    }
}

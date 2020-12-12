<?php

namespace App\Repositories;

use App\Models\Model;
use App\Models\User;
use Exception;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository extends Repository
{
    /**
     * @var User
     */
    protected $model = User::class;

    /**
     * Get user by email.
     *
     * @param string $email
     * @return Model
     * @throws Exception
     */
    public function getUserByEmail($email)
    {
        return $this->first(['email' => $email]);
    }

    /**
     * Update user status
     *
     * @param int $id
     * @param bool $isActive
     * @return Model|User
     * @throws Exception
     */
    public function updateStatus(int $id, $isActive)
    {
        return $this->update($id, ['disabled_at' => $isActive ? null : now()]);
    }
}

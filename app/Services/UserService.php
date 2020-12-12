<?php

namespace App\Services;

use App\Models\Model;
use Exception;
use App\Repositories\UserRepository;

/**
 * Class UserService
 * @package App\Services
 */
class UserService extends Service
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get user by id
     *
     * @param int $userId
     * @return Model|mixed
     * @throws Exception
     */
    public function getUser(int $userId)
    {
        return $this->userRepository->find($userId);
    }
}

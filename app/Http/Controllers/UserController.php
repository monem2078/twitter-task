<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\User;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function follow(User $user)
    {
        $this->userRepository->follow($user);

        return $this->jsonReturn(200, trans('validation.user_follow'), null);

    }

    public function timeLine()
    {
        $tweets = $this->userRepository->timeLine();

        return $this->jsonReturn(200, $tweets, null);
    }
}

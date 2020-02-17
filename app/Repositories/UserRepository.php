<?php


namespace App\Repositories;

use App\Helpers\SecurityHelper;
use App\User;
use Prettus\Repository\Eloquent\BaseRepository;


class UserRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    public function login($email,$password)
    {
        $user = $this->getUserByEmail($email);
        if ($user) {
            if ($user->password == SecurityHelper::getHashedPassword($password)) {
                return $user;
            }
        } else {
            return null;
        }
    }

    public function getUserByEmail($email)
    {
        $user = $this->model->selectRaw("*")
            ->where('email', $email)->first();
        return $user;
    }

    public function follow($user)
    {
        $user = auth()->user()->followers()->sync(['following_id' => $user->id]);

        return $user;
    }

    public function timeLine()
    {
        $model = $this->model->select('tweets.body', 'tweets.id', 'tweets.user_id', 'followings.image')
            ->Join('user_follows', 'user_follows.follower_id', 'users.id')
            ->Join('tweets', 'tweets.user_id', 'user_follows.following_id')
            ->Join('users as followings', 'followings.id', 'user_follows.following_id')
            ->where('users.id', auth()->user()->id);

        if (app()->getLocale() == 'ar') {
            $model->select('tweets.body_ar');
        } else {
            $model->select('tweets.body_en');
        }


        return $model->paginate(7);
    }

}

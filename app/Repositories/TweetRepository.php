<?php

namespace App\Repositories;

use App\Tweet;
use Prettus\Repository\Eloquent\BaseRepository;

class TweetRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Tweet::class;
    }

    public function create(array $attributes)
    {
        $tweet = auth()->user()->tweets()->create($attributes);

        return $tweet;
    }
}

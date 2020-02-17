<?php

namespace App\Http\Controllers;

use App\Repositories\TweetRepository;
use App\Tweet;
use Illuminate\Http\Request;
use Validator;

class TweetController extends Controller
{
    /**
     * @var TweetRepository
     */
    private $tweetRepository;

    public function __construct(TweetRepository $tweetRepository)
    {

        $this->tweetRepository = $tweetRepository;
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, Tweet::rules(), Tweet::messages());

        if ($validator->fails()) {
            return $this->jsonReturn(400, null, $validator->errors()->all());
        }

        return $this->jsonReturn(200, $this->tweetRepository->create($data), null);

    }

    public function destroy(Tweet $tweet)
    {
        if (auth()->user()->is($tweet->user)) {
            $this->tweetRepository->delete($tweet->id);

            return $this->jsonReturn(200, trans('validation.tweet_deleted'), null);
        }

        return $this->jsonReturn(401, null, null);
    }


}

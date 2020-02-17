<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\SecurityHelper;
use App\Helpers\UploadHelper;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class AuthenticateController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('throttle:5,1')->only('authenticate');

        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, User::rules('register'), User::messages());

        if ($validator->fails()) {
            return $this->jsonReturn(400, null, $validator->errors()->all());
        }

        if (isset($data['password'])) {
            $data['password'] = SecurityHelper::getHashedPassword($data['password']);
        }

        $userExist = $this->userRepository->findByField('email', $data['email']);
        if ($userExist->count() > 0) {
            return $this->jsonReturn(200, trans('validation.email_exists'), null);
        }
        if (isset($data['image'])) {
            $image_url = UploadHelper::uploadFile($request, 'image');
            $data['image'] = $image_url;
        }

        $this->userRepository->create($data);

        return $this->jsonReturn(200, ['created' => true], null);
    }

    /**
     * Login.
     *
     * @param Request $request
     *
     * @return Token
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->all();
        try {
            // verify the credentials and create a token for the user
            $user = $this->userRepository->login($credentials['email'], $credentials['password']);

            if (!$user) {
                return $this->jsonReturn(401, trans('validation.invalid_credentials'), null);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $customClaims = ['user_id' => $user->id];

        $token = JWTAuth::fromUser($user, $customClaims);

        return $this->jsonReturn(200, compact('token'), null);
    }
}

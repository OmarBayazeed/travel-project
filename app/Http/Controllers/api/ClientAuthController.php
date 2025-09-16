<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class ClientAuthController extends Controller
{
    public function __construct() {
        $this->middleware('jwt.verify', ['except' => ['login', 'register','verify_email','google','facebook','send_email','reset_password_code','reset_password']]);
        Config::set('auth.defaults.guard','api');
        Config::set('auth.defaults.passwords','clients');
    }


    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()], 400);
        }
        $client = Client::where('email', $request->email)->first();
        if ($client) {
            $hashed = $client->password;
            $normalPass = $request->password;
            if(Hash::check($normalPass,$hashed)) {
                $myTTL = 300000;
                JWTAuth::factory()->setTTL($myTTL);
                return response()->json([
                    'token' => JWTAuth::claims(['email' => $request->email])->fromUser($client),
                    "user" => $client,
                    'status' => true,
                ],200);
            } else {
                return response()->json([
                    'message' => 'wrong password',
                    'status' => false,
                ],400);
            }
        }
        else {
            return response()->json([
                'message' => 'you should register first',
                'status' => false,
            ],404);
        }
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|email|unique:clients',
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->letters()->numbers()->mixedCase()->symbols(),
            ],
        ]);
        if($validator->fails()){
            return response()->json(["message" => $validator->errors()], 400);
        }
        $user = Client::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        if ($request->has('email')) {
            $token = Str::random(6);
            $data['token'] = $token;
            $data['email'] = $request->email;
            $data['title'] = 'Email Verification';
            $data['body'] = 'use this code to verify your account.';

            Mail::send('forgetPasswordMail', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });
            $emailVerification= PasswordReset::where('email',$request->email);
            if ($emailVerification) {
                $emailVerification->delete();
            }
            $password_reset = new PasswordReset();
            $password_reset->email = $request->email;
            $password_reset->token = $token;
            $password_reset->save();
        }
        return response()->json([
            'message' => 'client successfully registered check your mail for verification',
            'user' => $user,
            'status' => true,
        ], 201);
    }

    public function verify_email(Request $request) {
        $validator = Validator::make($request->all(), [
            'code' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['message' => $validator->errors(), 'status' => false], 400);
        }

        $reset = PasswordReset::where('token',$request->code)->first();
        if ($reset) {
            $client = client::where('email',$reset->email)->first();
            $client->verified = 1;
            $client->save();
            $reset->delete();
            return response()->json(['message' => 'email verified successfully', 'status' => true], 200);
        }else {
            return response()->json(['message' => 'your code is wrong', 'status' => false], 400);
        }
    }

    public function google(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string|max:255',
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|unique:clients',
        ]);
        if($validator->fails()){
            return response()->json(["message" => $validator->errors(), 'status' => false], 400);
        }
        $client = client::where('social_id',$request->user_id)->where('social_type','google')->first();
        if ($client) {
            $myTTL = 300000;
            JWTAuth::factory()->setTTL($myTTL);
            return response()->json([
                'token' => JWTAuth::claims(['email' => $request->email])->fromUser($client),
                'user' => $client,
                'status' => true,
            ]);
        }else {
            $client = client::where('email',$request->email)->first();
            if ($client) {
                return response()->json([
                    'message' => 'you already have an account, you should login by your email and password',
                    'status' => false,
                ], 400);
            }
            else {
                $client = client::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'social_id' => $request->user_id,
                    'social_type' => 'google',
                ]);
                return response()->json([
                    'message' => 'you registered by google successfully',
                    'user' => $client,
                    'status' => true,
                ], 201);
            }
        }
    }

    public function facebook(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string|max:255',
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|unique:clients',
        ]);
        if($validator->fails()){
            return response()->json(["message" => $validator->errors(), 'status' => false], 400);
        }
        $client = client::where('social_id',$request->user_id)->where('social_type','facebook')->first();
        if ($client) {
            $myTTL = 300000;
            JWTAuth::factory()->setTTL($myTTL);
            return response()->json([
                'token' => JWTAuth::claims(['email' => $request->email])->fromUser($client),
                'user' => $client,
                'status' => true,
            ],200);
        }else {
            $client = client::where('email',$request->email)->first();
            if ($client) {
                return response()->json([
                    'message' => 'you already have an account, you should login by your phone',
                    'status' => false,
                ], 400);
            }
            else {
                $client = client::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'social_id' => $request->user_id,
                    'social_type' => 'facebook',
                ]);
                return response()->json([
                    'message' => 'you registered by facebook successfully',
                    'user' => $client,
                    'status' => true,
                ], 201);
            }
        }
    }


    public function send_email(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:100',
        ]);
        if($validator->fails()){
            return response()->json(['message' => $validator->errors(), 'status' => false], 400);
        }

        $client = client::where('email',$request->email)->first();
        if ($client) {
            $token = Str::random(6);
            $data['token'] = $token;
            $data['email'] = $request->email;
            $data['title'] = 'Password Reset';
            $data['body'] = 'use this code to reset your password.';

            Mail::send('forgetPasswordMail', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });
            $emailVerification= PasswordReset::where('email',$request->email);
            if ($emailVerification) {
                $emailVerification->delete();
            }
            $password_reset = new PasswordReset();
            $password_reset->email = $request->email;
            $password_reset->token = $token;
            $password_reset->save();
            if ($password_reset) {
                return response()->json(['message' => 'check your mail please','user_id' => $client->id, 'status' => true], 200);
            }else {
                return response()->json(['message' => 'something went wrong', 'status' => false], 400);
            }

        }else {
            return response()->json(['message' => 'this email not found in our system', 'status' => false], 404);
        }
    }

    public function reset_password_code(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['message' => $validator->errors(), 'status' => false], 400);
        }

        $reset = PasswordReset::where('token',$request->code)->first();
        if ($reset) {
            $reset->delete();
            return response()->json(['message' => 'your code is correct', 'status' => true], 200);
        }else {
            return response()->json(['message' => 'your code is wrong', 'status' => false], 400);
        }
    }

    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->letters()->numbers()->mixedCase()->symbols(),
            ],
            'user_id' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['message' => $validator->errors(), 'status' => false], 400);
        }

        $client = client::where('id',$request->user_id)->first();
        if ($client) {
            $client->password = bcrypt($request->password);
            $client->save();
            return response()->json(['message' => 'password reset done successfully', 'status' => true], 200);
        }else {
            return response()->json(['message' => 'client not found or something went wrong', 'status' => false], 400);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'client successfully signed out','status' => true],200);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth('api')->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json([auth('api')->user(),'status' => true]);
    }
}

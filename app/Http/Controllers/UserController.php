<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignupEmail;
use Illuminate\Support\Str;


class UserController extends Controller
{

    public function signUp(Request $request){

        $name = $request->name;
        $email = $request->email;

        // require and unique validation
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users',
        ],[
            'name.required'=>'Provide Name',
            'email.required'=>'Provide email',
            'email.unique'=>'Email already taken',
            'email.email'=>'Provide valid email',
        ]);
        if($validator->fails()){
            $error = $validator->errors()->first();
            $response = [
                'error' => $error,
            ];
            return response($response, 422);
        }
        // adding user to database
        $user = User::create([
            'name' =>  $name,
            'email' => $email,

        ]);
        $randomString = Str::random(6);
        //check user is added or not
        if($user->id){
           Mail::to($user->email)->send(new SignupEmail($randomString));
           // add user password
            $newdata = [
                'password'   =>  Hash::make($randomString),
            ];
            User::where('id', $user->id)->update($newdata);

            $response = [
                'message' => 'User registered successfully. Check your email for password',
                'detail'=>$user,
            ];
            return response($response, 201);
        }
        $response = [
            'message' => 'Something went wrong',
            'detail'=>null,
        ];
        return response($response, 400);

    }

    public function login(Request $request){

        $email = $request->email;
        $password = $request->password;

        // require and unique validation
        $validator=Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required',
        ],[
            'email.required'=>'Provide email',
            'email.email'=>'Provide valid email',
            'password.required'=>'Provide Password',
        ]);
        if($validator->fails()){
            $error = $validator->errors()->first();
            $response = [
                'error' => $error,
            ];
            return response($response, 422);
        }
        // Attempt to log the user in
        $credentials = $request->only(['email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            return response(['message' => 'Unauthorized'], 401);
        }

        // Return the token and user details
        return $this->respondWithToken($token);
    }
    protected function respondWithToken($token)
    {
        $carsCount = Car::count();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth('api')->user(),
            'vehicle_count' => $carsCount
        ]);
    }
    public function logout(){
        auth('api')->logout();
        $response =[
            'message' => 'Successfully logged out'
        ];
        return response($response, 200);


    }
}

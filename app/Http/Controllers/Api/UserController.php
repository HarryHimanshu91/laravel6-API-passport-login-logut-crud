<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Validator;
use App\User;
use Hash;
use Illuminate\Support\Facades\Auth; 

class UserController extends Controller
{

    /*
     * @CreatedOn : 19-7-2021
     * @CreatedBy : Himanshu Chand
     * @Description : User Sign Up  Api
     */


    public function signup(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2|max:45',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8|max:45',
            ]);

            if ($validator->fails()) {
                //  dd($validator->errors());  show all the errors in array

                $error = $validator->errors()->all()[0];  // show particular field error message
                return response()->json(['status' => 'false', 'message' => $error, 'data' => []], 422);
            } else {
               $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)

                ]);

                return response()->json(['status' => 'true', 'message' => 'User created successfully','data'=>$user ], 201);
            }
        } catch (\Exception $e) {
            return  response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }


    /*
     * @CreatedOn : 19-7-2021
     * @CreatedBy : Himanshu Chand
     * @Description : User Login Api
     */


    public function login(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:8|max:45',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];  // show particular field error message
                return response()->json(['status' => 'false', 'message' => $error, 'data' => []], 422);
            } else {
                $user = User::where('email', $request->email)->first();
                if ($user) {
                    if (Hash::check($request->password, $user->password)) {
                        $token =  $user->createToken('MyApp')->accessToken;
                        $user->token = $token;
                        return response()->json(['status' => 'true', 'message' => 'Logged In !!', 'data' => $user]);
                    } else {
                        return  response()->json(['status' => 'false', 'message' => 'Invalid Password', 'data' => []]);
                    }
                } else {
                    return  response()->json(['status' => 'false', 'message' => 'Email does not exists', 'data' => []]);
                }
            }
        } catch (\Exception $e) {
            return  response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }


    /*
     * @CreatedOn : 19-7-2021
     * @CreatedBy : Himanshu Chand
     * @Description : Get user details Api
     */

    public function getProfile(Request $request)
    {
        try {
            $user_id = $request->user()->id;
            $user = User::find($user_id);
            return  response()->json(['status' => 'true', 'message' => 'User profile', 'data' => $user]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }


    /*
     * @CreatedOn : 19-7-2021
     * @CreatedBy : Himanshu Chand
     * @Description : Update user details Api
     */

    public function updateProfile(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2|max:45',
                'email' => 'required|email|unique:users,id,' . $request->user()->id,
                'profile_picture' => 'nullable|image'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(['status' => 'false', 'message' => $error, 'data' => []], 422);
            } else {
                
                $user = User::find($request->user()->id);
                $user->name = $request->name;
                $user->email = $request->email;

                if($request->profile_picture && $request->profile_picture->isValid()){
                    $file_name = time().'.'.$request->profile_picture->extension();
                    $request->profile_picture->move(public_path('images'),$file_name);
                    $path = "public/images/$file_name";
                    $user->profile_picture = $path;
                }
                $user->update();
                return response()->json(['status'=>'true','message'=>'Profile Updated successfully','data'=>$user ]);


            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }

    public function logoutUser(Request $request)
    {
        try{
            $request->user()->token()->revoke();
            return response()->json(['status'=>'true','message'=>'Logout successfully','data'=>[] ]);
        }catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }
}

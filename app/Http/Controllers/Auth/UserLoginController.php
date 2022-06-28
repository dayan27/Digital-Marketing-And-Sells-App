<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserLoginController extends Controller
{

    public function login(Request $request){

        $request->validate([

            'phone_number'=>'required',
            'password'=>'required',

        ]);

       // $user_acc=Account::where('user_name',$request->email)->first();
        $user=User::where('phone_number',$request->phone_number)->first();
        if (! $user ) {
            return response()->json([
                'message'=>' incorrect email and password',
                ]
               ,404 );
        }

        $check=Hash::check($request->password, $user->password);
        if (! $check ) {
            return response()->json([
                'message'=>' incorrect  and password',
                ]
               ,404 );
        }

        $token=$user->createToken('auth_token')->plainTextToken;
      //  $user->profile_picture=asset('/profilepictures').'/'.$user->profile_picture;
       // return response()->json($Manager,200);
        return response()->json([
            'access_token'=>$token,
            'user'=>$user,
        ],200);

     }

    public function logout(Request $request){
        //  return  $request->user();
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message'=>$request->user(),
            ],200);

        }

        public function checkOtp(Request $request){
            $user=User::where('phone_number',$request->phone_number)->where('verification_code',$request->code)->first();
            if($user){
            // return $user;
               $user->verification_code=null;
               $user->verified=1;
               $user->save();
               //$request->session()->put('verified', true);
               $token=$user->createToken('auth_token')->plainTextToken;
               //  $user->profile_picture=asset('/profilepictures').'/'.$user->profile_picture;
                // return response()->json($Manager,200);
                 return response()->json([
                     'access_token'=>$token,
                     'user'=>$user,
                 ],200);
                     }else{
                return response()->json('Error inValid Otp',401);

            }

        }

        public function changePassword(Request $request){

           // return $request;
            $request->validate([
                'old_password'=>'required',
                'new_password'=>'required',

            ]);

            $user=Manager::where('email',$request->user()->email)->first();
            if (! $user ) {
                return response()->json([
                    'message'=>' incorrect credentials ',
                    ]
                   ,404 );
            }

            $check=Hash::check($request->old_password, $user->password);
            if (! $check ) {
                return response()->json([
                    'message'=>' incorrect old password ',
                    ]
                   ,404 );
            }

            $user->password=Hash::make($request->new_password);
            $user->save();
            return response()->json([
                'message'=>'Successfully  Reset',
                ]
               ,200 );
        }


        public function forgotPassword(){
            
        }
  }

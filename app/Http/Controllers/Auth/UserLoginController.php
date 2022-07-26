<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Manager;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\SendToken;
class UserLoginController extends Controller
{
    use SendToken;

    public function login(Request $request){

        $request->validate([

            'phone_number'=>'required',
            'password'=>'required',

        ]);

       // $user_acc=Account::where('user_name',$request->email)->first();
        $user=User::where('phone_number',$request->phone_number)->first();
        if (! $user ) {
            return response()->json([
                'message'=>' User does not exist ',
                ]
               ,404 );
        }

        if($user->verified == 0){

            $otp=rand(100000,999999);

            $user->verification_code=$otp;
            $user->save();
            $this->sendResetToken($otp,$request->phone_number);
            return response()->json(
                 'unverified user',201 );
        }

        $check=Hash::check($request->password, $user->password);
        if (! $check ) {
            return response()->json(
                 'incorrect email or password'
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

        public function verifyPhone(Request $request){
            $user=User::where('phone_number',$request->phone_number)->where('verification_code',$request->code)->first();
            if(!$user){
                return response()->json('Error inValid Otp',201);

            }
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
                    
            

        }

        public function checkResetOtp(Request $request,$token){
            $tokenData = DB::table('user_password_resets')->where('token', $token)->first();

            if (!$tokenData )
            //&& ($tokenData->phone_number != $request->phone_number)
            return response()->json('Invalide token',201);


            //     $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $tokenData->created_at);
            //      $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now());
    
    
            //  // $diff_in_minutes = $to->diffInMinutes($from);
            // Redirect the user back to the password reset request form if the token is invalid
            $time_to_expire= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',\Carbon\Carbon::now() )->diffInMinutes($tokenData->created_at);
            
            if($time_to_expire > 5){
                return response()->json('The expired token',201);

            }  
          
                 return response()->json([
                     'code'=>$token,
                    
                 ],200);
             
     }  
         
 
        

        public function changePassword(Request $request){

           // return $request;
            $request->validate([
                'old_password'=>'required',
                'new_password'=>'required',

            ]);

            $user=User::where('phone_number',$request->user()->phone_number)->first();
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


       public function resend(Request $request){
        $otp=rand(1000,9999);
        $user=User::where('phone_number',$request->phone_number)->first();
        $user->verification_code=$otp;
        $user->save();
        $this->sendResetToken($otp,$request->phone_number);
       }
    }
  

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AgentLoginController extends Controller
{

    public function login(Request $request){

        $request->validate([

            'email'=>'required',
            'password'=>'required',

        ]);

        $user_acc=Account::where('user_name',$request->email)->first();
        $user=Manager::where('email',$request->email)
        ->where('type','agent')
        ->where('is_active',1)
        ->first();
        if (! $user || !$user_acc ) {
            return response()->json([
                'message'=>' incorrect email and password',
                ]
               ,404 );
        }

        $check=Hash::check($request->password, $user_acc->password);
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
            'user'=>$user->load('phone_numbers'),
            'shop_id'=>$user->shop->id?? null,
        ],200);

     }

    public function logout(Request $request){
        //  return  $request->user();
            $request->user()->currentAccessToken()->delete();
            return response()->json([$request->user()],200);

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
  }

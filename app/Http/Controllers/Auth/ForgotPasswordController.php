<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Manager;
use App\Notifications\NewPasswordNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class ForgotPasswordController extends Controller
{
    public function forgot(Request $request){
        //You can add validation login here
            $user = DB::table('managers')->where('email', '=', $request->email)
            ->first();
            //Check if the user exists
            if (! $user) {
                return response()->json('user doesn\'t exist');
            }

            //Create Password Reset Token
            DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => Str::random(10),
            'created_at' => Carbon::now()
            ]);
            //Get the token just created above
            $tokenData = DB::table('password_resets')
            ->where('email', $request->email)->first();

            if( $this->sendResetEmail($request->email, $tokenData->token)){
               return response()->json(['link sent'],200);
            } 
            else {
                    return response()->json(['link not sent',404]);
                 }
    }

    private function sendResetEmail($email, $token)
        {
        //Retrieve the user from the database
        $user = Manager::where('email', $email)->first();

        //Generate, the password reset link. The token generated is embedded in the link

        if ($user->type == 'system_user') {
        $link = env('FRONTEND_MANAGER_URL').'/reset-password' .'/'. $token . '?email=' . urlencode($user->email);

        }else if ($user->type =='agent') {
            $link = env('FRONTEND_AGENT_URL').'/reset-password' .'/'. $token . '?email=' . urlencode($user->email);
        }

           try {
            //Here send the link with CURL with an external email API
            $user->notify(new NewPasswordNotification($link));
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController1 extends Controller
{

    public function broker()
    {
        return Password::broker('admins');
    }

    public function forgot(Request $request)
    {
        $credentials = $request->validate(['email' => 'required|email']);
        $admin=Admin::where('email',$request->email)->first();
        if (! $admin) {
            return response()->json([
                "msg" => "Email Not found"
            ],404);

        }

        $status = Password::sendResetLink(
            $request->only('email')
        );
        return response()->json(__($status));
    }

    private function sendResetEmail($email, $token)
    {
    //Retrieve the user from the database
    $user = DB::table('admins')->where('email', $email)->select('firstname', 'email')->first();
    //Generate, the password reset link. The token generated is embedded in the link
    $link = config('base_url') . 'password/reset/' . $token . '?email=' . urlencode($user->email);

        try {
        //Here send the link with CURL with an external email API
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function reset(Request $request){
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $admin = Admin::where('email',$request->email)->first();

        $admin->password = Hash::make($request->password);
        $admin->save();
        return response()->json([
            'message' => ' Reset Password Successfully',
        ], 200);
    }
}

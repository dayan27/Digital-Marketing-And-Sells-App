<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionEmail;
use Illuminate\Http\Request;

class SubscriptionEmailController extends Controller
{
    public function subscribe_email(){
        $email=new SubscriptionEmail();
        $email->email=request()->email;
        $email->save();
        return response()->json('subscribed',200);

    }

}

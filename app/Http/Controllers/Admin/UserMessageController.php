<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\BroadcastUserMessage;
use Illuminate\Http\Request;

class UserMessageController extends Controller
{
 use BroadcastUserMessage;
    public function sendUserMessage(Request $request){
        $users=User::where('verified',1)->get();
        $users = $users->each(function ($user, $key) use($request) {
          
          $this->sendMessage($request->message,$user->phone_number);
      });
      return response()->json('message sent ',200);
    }
}

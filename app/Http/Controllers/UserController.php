<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\OTPNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Twilio\Rest\Client;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page=request()->per_page;
        $query= User::query();
      
          $query->when(request('filter'),function($query){

            if (request('filter') == 'active') {
               $query= $query->where('active', '=', 1);
 
            }elseif (request('filter') == 'inactive') {
                $query= $query->where('active', '=', 0);
            }
            elseif (request('filter') == 'blocked') {
                $query= $query->where('is_blocked', '=', 1);
            }
         });
        
        return UserResource::collection($query->paginate($per_page));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=$request->all();
        $otp=rand(1000,9999);
        $data['verification_code']=$otp;
        $data['password']=Hash::make($request->password);
        
       $user= User::create($data);
       $this->sendSms($otp,$user->phone_number);

      //$this->sendSmsNotificaition();

   //Notification::send($user,new OTPNotification($otp));
       return response()->json($user,201) ;
    }

    public function sendSmsNotificaition()
    {
        $basic  = new \Vonage\Client\Credentials\Basic("05640d37", "PSYx94SuG4eF0aSN");
        $client = new \Vonage\Client($basic);

        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS("251986038473", 'BRAND_NAME', 'A text mes API')
        );

        $message = $response->current();

        if ($message->getStatus() == 0) {
            echo "The message was sent successfully\n";
        } else {
            echo "The message failed with status: " . $message->getStatus() . "\n";
        }
    }


    public function sendSms($code,$phone){
        // Send an SMS using Twilio's REST API and PHP
    $sid = "AC0428dd1cb20e48b25054618dd910df17"; // Your Account SID from www.twilio.com/console
    $token = "49feea1cc785bbecefc404be7a9545e2"; // Your Auth Token from www.twilio.com/console

      $client = new Client($sid, $token);
      $message = $client->messages->create(
       $phone, // Text this number
  [
    'from' => '+15733759362', // From a valid Twilio number
    'body' => 'your verfication number is '.$code,
  ]
);

   return 'successfully sent';
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return User::find($id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user=User::find($id);
       $orders= $user->orders;
       if($orders->isEmpty()){
        $user->delete();
        return response()->json('successfully delete',200);
       }
       else{
        return response()->json('fail to delete',400);

       }
           
    }

   
}

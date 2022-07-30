<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\OTPNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon as SupportCarbon;
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
      
        $query=$query->when(request('search'),function($query){

            $query->where('first_name','LIKE','%'.request('search').'%')
            ->orWhere('last_name','LIKE','%'.request('search').'%')
            ->orWhere('first_name','LIKE','%'.request('search'))
            ->orWhere('last_name','LIKE'.request('search').'%')
            ->orWhere('phone_number','LIKE','%'.request('search').'%');
                //  ->orWhere('products.model','LIKE','%'.request('search').'%');
            });
        return UserResource::collection($query->paginate($per_page));
    }

    public function getShopUser(){
        $per_page=request()->per_page;
        $query= User::query()->whereHas('shops', function (Builder $query) {
                   $query= $query->where('shops.id',request()->user()->shop->id);
        });    
        $query=$query->when(request('search'),function($query){

            $query->where('first_name','LIKE','%'.request('search').'%')
            ->orWhere('last_name','LIKE','%'.request('search').'%')
            ->orWhere('first_name','LIKE','%'.request('search'))
            ->orWhere('last_name','LIKE'.request('search').'%')
            ->orWhere('phone_number','LIKE','%'.request('search').'%');
                //  ->orWhere('products.model','LIKE','%'.request('search').'%');
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
        try {
            $this->sendSms($otp,$user->phone_number);
            return response()->json($user,201) ;

        } catch (\Throwable $th) {
            return response()->json('unable to verify ur phone',404) ;
        }

      //$this->sendSmsNotificaition();

   //Notification::send($user,new OTPNotification($otp));
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
          return $user= User::find($id);
        
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
        $user= User::find($id);
        $user->update($request->all());
        return $user;
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
    /**
     * changing the status of the user
     */

    public function changeUserStatus($user_id){
        $user=User::find($user_id);
        $user->active=request()->status;
        $user->save();
        return response()->json('sucessfuly changed',200);

    }

 public function registerUserAdminSide(Request $request){
    $data=$request->all();
    $otp=rand(1000,9999);
    //$data['verification_code']=$otp;
   $data['password']=Hash::make($request->last_name.'1234');
    
   $user= User::create($data);
 }
    
}

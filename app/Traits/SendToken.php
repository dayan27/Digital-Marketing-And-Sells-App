<?php
namespace App\Traits;

use Twilio\Rest\Client;

trait SendToken{

public function sendResetToken($code,$phone){
            // Send an SMS using Twilio's REST API and PHP
        $sid = "ACa97d1266e9ecff907c2745c7e54de647"; // Your Account SID from www.twilio.com/console
        $token = "66eeaa3c11451907cc7c1bec32397225"; // Your Auth Token from www.twilio.com/console

        try {

            $client = new Client($sid, $token);
            $message = $client->messages->create(
             $phone, // Text this number
                [
                'from' => '+19804145549', // From a valid Twilio number
                'body' => 'your verfication number is '.$code,
                ]
         );
  
         return 'sent';

        } catch (\Throwable $th) {
          return $th;
        }
      
        }
    }
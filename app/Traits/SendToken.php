<?php
namespace App\Traits;

use Twilio\Rest\Client;

trait SendToken{

public function sendResetToken($code,$phone){
            // Send an SMS using Twilio's REST API and PHP
        $sid = "AC0428dd1cb20e48b25054618dd910df17"; // Your Account SID from www.twilio.com/console
        $token = "49feea1cc785bbecefc404be7a9545e2"; // Your Auth Token from www.twilio.com/console

        try {

            $client = new Client($sid, $token);
            $message = $client->messages->create(
             $phone, // Text this number
                [
                'from' => '+15733759362', // From a valid Twilio number
                'body' => 'your verfication number is '.$code,
                ]
         );
  
         return true;

            return true;
        } catch (\Throwable $th) {
          return false;
        }
      
        }
    }
<?php
namespace App\Traits;
trait ApiMessage{



        /**
         * success response method.
         *
         * @return \Illuminate\Http\Response
         */
        public function sendResponse($result, $message)
        {
            $response = [
                'success' => true,
                'data'    => $result,
                'message' => $message,
            ];

            return response()->json($result, 200);
        }


        /**
         * return error response.
         *
         * @return \Illuminate\Http\Response
         */
        public function sendError($error, $errorMessages = [], $code = 404)
        {
            $response = [
                'success' => false,
                'message' => $error,
            ];

            if(!empty($errorMessages)){
                $response['data'] = $errorMessages;
            }

            return response()->json($response, $code);
        }
    }


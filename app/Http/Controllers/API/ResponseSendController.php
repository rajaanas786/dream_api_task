<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResponseSendController extends Controller
{
    //

    public function successResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }



    public function erroResponse($error, $error_message = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($error_message)){
            $response['data'] = $error_message;
        }


        return response()->json($response, $code);
    }

}

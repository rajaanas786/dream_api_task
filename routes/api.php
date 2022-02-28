<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\MailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Register/add new user/Customer here
Route::post('register', [ApiController::class, 'store']);
//for login purpose currently not requred
// Route::post('login', [ApiController::class, 'login']);



Route::middleware('auth:api')->group(function () {

    //see the registered users
    Route::get('show_user', [ApiController::class, 'show']);

    //send message, for new message authorized customer can send
    Route::post('cutomer_new_message', [ApiController::class, 'cutomer_new_message']);

    //support team can send a reply.
    Route::post('support_team_message', [ApiController::class, 'support_team_message']);

    //here customer can see the question and its status
    Route::get('customer_questions/{id}', [ApiController::class, 'customer_questions']);
    
    //support team want to see all the questions
    Route::get('show_questions', [ApiController::class, 'show_questions']);

    // questions can be searched by name or by status
    Route::post('search_questions', [ApiController::class, 'search_questions']);

    // support team can mark any question as a spam
    Route::post('mark_as_spam', [ApiController::class, 'mark_as_spam']);


});

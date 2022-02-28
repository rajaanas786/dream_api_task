<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\ResponseSendController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\chat_detail;
use App\Models\chat_message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mail;
use App\Mail\ConfirmMail;



class ApiController extends ResponseSendController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     //user can be registerd here the role one 1 should be assigned to support team and role 2 should be assigned to customer
    public function store(Request $request)
    {
// dd($request->all());

        try {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'role' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',

        ]);

        if($validator->fails()){
            return $this->erroResponse('Validation Error.', $validator->errors());
        }

      $user = new User();
      $user->name = $request->name;
      $user->role =  $request->role;
      $user->email = $request->email;
      $user->password = bcrypt($request->password);
      $user->save();
      $success['token'] =  $user->createToken('MyApp')->accessToken;
      $success['name'] =  $user->first_name.' '.$user->last_name;

      return $this->successResponse($success, 'User registered successfully.');

    } catch (\Illuminate\Database\QueryException $e) {

        $response['message'] = 'Error Occured, Try Again ';
        return $this->erroResponse('Validation Error.', $response);


    }







    }




    public function cutomer_new_message(Request $request)
    {
      $obj = new chat_detail();
      $obj->customer_user_id = $request->customer_user_id;
      $obj->status = $request->status;
      $obj->message =  $request->message;
      $obj->save();

      $success['message'] =  $obj->message;
      $success['status'] =  'Not Answered';
      return $this->successResponse($success,'Message Sent Successfully');
    }

    public function support_team_message(Request $request)
    {
      $obj = new chat_message();
      $obj->chat_id = $request->chat_id;
      $obj->customer_user_id = $request->customer_user_id;
      $obj->support_user_id = $request->support_user_id;
      $obj->message =  $request->message;
      $obj->save();


      $obj2 = chat_detail::find($request->chat_id);
      $obj2->status = $request->msg_status;
      $obj2->update();
//confirmation email can be sent from here however you need to configure your own email i have hidden my configuration details
    //   Mail::to('email@email.com')->send(new ConfirmMail);

      $success['message'] =  $obj->message;
      $success['status'] =  'In Progress';
      $success['mail_sent'] =  'Mail Sent';
      return $this->successResponse($success,'Message Replied Successfully');
    }




// The customer should see all his questions of all statuses ("Not Answered", "In Progress" and "Answered");
    public function customer_questions($id)
    {
            $customer_user_id=$id;
            $obj = chat_detail::where('customer_user_id',$customer_user_id)->get();
            $customer_quesations = array();

            foreach ($obj as $ab) {
                if($ab->status=='0'){
                    $message_status='Not Answerd';
                }elseif($ab->status=='1'){
                    $message_status='In Progess';
                }
                elseif($ab->status=='2'){
                    $message_status='Answerd';
                }else{
                    $message_status='Spam';
                }
            $gamer = array(
                'id' => $ab->id,
                'customer_user_id' => $ab->customer_user_id,
                'status' => $message_status,
                'sent_at' => $ab->created_at,
            );
            $customer_quesations [] = $gamer;
        }
        return response()->json(['data'=>$customer_quesations], 200, [], JSON_NUMERIC_CHECK);

    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


// here support team can see the list of all customers/users role 1 should be assinged to support team and role 2 should be assinged to customer
    public function show()
    {
        try{
        $success['data'] =  Auth::user();

            return $this->successResponse($success, 'user Detail');

        } catch (\Illuminate\Database\QueryException $e) {

            $response['message'] = 'Error Occured,Try Again ';
            return $this->erroResponse('Validation Error.', $response);


        }
    }

    //support team can see all the question here

    public function show_questions()
    {
        try{
        $success['data'] =  chat_detail::get();
            return $this->successResponse($success, 'question Detail');
        } catch (\Illuminate\Database\QueryException $e) {
            $response['message'] = 'Error Occured,Try Again ';
            return $this->erroResponse('Validation Error.', $response);

        }
    }

    // support team can send request to seach by name or by status,
    // both searches tried to accomdated under same method to so the API request should be followed as per direction

    public function search_questions(Request $request)
    {

        // dd($request->all());
        $name=$request->name;
        $status=$request->status;
        // if the search by name
            if($status==null && $name!=null){
                $customer_ids=User::where('name','like','%'.$name.'%')->where('role','2')->pluck('id')->toArray();
                $success['data'] =  chat_detail::whereIn('customer_user_id',$customer_ids)->get();
                return $this->successResponse($success, 'seach by name result 1');
        }
        //if search by status
        elseif($name ==null && $status !=null){

                $success['data'] =  chat_detail::where('status',$status)->get();
                return $this->successResponse($success, 'seach by status result 2');

            }
            // if searched by name and status both
            else if($name !=null && $status !=null){
                $customer_ids=User::where('name','like','%'.$name.'%')->where('role','2')->pluck('id')->toArray();
                $success['data'] =  chat_detail::where('status',$status)->whereIn('customer_user_id',$customer_ids)->get();
                return $this->successResponse($success, 'seach by status and name result 3');
            }





    }
    //support team can mark any message as spam, status should be changed accordingly
    public function mark_as_spam(Request $request){

// dd($request->all());
        $user = chat_detail::find($request->id);
        $user->status = $request->status;
        $user->update();

        $success['data'] =  $user;

        return $this->successResponse($success, 'Message status updatd successfully.');
    }




    //login not require now, authorized beared token will authorize user,, if login required can be used here

    // public function login(Request $request)
    // {

    //     try{
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required',
    //         'password' => 'required',
    //     ]);

    //     if($validator->fails()){
    //         return $this->erroResponse('Validation Error.', $validator->errors());
    //     }

    //     if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
    //         $user = Auth::user();
    //         $success['token'] =  $user->createToken('MyApp')->accessToken;
    //         $success['name'] =  $user->first_name.' '.$user->last_name;

    //         return $this->successResponse($success, 'User login successfully.');
    //     }
    //     else{
    //         return $this->erroResponse('Unauthorised.', ['error'=>'Unauthorised']);
    //     }

    // } catch (\Illuminate\Database\QueryException $e) {

    //     $response['message'] = 'Error Occured,Try Again ';
    //     return $this->erroResponse('Validation Error.', $response);


    // }

    // }


}

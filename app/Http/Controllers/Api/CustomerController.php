<?php

namespace App\Http\Controllers\Api;

use App\Customers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mediumart\Orange\SMS\SMS;
use Mediumart\Orange\SMS\Http\SMSClient;

class CustomerController extends Controller
{
    public $sms;
    public function __construct()
    {
       // $client = SMSClient::getInstance('A4oIre0GtgHjNKUYXkhQP7srwA0inQbX', 'yrkr1DGOfPptohxh');
       // $this->sms = new SMS($client);
    }


    public function dashboard(){
        return response()->json(['message' => 'wellcome']);
    }
    public function signUp(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'prenom' => 'required|max:55',
            'nom' => 'required|max:55',
            'email' => 'email|required|unique:customers',
            'telephone' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $request['password'] = bcrypt($request->password);
        $customers = Customers::create($request->all());
        $accessToken = $customers->createToken('authToken')->accessToken;

        return response([ 'user' => $customers, 'access_token' => $accessToken]);
    }

    public function signIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 401);
        }
        $email = $request->email;
        $password = $request->password;

        if(Customers::where('email', $email)->count()<= 0) return response(['message' =>'Email n\'existe pas' ],400);

        $customers = Customers::where('email', $email)->first();
        if(password_verify($password, $customers->password)){
            return response(['message' =>'Connect Avec Success', 'data' => [
                'customers' => $customers,
                'token' => $customers->createToken('Personal Access Token', ['customer'])->accessToken
            ] ], 200);
        }else{
            return response(['message' => 'Wrong Credemtials'], 400);
        }


    }

    public function messageSend(Request $request){

        $loginData = $request->validate([
            'access_token' => 'email|required',
            'receiver' => 'required',
            'message' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials']);
        }


        $response = $this->sms->to($request->receiver)
                ->from('+224625448445','Alpha 2020')
                ->message($request->message)
                 ->send();
}

public function smss(){
    $response = $this->sms->to('+224628494519')
        ->from('+224625448445','Alpha 2020')
        ->message('Hello, world!')
        ->send();
  //  $response = $this->sms->balance('GIN');
   // $response = $this->sms->ordersHistory('GIN');

    //$response = $this->sms->statistics('GIN', 'RO4K1HCUUJUFJPFS');
    //$response = $this->sms->checkDeliveryReceiptNotificationUrl($id);
    dd($response);

}
}

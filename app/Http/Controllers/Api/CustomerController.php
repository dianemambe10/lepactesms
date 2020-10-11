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
        $client = SMSClient::getInstance('<client_id>', '<client_secret>');
        $this->sms = new SMS($client);
    }

    public function signup(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'prenom' => 'required|max:55',
            'nom' => 'required|max:55',
            'email' => 'email|required|unique:customers',
            'telephone' => 'email|required|unique:customers',
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

    public function signin(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials']);
        }

        $accessToken = auth()->custmer()->createToken('authToken')->accessToken;

        return response(['custmer' => auth()->custmer(), 'access_token' => $accessToken]);

    }

    public function sendsms(Request $request){

        $response = $this->sms->to('+224628494519')
                ->from('+224625448445','Alpha 2020')
                ->message('Hello, world!')
            ->send();
}
}

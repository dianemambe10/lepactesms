<?php

namespace App\Http\Controllers;

use App\Customers;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //

    public function signup(Request $request)
    {
        $validatedData = $request->validate([
            'prenom' => 'required|max:55',
            'nom' => 'required|max:55',
            'email' => 'email|required|unique:customers',
            'telephone' => 'email|required|unique:customers',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $customer = Customers::create($validatedData);

        $accessToken = $customer->createToken('authToken')->accessToken;

        return response([ 'user' => $customer, 'access_token' => $accessToken]);
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
}

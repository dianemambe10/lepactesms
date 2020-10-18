<?php

namespace App\Http\Controllers;

use App\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    //

    public function index()

    {

        $customers = Customers::latest()->paginate(5);



        return view('customers.index',compact('customers'))

            ->with('i', (request()->input('page', 1) - 1) * 5);

    }
 public function allCustomers(Request $request){


     $results =   Customers::paginate(100);
     $response = [
         'pagination' => [
             'total' => $results->total(),
             'per_page' => $results->perPage(),
             'current_page' => $results->currentPage(),
             'last_page' => $results->lastPage(),
             'from' => $results->firstItem(),
             'to' => $results->lastItem()
         ],
         'data' => $results
     ];

     return $response;
 }

 public function create(Request $request){

         return view('customers.customers-new');
 }

 public function customerSave(Request $request){

         $rules =  [
         'nom' => 'required|max:255',
         'prenom' => 'required|max:255',
         'email' => 'required|email|max:255|unique:customers,id',
         'telephone' => 'required|max:255|unique:customers,id'
     ];
     $customMessages = [
             'nom.required' =>' le nom est obligatoire',
             'prenom.required' =>' le prenom est obligatoire',
             'email.required' =>' l\'email est obligatoire',
             'email.unique' => 'l\'email existe déjà',
             'telephone.required' =>' le numero de téléphone est obligatoire',
             'telephone.unique' =>' le numero exite déjà'

         ];

      $this->validate($request, $rules, $customMessages);

     $request['password'] = bcrypt('123456789');

     $customers = Customers::create($request->all());
     return response([ 'customers' => $customers, 'message' => 'Nouveau Client Api Ajouté avec success']);

 }

}

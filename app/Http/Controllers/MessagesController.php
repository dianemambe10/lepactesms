<?php

namespace App\Http\Controllers;

use App\Customers;
use Illuminate\Http\Request;

class MessagesController extends Controller
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
}

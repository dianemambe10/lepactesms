<?php

namespace App\Http\Controllers;

use App\Volontaires;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VolontairesController extends Controller
{
    public function index()

    {

        return view('volontaires.index');

    }
    public function allVolontaires(Request $request){

//dd($request->user_id);
        $results =  DB::table('pac_volontaire')
               ->Leftjoin('smssends','pac_volontaire.id','=','smssends.volontaire_id')

            //->where('sms.id', '=', $request->sms_id)
             ->where('sms_id', '=', null)
            ->select('pac_volontaire.id as id', 'nom','prenom', 'ville','phone','email','content')
       // ->get();
      //  return response()->json($results);
        ->paginate(50);
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

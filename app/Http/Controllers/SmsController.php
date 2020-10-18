<?php

namespace App\Http\Controllers;

use App\Customers;
use App\Participant;
use App\Sms;
use App\Smssend;
use App\User;
use App\Volontaires;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mediumart\Orange\SMS\Http\SMSClient;

class SmsController extends Controller
{
    //

    public $sms;
    public function __construct()
    {
      //  $client = SMSClient::getInstance('A4oIre0GtgHjNKUYXkhQP7srwA0inQbX', 'yrkr1DGOfPptohxh');
       // $this->sms = new \Mediumart\Orange\SMS\SMS($client);
    }


    public function index()

    {

        return view('sms.index');

    }

    public function create(Request $request){

        return view('sms.sms-new');
    }
    public function allSms(Request $request){
        $results =   Sms::paginate(100);
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

    public function sendSms(Request $request ){
        $id= $request->id;
        $user_id= Auth::user()->id;
        return view('sms.sendsms', compact('id','user_id'));

    }

    public function getSms(Request $request){

      $sms = Sms::where('id', $request->id)->first();
        //dd($sms);
      return response()->json($sms);
    }
    public function smsValide(Request $request){

      /* foreach($request->numero as $numer){
       $numer = '+224'. preg_replace('/\s+/','',str_replace(' ', '',str_replace('+224','',$numer))) ;

            $response = $this->sms->to($numer)
                ->from('+224625448445',$request->smsdata->title)
                ->message($request->smsdata->content)
                ->send();
        }
     */

        foreach (array_chunk($request->data, 50) as $info){
            Smssend::insert($info);
        }

        return response()->json(['success' => 'Les messages ont été envoyés avec success']);

    }

    public function smsSave(Request $request){

        $rules =  [
            'title' => 'required|max:255',
            'content' => 'required|max:255',
         ];
        $customMessages = [
            'title.required' =>' le titre est obligatoire',
            'content.required' =>' le contenu est obligatoire'
           ];

        $this->validate($request, $rules, $customMessages);

         $request['user_id'] =  Auth::user()->id;
        $sms = Sms::create($request->all());
        return response([ 'customers' => $sms, 'message' => 'Nouveau Sms Ajouté avec success']);

    }


}

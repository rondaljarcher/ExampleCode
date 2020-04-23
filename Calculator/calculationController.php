<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Config;
use Log;

class CalcController extends Controller
{

    public function postIndex(Request $request){
    $data = $request->all();
    $fZip = $request['data.zip'];
    $tZip = $request['data.delivery'];//$request->input('data.delivery');
    $make = $request['data.make'];//$request->input('data.make');
    $model = $request['data.model'];//$request->input('data.model');
    // $OGmodel = $request['data.OGmodel'];//$request->input('data.OGmodel');
    $year = $request['data.year'];//$request->input('data.year');
    $shipping = $request['data.shipping'];//$request->input('data.shipping');
    $enclosed = $request['data.enclosed'];//$request->input('data.enclosed');
    $running = $request['data.running'];//$request->input('data.running');

$curl_data = "{
   \"pickup\":
   {
     \"zip\":\"".$fZip."\" },
   \"delivery\":{
        \"zip\":\"".$tZip."\"    },
   \"vehicles\":[
    {
      \"year\": \"".$year."\",
      \"make\": \"".$make."\",
      \"model\": \"".$model."\",
      \"running\":".$running."     }
    ],

      \"estimated_shipping_date\":\"".$shipping."\",
      \"enclosed\":".$enclosed.",
      \"company_id\":1,
      \"user_company_id\":6    }";

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "NDA",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 90,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $curl_data,
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: c586ca5b-8708-b76d-6867-ea9472711775"
    ),
));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
    //ignore
    } else {
        $price =json_decode($response);
        return $price->pr_cpay;
    }

}
    public function getIndex()
    {
      // return 'hi';
    return view('calc');
    }
}
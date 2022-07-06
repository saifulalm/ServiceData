<?php

namespace App\Services\ServiceData;

use App\Events\ServiceData\RequestEvent;
use App\Events\ServiceData\ResponseEvent;
use Illuminate\Support\Facades\Http;

class GetRequest
{

    private function credential(): array
    {

        $apikey='94zx9y2kh3jh4t3t3rsutrp2';
        $secret='G6GbaaAdYI';
        $subskey='B1SB0KCY0JBIPM42DIQI';
        $timestamp=gmdate('U');
        $sign=md5($apikey.$secret.$timestamp);
        return array('apikey'=>$apikey,'secret'=>$secret,'subskey'=>$subskey,'sign'=>$sign);

    }

    public function balance()
    {
        $credential=$this->credential();
        $data = ['subscriptionKey' => $credential['subskey']];
        $header = ['api_key' => $credential['apikey'], 'x-signature' => $credential['sign']];
        event(new RequestEvent(json_encode($data)));
        $response = Http::withHeaders($header)->get('http://68.183.188.18:3010/api/v0/balance', $data)->json();
        event(new ResponseEvent($response));
        return $response;
    }

    public function product()
    {

        $credential=$this->credential();
        $data = ['subscriptionKey' => $credential['subskey']];
        $header = ['api_key' => $credential['apikey'], 'x-signature' => $credential['sign']];
        event(new RequestEvent(json_encode($data)));
        $response = Http::withHeaders($header)->post('http://68.183.188.18:3010/api/v0/info/post',['form_params'=>$data])->json();
        event(new ResponseEvent($response));
        unset($response['subscriptionKey']);
        return $response;


    }


    public function index($idtrx,$kode,$tujuan)
    {

        $msisdn = '62' . substr($tujuan, 1);
        $credential=$this->credential();
        $data = ['paket'=>$kode,'msisdn'=>$msisdn,'subscriptionKey' => $credential['subskey'],'callbackUrl'=>'https://112.78.139.28/v1/'];
        $header = ['api_key' => $credential['apikey'], 'x-signature' => $credential['sign']];
        event(new RequestEvent(json_encode($data)));
        $response = Http::withHeaders($header)
                    ->post('http://68.183.188.18:3010/api/v0/info/post',['form_params'=>$data])
                    ->json();
        event(new ResponseEvent($response));
        return array('idtrx'=>$idtrx,'kode'=>$kode,'tujuan'=>$tujuan,'msg'=>$response);


    }


    public function callback($request){






    }


}

<?php

namespace App\Services\ServiceData;

use App\Events\ServiceData\RequestEvent;
use App\Events\ServiceData\ResponseEvent;
use Illuminate\Support\Facades\Http;

class GetRequest
{

    protected $DbActivity;

    public function __construct(DbActivity $DbActivity)
    {
        $this->DbActivity = $DbActivity;
    }

    private static function sn($length)
    {
//        02140400001014384104
        $dpn = '02140';
        $tgh = '0000';
        for ($i = 0; $i < 1; $i++) {
            $acak = mt_rand(1, 9);
        }
        for ($i = 0; $i < 2; $i++) {
            $acak2 = mt_rand(11, 99);
        }
        $result = $dpn . $acak2 . $acak . $tgh . '';
        for ($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }


        return $result;
    }


    private function credential(): array
    {

        $apikey = '94zx9y2kh3jh4t3t3rsutrp2';
        $secret = 'G6GbaaAdYI';
        $subskey = 'B1SB0KCY0JBIPM42DIQI';
        $timestamp = gmdate('U');
        $sign = md5($apikey . $secret . $timestamp);
        return array('apikey' => $apikey, 'secret' => $secret, 'subskey' => $subskey, 'sign' => $sign);

    }

    public function balance()
    {
        $credential = $this->credential();
        $data = ['subscriptionKey' => $credential['subskey']];
        $header = ['api_key' => $credential['apikey'], 'x-signature' => $credential['sign']];
        event(new RequestEvent(json_encode($data)));
        $response = Http::withHeaders($header)->get('http://68.183.188.18:3010/api/v0/balance', $data)->json();
        event(new ResponseEvent($response));
        unset($response['subscriptionKey']);
        dd($response);
        return $response;
    }

    public function product()
    {

        $credential = $this->credential();
        $data = ['subscriptionKey' => $credential['subskey']];
        $header = ['api_key' => $credential['apikey'], 'x-signature' => $credential['sign']];
        event(new RequestEvent(json_encode($data)));
        $response = Http::withHeaders($header)->post('http://68.183.188.18:3010/api/v0/info/post', ['form_params' => $data])->json();
        event(new ResponseEvent($response));
        unset($response['subscriptionKey']);
        return $response;


    }


    public function index($idtrx, $kode, $tujuan)
    {
        $msisdn = '62' . substr($tujuan, 1);
        $credential = $this->credential();
        $header = ['api_key' => $credential['apikey'], 'x-signature' => $credential['sign']];

        if ($this->DbActivity->find($idtrx)){

            if (isset($this->DbActivity->find($idtrx)->response['requestId'])){

                return array('advice'=>true,'idtrx' => $idtrx, 'kode' => $kode, 'tujuan' => $tujuan, 'msg' => 'Data Tidak Ditemukan Dalam Database, silahkan cek web report / info vendor');


            }

            $data = ['requestId' => $this->DbActivity->find($idtrx)->response['requestId'],'subscriptionKey' => $credential['subskey']];
            $response = Http::withHeaders($header)
                ->post('http://68.183.188.18:3010/api/v0/status', ['form_params' => $data])
                ->json();

            if ($response['sukses']){


                return array('advice'=>true,'idtrx' => $idtrx, 'kode' => $kode, 'tujuan' => $tujuan, 'msg' => $response,'sn'=>self::sn(8));

            }

            return array('advice'=>true,'idtrx' => $idtrx, 'kode' => $kode, 'tujuan' => $tujuan, 'msg' => $response);


        }


        $data = ['paket' => $kode, 'msisdn' => $msisdn, 'subscriptionKey' => $credential['subskey'], 'callbackUrl' => 'https://voucherdiskon.com/bnNBTsWPwKFxtrdnwEcr/api/v1/utn'];
        event(new RequestEvent(json_encode($data)));
        $response = Http::withHeaders($header)
            ->post('http://68.183.188.18:3010/api/v0/transaction/post', ['form_params' => $data])
            ->json();
        event(new ResponseEvent($response));

        $this->DbActivity->activity_transaction($idtrx, $tujuan, $kode, $response['requestId'] ?? null, $data, $response);
        return array('idtrx' => $idtrx, 'kode' => $kode, 'tujuan' => $tujuan, 'msg' => $response);


    }


    public function callback($request)
    {


        if ($this->DbActivity->find($request->requestid)){



        }

    }


}

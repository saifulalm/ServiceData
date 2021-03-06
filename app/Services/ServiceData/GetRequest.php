<?php

namespace App\Services\ServiceData;

use App\Events\ServiceData\RequestEvent;
use App\Events\ServiceData\ResponseEvent;
use App\Jobs\schedule_advice;
use Ixudra\Curl\Facades\Curl;


class GetRequest
{

    protected $DbActivity;

    public function __construct(DbActivity $DbActivity)
    {
        $this->DbActivity = $DbActivity;
    }

    public function balance()
    {
        $credential = $this->credential();
        $data = ['subscriptionKey' => $credential['subskey']];
        $header = ['api_key' => $credential['apikey'], 'x-signature' => $credential['sign']];
        event(new RequestEvent(json_encode($data)));
        $response = Curl::to('http://68.183.188.18:3010/api/v0/balance')
            ->withHeaders($header)
            ->withdata($data)
            ->withTimeout(60)
            ->asJsonResponse()
            ->get();
        event(new ResponseEvent(json_encode($response)));
        unset($response->subscriptionKey);
        return $response;
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

    public function product()
    {

        $credential = $this->credential();
        $data = ['subscriptionKey' => $credential['subskey']];
        $header = ['api_key' => $credential['apikey'], 'x-signature' => $credential['sign']];
        event(new RequestEvent(json_encode($data)));
        $response = Curl::to('http://68.183.188.18:3010/api/v0/info/post')
            ->withHeaders($header)
            ->withdata($data)
            ->withTimeout(60)
            ->asJsonResponse()
            ->post();

        event(new ResponseEvent(json_encode($response)));
        unset($response->data->subscriptionKey);
        return $response;


    }

    public function index($idtrx, $kode, $tujuan): array
    {
        $msisdn = '62' . substr($tujuan, 1);
        $credential = $this->credential();
        $header = ['api_key' => $credential['apikey'], 'x-signature' => $credential['sign'], 'Content-Type' => 'application/x-www-form-urlencoded'];

        if ($this->DbActivity->find($idtrx)) {

            if (!isset($this->DbActivity->find($idtrx)->response['requestId'])) {

                return array('advice' => true, 'idtrx' => $idtrx, 'kode' => $kode, 'tujuan' => $tujuan, 'msg' => 'Data Tidak Ditemukan Dalam Database');


            }

            $data = ['requestId' => $this->DbActivity->find($idtrx)->response['requestId'], 'subscriptionKey' => $credential['subskey']];
            $response = Curl::to('http://68.183.188.18:3010/api/v0/status')
                ->withHeaders($header)
                ->withdata($data)
                ->withTimeout(60)
                ->asJsonResponse()
                ->get();
            unset($response->subscriptionKey);


            if ($response->status === "success") {


                return array('advice' => true, 'idtrx' => $idtrx, 'kode' => $kode, 'tujuan' => $tujuan, 'msg' => $response, 'sn' => self::sn());

            }

            return array('advice' => true, 'idtrx' => $idtrx, 'kode' => $kode, 'tujuan' => $tujuan, 'msg' => $response);


        }


        $data = ['paket' => $kode, 'msisdn' => $msisdn, 'subscriptionKey' => $credential['subskey'], 'callbackurl' => 'https://voucherdiskon.com/bnNBTsWPwKFxtrdnwEcr/api/v1/utn'];
        event(new RequestEvent(json_encode($data)));
        $response = Curl::to('http://68.183.188.18:3010/api/v0/transaction/post')
            ->withHeaders($header)
            ->withdata($data)
            ->withTimeout(60)
            ->asJsonResponse()
            ->post();
        event(new ResponseEvent(json_encode($response)));
        $this->DbActivity->activity_transaction($idtrx, $tujuan, $kode, $response->requestId ?? null, $data, $response);

        schedule_advice::dispatch($header, $idtrx, $kode, $tujuan, $response->requestId)->delay(now()->addSeconds(5));
        if ($response->success) {

            return array('idtrx' => $idtrx, 'kode' => $kode, 'tujuan' => $tujuan, 'status' => 'Proses', 'msg' => $response);

        }
        return array('idtrx' => $idtrx, 'kode' => $kode, 'tujuan' => $tujuan, 'msg' => $response);


    }

    private static function sn(): string
    {
        $dpn = '02140';
        $tgh = '0000';
        for ($i = 0; $i < 1; $i++) {
            $acak = mt_rand(1, 9);
        }
        for ($i = 0; $i < 2; $i++) {
            $acak2 = mt_rand(11, 99);
        }
        $result = $dpn . $acak2 . $acak . $tgh;
        for ($i = 0; $i < 8; $i++) {
            $result .= mt_rand(0, 9);
        }


        return $result;
    }

    public function callback($request): array
    {


        if ($this->DbActivity->find_requestid($request->input('request_id'))) {


            if ($request->status == 'success') {

                $data = array('callback' => true, 'idtrx' => $this->DbActivity->find_requestid($request->input('request_id'))->idtrx, 'tujuan' => $this->DbActivity->find_requestid($request->input('request_id'))->tujuan, 'msg' => $request->input('status'), 'sn' => self::sn());

            } else {

                $data = array('callback' => true, 'idtrx' => $this->DbActivity->find_requestid($request->input('request_id'))->idtrx,'tujuan' => $this->DbActivity->find_requestid($request->input('request_id'))->tujuan, 'msg' => $request->input('status'));

            }


            Curl::to('http://131.101.55.119:2074/')
                ->withdata($data)
                ->withTimeout(60)
                ->asJsonRequest()
                ->post();


            return array('Data' => True);

        }


        return array('Data' => False);

    }


}

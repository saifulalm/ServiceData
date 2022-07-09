<?php

namespace App\Jobs;

use App\Services\ServiceData\DbActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Ixudra\Curl\Facades\Curl;

class schedule_advice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $header;
    private $idtrx;
    private $kode;
    private $tujuan;
    private $requestid;

    public function __construct($header,$idtrx, $kode, $tujuan,$requestid)
    {
        $this->header = $header;
        $this->idtrx = $idtrx;
        $this->kode = $kode;
        $this->tujuan = $tujuan;
        $this->requestid=$requestid;
    }

    public function handle()
    {

        $data = ['requestId' => $this->requestid, 'subscriptionKey' => $this->credential()['subskey']];
        $response = Curl::to('http://68.183.188.18:3010/api/v0/status')
            ->withHeaders($this->header)
            ->withdata($data)
            ->withTimeout(60)
            ->asJsonResponse()
            ->get();
        unset($response->subscriptionKey);


        if ($response->status === "success") {


            $send = array('advice' => true, 'idtrx' => $this->idtrx, 'kode' => $this->kode, 'tujuan' => $this->tujuan, 'msg' => $response, 'sn' => self::sn());

        } else {

            $send = array('advice' => true, 'idtrx' => $this->idtrx, 'kode' => $this->kode, 'tujuan' => $this->tujuan, 'msg' => $response);
        }


        Curl::to('http://131.101.55.119:2074/')
            ->withdata($send)
            ->withTimeout(60)
            ->asJsonRequest()
            ->post();

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
}

<?php

namespace App\Services\ServiceData;

use App\Events\ServiceData\RequestEvent;
use App\Events\ServiceData\ResponseEvent;
use Illuminate\Support\Facades\Http;

class GetRequest
{

    public function balance()
    {
        $header = ['api_key' => '', 'x-signature' => ''];
        $data = ['subscriptionKey' => 'SN1MG6IYOZVQYK0ICN'];
        event(new RequestEvent(json_encode($data)));
        $response = Http::withHeaders($header)->get('http://68.183.188.18:3010/api/v0/balance', $data);
        event(new ResponseEvent($response));

        return $response;
    }

    public function index(){




    }


}

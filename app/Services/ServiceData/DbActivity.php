<?php

namespace App\Services\ServiceData;


use App\Models\ServiceData;
use App\Services;

class DbActivity
{

    public function activity_transaction($idtrx, $tujuan, $kode, $requestid, $request, $response)
    {


        $match = ['idtrx' => $idtrx];
        ServiceData::updateorcreate($match, ['tujuan' => $tujuan, 'kode' => $kode, 'requestid' => $requestid, 'request' => $request, 'response' => $response]);
    }


    public function find($idtrx)
    {


        return ServiceData::find($idtrx);

    }


    public function find_requestid($requestid)
    {


        return ServiceData::where('requestid', $requestid);

    }

}

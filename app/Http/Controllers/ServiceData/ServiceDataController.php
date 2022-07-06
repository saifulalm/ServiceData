<?php

namespace App\Http\Controllers\ServiceData;

use App\Http\Controllers\Controller;
use App\Services\ServiceData\GetRequest;
use Illuminate\Http\Request;


class ServiceDataController extends Controller
{

    protected $GetRequest;


    public function __construct(GetRequest $GetRequest)
    {
        $this->GetRequest = $GetRequest;
    }

    public function index(): array
    {

        $idtrx=$_GET['idtrx'];
        $kode=$_GET['kodeproduk'];
        $tujuan=$_GET['tujuan'];

        return $this->GetRequest->index($idtrx,$kode,$tujuan);


    }

    public function balance(){



        return $this->GetRequest->balance();


    }


    public function product(){



        return $this->GetRequest->product();


    }


    public function callback(Request $request){


        if ($request->hasAny(['requestid','status'])){

            return $this->GetRequest->callback($request);

        }


        return array('callback'=>false);

    }
}

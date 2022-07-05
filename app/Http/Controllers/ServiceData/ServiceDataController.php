<?php

namespace App\Http\Controllers\ServiceData;

use App\Http\Controllers\Controller;
use App\Services\ServiceData\GetRequest;

class ServiceDataController extends Controller
{

    protected $GetRequest;


    public function __construct(GetRequest $GetRequest)
    {
        $this->GetRequest = $GetRequest;
    }

    public function index(){

        $idtrx=$_GET['idtrx'];
        $kode=$_GET['kodeproduk'];
        $tujuan=$_GET['tujuan'];

        return $this->GetRequest->index($idtrx,$kode,$tujuan);


    }
}

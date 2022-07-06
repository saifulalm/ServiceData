<?php

namespace App\Services\ServiceData;



use App\Models\ServiceData;
use App\Services;

class DbActivity
{

 public function activity_transaction($idtrx,$tujuan,$kode,$request,$response)
 {


     $match = ['idtrx' => $idtrx];
     ServiceData::updateorcreate($match, ['tujuan' => $tujuan, 'kode' => $kode,  'request' => $request, 'response' => $response]);
 }

}

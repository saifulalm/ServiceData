<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceData extends Model
{
    protected $table = "message_transaction_servicedata";
    protected $primaryKey = 'idtrx';
    protected $keyType = 'string';
    protected $fillable = ['idtrx','kode','tujuan','requestid','request','response'];
    protected $casts = [
        'request'=>'array',
        'response' => 'array',
    ];
}

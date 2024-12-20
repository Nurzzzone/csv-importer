<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Product extends Model
{
    protected $table = 'tblProductData';
    protected $primaryKey = 'intProductDataId';
    protected $fillable = [
        'strProductName',
        'strProductDesc',
        'strProductCode',
        'decCost',
        'intStock',
        'dtmAdded',
        'dtmDiscontinued',
    ];
    protected $dates = [
        'dtmAdded',
        'dtmDiscontinued',
        'stmTimestamp',
    ];
    protected $casts = [
        'dtmAdded' => 'datetime',
        'dtmDiscontinued' => 'datetime',
        'stmTimestamp' => 'datetime',
    ];
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producer extends Model
{
    use HasFactory;

    protected $fillable = ['org_id', 'name', 'status'];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function itemPrices()
    {
        return $this->hasMany(ItemPrice::class, 'producer_id');
    }
}

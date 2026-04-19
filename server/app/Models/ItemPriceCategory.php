<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPriceCategory extends Model
{
    use HasFactory;

    protected $fillable = ['org_id', 'name', 'description', 'status'];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function itemPrices()
    {
        return $this->hasMany(ItemPrice::class, 'category_id');
    }
}

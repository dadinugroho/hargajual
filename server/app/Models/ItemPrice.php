<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_id',
        'producer_id',
        'category_id',
        'name',
        'base_unit',
        'qty_per_box',
        'purchase_price',
        'disc1',
        'disc2',
        'disc3',
        'disc4',
        'disc5',
        'disc6',
        'handling_cost',
        'handling_qty',
        'additional_cost_base_unit',
        'additional_cost_box',
        'cost_price_base_unit',
        'cost_price_box',
        'rounding_base_unit',
        'rounding_box',
        'profit_base_unit',
        'profit_box',
        'selling_price_base_unit',
        'selling_price_box',
    ];

    protected $casts = [
        'qty_per_box' => 'decimal:4',
        'purchase_price' => 'decimal:4',
        'disc1' => 'decimal:4',
        'disc2' => 'decimal:4',
        'disc3' => 'decimal:4',
        'disc4' => 'decimal:4',
        'disc5' => 'decimal:4',
        'disc6' => 'decimal:4',
        'handling_cost' => 'decimal:4',
        'additional_cost_base_unit' => 'decimal:4',
        'additional_cost_box' => 'decimal:4',
        'cost_price_base_unit' => 'decimal:4',
        'cost_price_box' => 'decimal:4',
        'rounding_base_unit' => 'decimal:4',
        'rounding_box' => 'decimal:4',
        'profit_base_unit' => 'decimal:4',
        'profit_box' => 'decimal:4',
        'selling_price_base_unit' => 'decimal:4',
        'selling_price_box' => 'decimal:4',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function producer()
    {
        return $this->belongsTo(Producer::class, 'producer_id');
    }

    public function category()
    {
        return $this->belongsTo(ItemPriceCategory::class, 'category_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';


    protected $fillable = [
        'id',
        'parent_stock_code',
        'stock_code',
        'colour',
        'colour_suffix',
        'size',
        'size_suffix',
        'description',
        'buy_price',
        'sell_price',
        'sell_price_web',
        'weight',
        'stock_id',
        'long_desc',
        'web_teaser',
        'deleted',
        'web',
        'stock_controlled',
        'discounted',
        'drop_ship',
        'discounts_disabled',
        'run_to_zero',
        'var_relief_qualified',
        'web_pageorder',
        'web_colourvalue',
        'epos_desc',
        'associated_ref',
        'availability',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'max_display_qty',
        'launch_date',
        'launch_time',
        'vat_rate',
        'stock_type',
        'stock_ttype',
        'stock_mid_type',
        'stock_sub_type',
        'lead_time',
        'sub_type_id',
        'reward_points',
        'reorder_multiple',
        'purchase_multiple',
        'min_level',
        'safe_level',
        'height',
        'width',
        'depth',
        'country_of_manufacture',
        'supplier_stock_code',
        'stock_option',
        'user_defined',
        'barcode',
        'imported',
    ];

    public function getColumns()
    {
        return $this->fillable;
    }

    public function parent()
    {
        return $this->hasOne('App\ParentProduct', 'stock_code', 'stock_code');
    }

    public function images()
    {
        return $this->hasMany('App\Images', 'stock_code', 'stock_code');
    }

    public function stock_levels()
    {
        return $this->hasMany('App\StockLevel', 'stock_code', 'stock_code');
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class SiteDefaults extends Model
{
    protected $table = 'site_defaults';

    protected $fillable = [
        'id',
        'site_id',
        'entity_type_id',
        'colour_attribute_id',
        'manufacturer_attribute_id',
        'size_attribute_id',
        'super_attribute_colour_label',
        'super_attribute_size_label',
        'manufacturer_category_name',
        'visibility_attribute_id',
        'search_terms_name_attribute_id',
        'search_terms_sku_attribute_id',
        'search_terms_description_attribute_id',
        'search_terms_manufacturer_attribute_id',
        'image_attribute_ids',
        'image_attribute_1_id',
        'image_attribute_2_id',
        'default_category_id',
        'category_attribute_1_id',
        'category_attribute_2_id',
        'category_attribute_3_id',
        'default_attribute_set_id',
        'manufacturer_name_attribute_id',
    ];

    public function getColumns()
    {
        return $this->fillable;
    }


}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceListStockItems extends Model
{
    protected $table = 'price_list_stock_items';

    protected $fillable = [
        'id',
        'price_list',
        'AmountValue',
        'BuyPrice',
        'BuyPriceMassage',
        'CalcBranch',
        'CalculatedPrice',
        'Discontinued',
        'DiscountValue',
        'FlagsStrucDiscPerSOrderLine',
        'MarkUp',
        'MassagedMarkUp',
        'OriginalCalculatedPrice',
        'QtyEnd',
        'QtyStart',
        'SellPrice',
        'StockCode',
        'StockDesc',
        'StockID',
        'TaxPortion',
        'TaxRate',
        'UseBuyPrice'
    ];

    public function getColumns()
    {
        return $this->fillable;
    }

}

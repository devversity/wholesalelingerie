<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Schema;
use File;
use DB;
use StdClass;
use App\KhaosControl;
use App\CompanyAddress;

use Illuminate\Support\Facades\Cache;

class Order extends Model
{
    protected $table = 'order';

    protected $fillable = [
        'id',
        'site_id',
        'entity_id',
        'increment_id',
        'state',
        'status',
        'coupon_code',
        'shipping_description',
        'customer_id',
        'grand_total',
        'sub_total',
        'tax_amount',
        'shipping_amount',
        'shipping_tax',
        'discount_amount',
        'shipping_address_id',
        'billing_address_id',
        'customer_email',
        'customer_firstname',
        'customer_surname',
        'currency_code',
        'ip_address',
        'shipping_method',
        'order_date',
        'imported'
    ];

    public function getColumns()
    {
        return $this->fillable;
    }

    public function customer()
    {
        return $this->hasOne('App\Customer', 'entity_id', 'customer_id');
    }

    public function grid()
    {
        return $this->hasOne('App\Order_Grid', 'entity_id', 'entity_id');
    }

    public function history()
    {
        return $this->hasMany('App\Order_History', 'entity_id', 'entity_id');
    }

    public function payment()
    {
        return $this->hasMany('App\Order_Payment', 'parent_id', 'entity_id');
    }

    public function items()
    {
        return $this->hasMany('App\Order_Item', 'order_id', 'entity_id');
    }

    public function billing_address()
    {
        return $this->hasOne('App\Order_Address', 'entity_id', 'billing_address_id');
    }

    public function shipping_address()
    {
        return $this->hasOne('App\Order_Address', 'entity_id', 'shipping_address_id');
    }

    public static function pull($db, $cmd = false, $site_id = 0, $prefix = "")
    {
        $tables = [
            'sales_order',
            'sales_order_address',
            'sales_order_grid',
            'sales_order_item',
            'sales_order_status_history',
            'sales_order_payment',
            'customer_entity',
            'customer_address_entity',
        ];

        $data = [];

        foreach ($tables as $table) {
            $data[$table] = $db->table($prefix.$table)->get();
        }

        if (isset($_GET['all'])) {
            echo "<pre>";
            print_r($data);exit;
        }

        foreach ($data as $table => $row) {
            if (count($row) == 0) {
                continue;
            }
            foreach ($row as $item) {

                if (isset($item->entity_id)) {
                    $cacheKey = $table . '_' . $item->entity_id . '_' . $site_id;
                } elseif (isset($item->item_id)) {
                    $cacheKey = $table . '_' . $item->item_id . '_' . $site_id;
                } else {
                    $cacheKey = $table . '_' . $site_id;
                }

                if (Cache::has($cacheKey)) {
                    continue;
                } else {
                    echo '.';
                }

                switch ($table) {
                    case "customer_address_entity":

                        CompanyAddress::updateOrCreate(
                            [
                                'entity_id' => $item->entity_id,
                                'site_id' => $site_id,
                            ],
                            [
                                'site_id' => $site_id,
                                'first_name' => $item->firstname,
                                'surname' => $item->lastname,
                                'company' => $item->company,
                                'street' => $item->street,
                                'city' => $item->city,
                                'region' => $item->region,
                                'postcode' => $item->postcode,
                                'telephone' => $item->telephone,
                                'country_code' => $item->country_id,
                            ]
                        );

                        Cache::put($cacheKey, 'cached');

                        break;
                    case "sales_order":
                        Order::updateOrCreate(
                            [
                                'entity_id' => $item->entity_id,
                                'site_id' => $site_id,
                            ],
                            [
                                'increment_id' => $item->increment_id,
                                'state' => $item->state,
                                'status' => $item->status,
                                'coupon_code' => $item->coupon_code,
                                'shipping_description' => $item->shipping_description,
                                'customer_id' => $item->customer_id,
                                'grand_total' => $item->grand_total,
                                'sub_total' => $item->subtotal,
                                'tax_amount' => $item->tax_amount,
                                'shipping_amount' => $item->shipping_amount,
                                'shipping_tax' => $item->shipping_tax_amount,
                                'discount_amount' => $item->discount_amount,
                                'shipping_address_id' => $item->shipping_address_id,
                                'billing_address_id' => $item->billing_address_id,
                                'customer_email' => $item->customer_email,
                                'customer_firstname' => $item->customer_firstname,
                                'customer_surname' => $item->customer_lastname,
                                'currency_code' => $item->order_currency_code,
                                'ip_address' => $item->remote_ip,
                                'shipping_method' => $item->shipping_method,
                                'order_date' => $item->created_at
                            ]
                        );

                        Cache::put($cacheKey, 'cached');

                        break;
                    case "sales_order_address":
                        Order_Address::updateOrCreate(
                            [
                                'entity_id' => $item->entity_id,
                                'site_id' => $site_id,
                            ],
                            [
                                'customer_firstname' => $item->firstname,
                                'customer_surname' => $item->lastname,
                                'street' => $item->street,
                                'city' => $item->city,
                                'region' => $item->region,
                                'postcode' => $item->postcode,
                                'country' => $item->country_id,
                                'email' => $item->email,
                                'telephone' => $item->telephone,
                                'company' => $item->company,
                                'address_type' => $item->address_type,
                            ]
                        );

                        Cache::put($cacheKey, 'cached');

                        break;
                    case "sales_order_grid":
                        Order_Grid::updateOrCreate(
                            [
                                'entity_id' => $item->entity_id,
                                'site_id' => $site_id,
                            ],
                            [
                                'base_grand_total' => $item->base_grand_total,
                                'base_total_paid' => $item->base_total_paid,
                                'grand_total' => $item->grand_total,
                                'total_paid' => $item->total_paid,
                                'base_currency_code' => $item->base_currency_code,
                                'order_currency_code' => $item->order_currency_code,
                                'shipping_name' => $item->shipping_name,
                                'billing_name' => $item->billing_name,
                                'billing_address' => $item->billing_address,
                                'shipping_address' => $item->shipping_address,
                                'shipping_information' => $item->shipping_information,
                                'customer_email' => $item->customer_email,
                                'subtotal' => $item->subtotal,
                                'shipping_and_handling' => $item->shipping_and_handling,
                                'customer_name' => $item->customer_name,
                                'payment_method' => $item->payment_method,
                                'total_refunded' => $item->total_refunded,
                            ]
                        );

                        Cache::put($cacheKey, 'cached');

                        break;
                    case "sales_order_item":
                        Order_Item::updateOrCreate(
                            [
                                'item_id' => $item->item_id,
                                'site_id' => $site_id,
                            ],
                            [
                                'order_id' => $item->order_id,
                                'product_id' => $item->product_id,
                                'product_type' => $item->product_type,
                                'product_options' => $item->product_options,
                                'weight' => $item->weight,
                                'sku' => $item->sku,
                                'product_name' => $item->name,
                                'description' => $item->description,
                                'quantity' => $item->qty_ordered,
                                'price' => $item->price,
                                'tax_percent' => $item->tax_percent,
                                'tax_amount' => $item->tax_amount,
                                'discount_percent' => $item->discount_percent,
                                'discount_amount' => $item->discount_amount,
                                'row_total' => $item->row_total,
                                'row_weight' => $item->row_weight,
                            ]
                        );

                        Cache::put($cacheKey, 'cached');

                        break;
                    case "sales_order_status_history":
                        Order_History::updateOrCreate(
                            [
                                'entity_id' => $item->entity_id,
                                'site_id' => $site_id,
                            ],
                            [
                                'comment' => $item->comment,
                                'status' => $item->status,
                                'entity_name' => $item->entity_name,
                            ]
                        );

                        Cache::put($cacheKey, 'cached');

                        break;
                    case "customer_entity":
                        Customer::updateOrCreate(
                            [
                                'entity_id' => $item->entity_id,
                                'site_id' => $site_id,
                            ],
                            [
                                'first_name' => $item->firstname,
                                'surname' => $item->lastname,
                                'default_billing' => $item->default_billing,
                                'default_shipping' => $item->default_shipping,
                                'email' => $item->email,
                            ]
                        );

                        Cache::put($cacheKey, 'cached');

                        break;
                    case "sales_order_payment":

                        Order_Payment::updateOrCreate(
                            [
                                'entity_id' => $item->entity_id,
                                'site_id' => $site_id,
                            ],
                            [
                                'parent_id' => $item->parent_id,
                                'amount_paid' => $item->amount_paid,
                                'shipping_amount' => $item->shipping_amount,
                                'amount_refunded' => $item->amount_refunded,
                                'shipping_refunded' => $item->shipping_refunded,
                                'method' => $item->method,
                                'cc_exp_month' => $item->cc_exp_month,
                                'cc_exp_year' => $item->cc_exp_year,
                                'cc_ss_start_month' => $item->cc_ss_start_month,
                                'cc_ss_start_year' => $item->cc_ss_start_year,
                                'cc_debug_request_body' => $item->cc_debug_request_body,
                                'cc_secure_verify' => $item->cc_secure_verify,
                                'cc_approval' => $item->cc_approval,
                                'cc_last_4' => $item->cc_last_4,
                                'cc_status_description' => $item->cc_status_description,
                                'cc_cid_status' => $item->cc_cid_status,
                                'cc_owner' => $item->cc_owner,
                                'cc_type' => $item->cc_type,
                                'cc_status' => $item->cc_status,
                                'cc_ss_issue' => $item->cc_ss_issue,
                                'cc_avs_status' => $item->cc_avs_status,
                                'protection_eligibility' => $item->protection_eligibility,
                                'last_trans_id' => $item->last_trans_id,
                                'po_number' => $item->po_number,
                                'anet_trans_method' => $item->anet_trans_method,
                                'account_status' => $item->account_status,
                                'echeck_bank_name' => $item->echeck_bank_name,
                                'echeck_account_type' => $item->echeck_account_type,
                                'echeck_type' => $item->echeck_type,
                                'echeck_routing_number' => $item->echeck_routing_number,
                                'echeck_account_name' => $item->echeck_account_name,
                            ]
                        );

                        Cache::put($cacheKey, 'cached');

                        break;
                }
            }
        }

        echo "- Pulled data (site id: ".$site_id.") from ".implode(", ", $tables).".";
        echo $cmd ? "\n" : "<br/>";
    }

    public static function transform($dir = '', $o_id = null, $cmd = false, $site_information = null, $xml = 'KSDXMLImportFormat')
    {
        if (empty($dir)) {
            return;
        }

        $site_id = 0;
        if (!empty($site_information->id)) {
            $site_id = $site_information->id;
        }

        if (is_null($o_id)) {
            $orders = Order::where('imported', '=', 0)
                ->where('site_id', '=', $site_id)
                ->get();
        } else {
            $orders = Order::where('increment_id', '=', $o_id)
                ->where('site_id', '=', $site_id)
                ->get();
        }

        foreach ($orders as &$order) {
            $order->customer = DB::table('customer')->where('site_id', '=', $site_id)->where('entity_id','=', $order->customer_id)->first();
            $order->billing_address = DB::table('order_address')->where('site_id', '=', $site_id)->where('entity_id','=', $order->billing_address_id)->first();
            $order->shipping_address = DB::table('order_address')->where('site_id', '=', $site_id)->where('entity_id','=', $order->shipping_address_id)->first();
            $order->grid = DB::table('order_grid')->where('site_id', '=', $site_id)->where('entity_id','=', $order->entity_id)->first();
            $order->history = DB::table('order_history')->where('site_id', '=', $site_id)->where('entity_id','=', $order->entity_id)->get();
            $order->items = DB::table('order_item')->where('site_id', '=', $site_id)->where('order_id','=', $order->entity_id)->get();
            $order->payment = DB::table('order_payment')->where('site_id', '=', $site_id)->where('parent_id','=', $order->entity_id)->get();
        }

        if (count($orders) == 0) {
            return;
        }

        foreach ($orders as $order) {

            // Change billing address to specified default
            if ($order->customer->default_billing) {
                $default_billing_address = CompanyAddress::where('entity_id', '=', $order->customer->default_billing)
                    ->where('site_id', '=', $site_id)
                    ->first();

                if (isset($default_billing_address->entity_id)) {
                    $order->billing_address->customer_firstname = $default_billing_address->first_name;
                    $order->billing_address->customer_surname = $default_billing_address->surname;
                    $order->billing_address->street = $default_billing_address->street;
                    $order->billing_address->city = $default_billing_address->city;
                    $order->billing_address->region = $default_billing_address->region;
                    $order->billing_address->postcode = $default_billing_address->postcode;
                    $order->billing_address->country = $default_billing_address->country_code;
                    $order->billing_address->email = $default_billing_address->email;
                    $order->billing_address->telephone = $default_billing_address->telephone;
                    $order->billing_address->company = $default_billing_address->company;
                }
            }

            $order->billing_address->street = preg_replace('/\s+/', ' ', $order->billing_address->street);
            $order->shipping_address->street = preg_replace('/\s+/', ' ', $order->shipping_address->street);

            // Split addresses into 3 lines.
            $delivery = explode(",", $order->shipping_address->street);
            $order->shipping_address->addr1 = isset($delivery[0]) ? trim($delivery[0]) : trim($order->shipping_address->street);
            $order->shipping_address->addr2 = isset($delivery[1]) ? trim($delivery[1]) : '';
            $order->shipping_address->addr3 = isset($delivery[2]) ? trim($delivery[2]) : '';
            $billing = explode(",", $order->billing_address->street);
            $order->billing_address->addr1 = isset($billing[0]) ? trim($billing[0]) : trim($order->billing_address->street);
            $order->billing_address->addr2 = isset($billing[1]) ? trim($billing[1]) : '';
            $order->billing_address->addr3 = isset($billing[2]) ? trim($billing[2]) : '';

            if (isset($_GET['debug'])) {
                echo "<Pre>";
                print_r($order->items);
            }

            $price_override = [];
            if (!empty($order->items)) {
                foreach ($order->items as $item) {
                    if ($item->product_type == "configurable") {
                        $price_override[$item->sku] = $item->price;
                    }
                }
            }

            $order->po_number = "";
            $order->mapping_type_id = -1;

            $skip = 0;
            $invoice_priority = "";

            if (isset($order->payment)) {
                foreach ($order->payment as &$payment) {
                    $payment->auth_code = '';

                    switch($payment->method) {
                        case "checkmo":
                            $payment->payment_type_id = 0;
                            break;
                        case "purchaseorder":
                            $payment->payment_type_id = 3;
                            break;
                        case "banktransfer":
                            $payment->payment_type_id = 3;
                            $invoice_priority = "Bank Transfer Payment";
                            break;
                        case "paypal_express":
                        case "hosted_pro":
                            $payment->payment_type_id = 2;
                            $payment->auth_code = "PAYPAL";
                            break;
                        default:
                            $skip = 1;
                            echo ($payment->method." not mapped (".$order->increment_id."_".$order->entity_id.")");
                            echo $cmd ? "\n" : "<br/>";
                            @mail("sales@kevco.co.uk", $payment->method." not mapped (".$order->increment_id."_".$order->entity_id.")", $payment->method." not mapped (".$order->entity_id.")");
                            break;
                    }
                    if (!empty($payment->po_number)) {
                        $order->po_number = $payment->po_number;
                    }
                }
            }

            if ($skip == 0) {
                $contents = View('khaos/import/'.$xml, [
                    'order' => $order,
                    'site_information' => $site_information,
                    'invoice_priority' => $invoice_priority,
                    'price_override' => $price_override
                ])->render();
                File::put($dir.$order->increment_id."_".$order->entity_id.".xml", $contents);
                echo "Transformed order ".$order->entity_id;
                echo $cmd ? "\n" : "<br/>";
            }

        }
    }

    public static function export($dir, $o_id = null, $cmd = false, $site_information = null, $khaos_version = '')
    {
        if (empty($dir)) {
            return;
        }

        if ($khaos_version == 'kls') {
            $khaos_control = new KhaosControl('kls');
        } else {
            $khaos_control = new KhaosControl;
        }

        $site_id = 0;
        if (!empty($site_information->id)) {
            $site_id = $site_information->id;
        }

        $archive_folder = $dir . "archive/";
        if (!is_dir($archive_folder)) {
            mkdir($archive_folder);
        }
        $files = scandir($dir);

        foreach ($files as $file) {
            if (in_array($file, ['.', '..', 'archive'])) {
                continue;
            }
            $xml = @file_get_contents($dir . $file);
            $split = explode(".", $file);

            if (!isset($split[0])) {
                continue;
            }

            $ids = explode("_", $split[0]);
            if (count($ids) > 1) {
                $increment_id = $ids[0];
                $entity_id = $ids[1];
            } else {
                continue;
            }

            if (!is_null($increment_id)  && !is_null($o_id) && $increment_id != $o_id) {
                continue;
            }

            $order = Order::where('site_id','=', $site_id)
                ->where('entity_id', '=', $entity_id)
                ->first();

            if (!isset($order->entity_id)) {
                echo "Order not found in API database: ".$order->entity_id." (Site ID: ".$site_id.")";
            } else {
                $response = $khaos_control->request("ImportOrders", 0, null, $xml);

                if ($response != "error") {
                    $order->imported = 1;
                    $order->save();
                    echo "Sent order to Khaos: ".$order->increment_id." (".$order->entity_id.") (Site ID: ".$site_id.")";
                } else {
                    $response = $khaos_control->request("ImportOrders", 0, null, $xml, null, null, true);
                    $email = "sales@kevco.co.uk";
                    if (!isset($_GET['debug'])) {
                        @mail($email, "Export Order Error: ".$order->increment_id, "https://www.wholesalelingerie.co.uk/io/public/orders/".$order->increment_id."?debug=Y");
                    }
                    print_r($response);
                    //echo "Order NOT sent to Khaos, see email (".$email.") or try <a href='https://www.wholesalelingerie.co.uk/io/public/orders/".$order->increment_id."?debug=Y'>https://www.wholesalelingerie.co.uk/io/public/orders/".$order->increment_id."?debug=Y</a>";
                }

            }

            echo $cmd ? "\n" : "<br/>";

            $old = $dir . $file;
            $new = $archive_folder . $file;
            @rename($old, $new);
        }
    }

    public static function import($dir, $o_id = null, $cmd = false, $db, $site_information = null, $prefix = '')
    {
        if (empty($dir)) {
            return;
        }

        $site_id = 0;
        if (!empty($site_information->id)) {
            $site_id = $site_information->id;
        }

        $orders = Order::where('site_id', '=', $site_id)
            ->where('imported', '=', 1)
            ->get();

        foreach ($orders as $order) {

            $db->table($prefix.'sales_order')
                ->updateOrInsert(
                    [
                        'entity_id' => $order->entity_id,
                    ],
                    [
                        'state' => 'complete',
                        'status' => 'complete',
                    ]
                );
            $db->table($prefix.'sales_order_grid')
                ->updateOrInsert(
                    [
                        'entity_id' => $order->entity_id,
                    ],
                    [
                        'status' => 'complete',
                    ]
                );
        }
    }

    public static function clean($dir, $cmd = false, $archive_time = 604800)
    {
        if (empty($dir)) {
            return;
        }

        $archive_time = 30 * 86400;
        $archived_files = glob($dir . "archive/*");
        foreach ($archived_files as $file) {
            $filemtime = filemtime($file);
            if (time() - $filemtime >= $archive_time) {
                echo "Removing file " . $file . " from archive";
                echo $cmd ? "\n" : "<br/>";
                @unlink($file);
            }
        }
    }

    /**
     * Pull retail orders and push them to Khaos.
     *
     * @param null $site_id
     * @param null $o_id
     */
    public static function retail_orders($site_id = 0, $o_id = 0, $cmd = false, $base_dir = "./../public/khaos/out/")
    {
        if ($site_id == 0 || $site_id == null) {
            $sites = Sites::where('active', '=', 1)->get();
        } else {
            $sites = Sites::where('active', '=', 1)->where('id', '=', $site_id)->get();
        }

        if (count($sites) == 0) {
            die("No site/s found for id: ".$site_id.". Please check if the site id is valid and the site is active.");
        }

        foreach ($sites as $site) {
            $site_db = DB::connection($site->db_connection);

            $dir = $base_dir.$site->name."/";

            if (!is_dir($dir)) {
                mkdir($dir);
            }

            Self::pull($site_db, $cmd, $site->id, $site->prefix);
            Self::clean($dir);
            Self::transform($dir, $o_id, $cmd, $site);
            Self::export($dir, $o_id, $cmd, $site, 'kls');
            Self::import($dir, $o_id, false, $site_db, $site, $site->prefix);

        }
    }

}

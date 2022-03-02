<?php

namespace App;

use App\DefaultBillingAddress;
use Illuminate\Database\Eloquent\Model;
use Schema;

class Customer extends Model
{
    protected $table = 'customer';

    protected $fillable = [
        'id',
        'site_id',
        'entity_id',
        'first_name',
        'surname',
        'default_billing',
        'default_shipping',
        'email',
        'imported'
    ];

    public function billing_address()
    {
        return $this->HasOne('App\CompanyAddress', 'id', 'default_billing');
    }

    public function getColumns()
    {
        return $this->fillable;
    }

    public static function sync($db)
    {
        $tables = [
            'customer_entity',
            'customer_address_entity',
        ];

        $data = [];

        foreach ($tables as $table) {
            $data[$table] = $db->table($table)->get();
        }

        foreach ($data as $table => $row) {
            if (count($row) == 0) {
                continue;
            }
            foreach ($row as $item) {
                switch ($table) {
                    case "customer_address_entity":

                        CompanyAddress::updateOrCreate(
                            [
                                'entity_id' => $item->entity_id,
                            ],
                            [
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

                        break;
                    case "customer_entity":
                        Customer::updateOrCreate(
                            [
                                'entity_id' => $item->entity_id,
                            ],
                            [
                                'first_name' => $item->firstname,
                                'surname' => $item->lastname,
                                'default_billing' => $item->default_billing,
                                'default_shipping' => $item->default_shipping,
                                'email' => $item->email,
                            ]
                        );
                        break;
                }
            }
        }

        $customers = Customer::with('billing_address')
                ->where('default_billing', '>', 0)
                ->where('imported', '=', 1)
                ->get();

        foreach ($customers as $customer) {

            $default_billing_address = new DefaultBillingAddress;
            $default_billing_address->customer_id = $customer->id;
            $default_billing_address->first_name = $customer->billing_address->first_name;
            $default_billing_address->surname = $customer->billing_address->surname;
            $default_billing_address->company = $customer->billing_address->company;
            $default_billing_address->street = $customer->billing_address->street;
            $default_billing_address->city = $customer->billing_address->city;
            $default_billing_address->region =$customer->billing_address->region;
            $default_billing_address->postcode = $customer->billing_address->postcode;
            $default_billing_address->country_code = $customer->billing_address->country_code;
            $default_billing_address->telephone = $customer->billing_address->telephone;
            $default_billing_address->email = $customer->billing_address->email;
            $default_billing_address->save();

            $update = Customer::find($customer->id);
            $update->imported = 1;
            $update->save();
        }

        echo "<pre>";
        print_r($customers);exit;


    }

}

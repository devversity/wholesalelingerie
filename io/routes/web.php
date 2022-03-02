<?php

$validIps = [
    "82.5.81.192",
    "185.4.176.131"
];

$remoteAddr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
$remoteAddr2 = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : null;
$artisan = isset($_SERVER['PHP_SELF']) && $_SERVER['PHP_SELF'] === 'artisan';

if (
    !in_array($remoteAddr, $validIps) &&
    !in_array($remoteAddr2, $validIps) &&
    $artisan === false
) {
    if (!empty($remoteAddr2)) {
        dd('Permission not granted for '. $remoteAddr2);
    } else {
        dd('Permission not granted for '. $remoteAddr);
    }
}

Route::get('/', 'KhaosController@index');
Route::get('/sites', 'KhaosController@sites');
Route::get('/sites/{i}', 'KhaosController@sites');
Route::get('/data/{table}', 'KhaosController@data');
Route::get('/export/{table}', 'KhaosController@csv');
Route::get('/data/{table}/{key}', 'KhaosController@data');

Route::post('/sites', 'KhaosController@sites');
Route::get('/stock_code', 'KhaosController@stock_code');

Route::get('/request/{i}/{j}', 'KhaosController@request');
Route::get('/request/{i}', 'KhaosController@request');

Route::get('/migrate/{i}', 'KhaosController@migrate');
Route::get('/migrate/{i}/{j}', 'KhaosController@migrate');

Route::get('/orders', 'KhaosController@orders');
Route::get('/customer_sync', 'KhaosController@customer_sync');
Route::get('/orders/{i}', 'KhaosController@orders');
Route::get('/stock_sync', 'KhaosController@stock_sync');
Route::get('/categories/{i}', 'KhaosController@categories');

Route::get('/retail/clear', 'RetailController@clear');
Route::get('/retail/clear/{i}', 'RetailController@clear');
Route::get('/retail/refresh', 'RetailController@refresh');
Route::get('/retail/refresh/{i}', 'RetailController@refresh');
Route::get('/retail/migrate/{i}/{j}', 'RetailController@migrate');
Route::get('/retail/migrate/{i}/{j}/{k}', 'RetailController@migrate');
Route::get('/retail/migrate/{i}', 'RetailController@migrate');
Route::get('/retail/migrate', 'RetailController@refresh');
Route::get('/retail/attributes/{i}/{j}', 'RetailController@attributes');
Route::get('/retail/attributes/{i}', 'RetailController@attributes');
Route::get('/retail/attributes', 'RetailController@attributes');
Route::get('/retail/orders', 'RetailController@orders');
Route::get('/retail/orders/{i}', 'RetailController@orders');
Route::get('/retail/orders/{i}/{j}', 'RetailController@orders');
Route::get('/retail/orders/{i}/{j}/{k}', 'RetailController@orders');


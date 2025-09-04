<?php

use Illuminate\Support\Facades\Route;
use Razorpay\Api\Api;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/rozar', function(){
    $api = new Api('rzp_test_RBVPV5iC82WfNr','rzp_test_RBVPV5iC82WfNr');
    $accountId = 'pay_RBzNTh57QODlyS';
    $account = $api->account->fetch($accountId)->webhooks()->all();
    dd($account);
});

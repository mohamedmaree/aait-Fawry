<?php

return [


    /*
    |--------------------------------------------------------------------------
    | Merchant account authorization info
    |--------------------------------------------------------------------------
    |
    |
    */

    "merchant_code"   => "" ,
    "merchant_key"    => "" ,

    /*
    |--------------------------------------------------------------------------
    | Paymob Mode
    |--------------------------------------------------------------------------
    |
    | Mode only values: "test" or "live"
    |
    */

    "mode"     => "test",

    /*
    |--------------------------------------------------------------------------
    | Paymob currency
    |--------------------------------------------------------------------------
    | EGP , SAR , USD, .. etc
    */

    "currency" => "EGP" ,

    /*
    |--------------------------------------------------------------------------
    | TEST Payment Request url
    |--------------------------------------------------------------------------
    */

    "test_urls" => [
            "create_card_token_url"    => "https://atfawry.fawrystaging.com/fawrypay-api/api/cards/cardToken",
            "list_card_url"            => "https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/cards/cardToken",
            "charge_card_url"          => "https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/charge",
            "refund_card_url"          => "https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/refund",
            "cancel_unpaid_url"        => "https://atfawry.fawrystaging.com/ECommerceWeb/api/orders/cancel-unpaid-order"
            ],
    /*
    |--------------------------------------------------------------------------
    | LIVE Payment Request url
    |--------------------------------------------------------------------------
    */

    "live_urls" => [ 
            "create_card_token_url"    => "https://www.atfawry.com/ECommerceWeb/api/cards/cardToken",
            "list_card_url"            => "https://www.FawryPay.com/ECommerceWeb/Fawry/cards/cardToken",
            "charge_card_token_url"    => "https://www.atfawry.com/ECommerceWeb/Fawry/payments/charge",
            "refund_card_url"          => "https://www.atfawry.com/ECommerceWeb/Fawry/payments/refund",
            "cancel_unpaid_url"        => "https://www.atfawry.com/ECommerceWeb/api/orders/cancel-unpaid-order",
    ],



];
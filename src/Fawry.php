<?php
namespace Maree\Fawry;

class Fawry {

    // $customer_data = ['customer_id' => '1','customer_name'=>'maree' ,'customer_mobile' => '01002700084', 'customer_email' => 'm7mdmaree26@gmail.com'];
    // $card_data     = ['card_number' => '1111111111111','card_expiry_year' => '2023','expiry_year' => '2023' ,'expiry_month' => '05', 'card_expiry_month' => '05', 'cvv' => '123', 'is_default' => false];
    public static function createCardToken($customer_data = [], $card_data = []) {
        $url = config('fawry.mode') == 'live' ? config('fawry.live_urls')['create_card_token_url'] : config('fawry.test_urls')['create_card_token_url'];
        $client    = new \GuzzleHttp\Client();
        $response  = $client->post($url, [
          \GuzzleHttp\RequestOptions::JSON => [
                "merchantCode"      => config('fawry.merchant_code'),
                "customerProfileId" => md5($customer_data['customer_id']),
                "customerMobile"    => $customer_data['customer_mobile'],
                "customerEmail"     => $customer_data['customer_email'],
                "cardNumber"        => $card_data['card_number'],
                "expiryYear"        => $card_data['expiry_year'],
                "expiryMonth"       => $card_data['expiry_month'],
                "cvv"               => $card_data['cvv'],
                'isDefault'         => $card_data['is_default']
            ],
        ]);
        $response = json_decode($response->getBody(), true);
        return json_encode( $response );
    }

    public static function listCustomerTokens($customer_id='')
    {
        $url = config('fawry.mode') == 'live' ? config('fawry.live_urls')['list_card_url'] : config('fawry.test_urls')['list_card_url'];

        $client    = new \GuzzleHttp\Client();
        $response  = $client->post($url, [
          \GuzzleHttp\RequestOptions::JSON => [
                "merchantCode"      => config('fawry.merchant_code'),
                "customerProfileId" => md5($customer_id),
                'signature'         => hash('sha256',config('fawry.merchant_code') .md5($customer_id) .config('fawry.merchant_key')),
            ],
        ]);

        $response = json_decode($response->getBody(), true);
        return json_encode( $response );
    }


    public static function deleteCardToken($customer_id='', $customer_card_token='')
    {
        $url = config('fawry.mode') == 'live' ? config('fawry.live_urls')['list_card_url'] : config('fawry.test_urls')['list_card_url'];

        $client    = new \GuzzleHttp\Client();
        $response  = $client->delete($url, [
          \GuzzleHttp\RequestOptions::JSON => [
                "merchantCode"      => config('fawry.merchant_code'),
                "customerProfileId" => md5($customer_id),
                'cardToken'         => $customer_card_token,
                'signature'         => hash('sha256',config('fawry.merchant_code').md5($customer_id).$customer_card_token.config('fawry.merchant_key')),
            ],
        ]);
        $response = json_decode($response->getBody(), true);
        return json_encode( $response ); 
    }

    //$customer_data = ['customer_id' => '1', 'customer_mobile' => '010027*****', 'customer_email' => 'm7mdmaree26@gmail.com'];
    public static function payByCardToken($merchantRefNum='', $customer_card_token='', $customer_data =[], $amount = 1 , $chargeItems = [], $description = '')
    {
        $url = config('fawry.mode') == 'live' ? config('fawry.live_urls')['charge_card_url'] : config('fawry.test_urls')['charge_card_url'];

        $client    = new \GuzzleHttp\Client();
        $response  = $client->post($url, [
          \GuzzleHttp\RequestOptions::JSON => [
                "merchantCode"      => config('fawry.merchant_code'),
                'merchantRefNum'    => $merchantRefNum,
                'paymentMethod'     => 'CARD',
                'cardToken'         => $customer_card_token,
                "customerProfileId" => md5($customer_data['customer_id']),
                "customerMobile"    => $customer_data['customer_mobile'],
                "customerEmail"     => $customer_data['customer_email'],
                'amount'            => number_format((float) $amount, 2, '.', ''),
                'currencyCode'      => config('fawry.currency'),
                'chargeItems'       => $chargeItems,
                'description'       => $description,
                'signature'         => hash('sha256',config('fawry.merchant_code').$merchantRefNum .md5($customer_data['customer_id']).'CARD'.number_format((float) $amount, 2, '.', '').$customer_card_token.config('fawry.merchant_key')),
            ],
        ]);
        $response = json_decode($response->getBody(), true);
        return json_encode( $response );
    }

    //$customer_data = ['customer_id' => '1','customer_name' => 'mohamed maree' 'customer_mobile' => '010027*****', 'customer_email' => 'm7mdmaree26@gmail.com'];
    public static function payByCardToken3DS($merchantRefNum='', $customer_card_token='', $cvv='', $customer_data = [], $amount = 1 , $callbackURL='', $chargeItems = [], $authCaptureModePayment = false, $language = 'en-gb', $description = '')
    {
        $url = config('fawry.mode') == 'live' ? config('fawry.live_urls')['charge_card_url'] : config('fawry.test_urls')['charge_card_url'];
        $client    = new \GuzzleHttp\Client();
        $response  = $client->post($url, [
          \GuzzleHttp\RequestOptions::JSON => [
                "merchantCode"      => config('fawry.merchant_code'),
                "customerName"      => $customer_data['customer_name'],
                "customerMobile"    => $customer_data['customer_mobile'],
                "customerEmail"     => $customer_data['customer_email'],
                "customerProfileId" => md5($customer_data['customer_id']),
                "cardToken"         => $customer_card_token,
                "cvv"               => $cvv,
                "merchantRefNum"    => $merchantRefNum,
                "amount"            => number_format((float) $amount, 2, '.', ''),
                "currencyCode"      => config('fawry.currency'),
                "language"          => $language,
                "chargeItems"       => $chargeItems,
                "enable3DS"         => true,
                "authCaptureModePayment" => $authCaptureModePayment,
                "returnUrl"         => $callbackURL,
                "signature"         => hash('sha256', config('fawry.merchant_code').$merchantRefNum .md5($customer_data['customer_id']) .'CARD' .number_format((float) $amount, 2, '.', '') .$customer_card_token .$cvv .$callbackURL .config('fawry.merchant_key')),
                "paymentMethod"     => "CARD",
                "description"       => $description
            ],
        ]);
        $response = json_decode($response->getBody(), true);
        return json_encode( $response ); 
    }

    //$customer_data = ['customer_id' => '1','customer_name' => 'mohamed maree' 'customer_mobile' => '010027*****', 'customer_email' => 'm7mdmaree26@gmail.com'];
    //$card_data     = ['card_number'=>11111**********,'card_expiry_year' => '23', 'card_expiry_month' => '05','cvv' =>'123'];
    public static function payByCard($merchantRefNum='', $card_data = [], $customer_data = [], $amount= 1, $chargeItems = [], $language = 'en-gb' , $description = '')
    {
        $url = config('fawry.mode') == 'live' ? config('fawry.live_urls')['charge_card_url'] : config('fawry.test_urls')['charge_card_url'];
        $client    = new \GuzzleHttp\Client();
        $response  = $client->post($url, [
          \GuzzleHttp\RequestOptions::JSON => [
                "merchantCode"      => config('fawry.merchant_code'),
                "customerName"      => $customer_data['customer_name'],
                "customerMobile"    => $customer_data['customer_mobile'],
                "customerEmail"     => $customer_data['customer_email'],
                "customerProfileId" => md5($customer_data['customer_id']),
                "cardNumber"        => $card_data['card_number'],
                "cardExpiryYear"    => $card_data['card_expiry_year'],
                "cardExpiryMonth"   => $card_data['card_expiry_month'],
                "cvv"               => $card_data['cvv'],
                "merchantRefNum"    => $merchantRefNum,
                "amount"            => number_format((float) $amount, 2, '.', ''),
                "currencyCode"      => config('fawry.currency'),
                "language"          => $language,
                "chargeItems"       => $chargeItems,
                "paymentMethod"     => "CARD",
                "description"       => $description,
                "signature"         => hash("sha256", config('fawry.merchant_code') .
                    $merchantRefNum .
                    md5($customer_data['customer_id']) .
                    'CARD' .
                    number_format((float) $amount, 2, '.', '') .
                    $card_data['card_number'] .
                    $card_data['card_expiry_year'] .
                    $card_data['card_expiry_month'] .
                    $card_data['cvv'] .
                    config('fawry.merchant_key')
                )
            ],
        ]);
        $response = json_decode($response->getBody(), true);
        return json_encode( $response ); 
    }

    //$customer_data = ['customer_id' => '1','customer_name' => 'mohamed maree' 'customer_mobile' => '010027*****', 'customer_email' => 'm7mdmaree26@gmail.com'];
    //$card_data     = ['card_number'=>11111**********,'card_expiry_year' => '23', 'card_expiry_month' => '05','cvv' =>'123'];
    public static function payByCard3DS($merchantRefNum='', $card_data = [], $customer_data = [], $amount =1 , $calbackURL='', $chargeItems = [], $authCaptureModePayment = false, $language = 'en-gb' , $description = '')
    {
        $url = config('fawry.mode') == 'live' ? config('fawry.live_urls')['charge_card_url'] : config('fawry.test_urls')['charge_card_url'];
        $client    = new \GuzzleHttp\Client();
        $response  = $client->post($url, [
          \GuzzleHttp\RequestOptions::JSON => [
                "merchantCode"      => config('fawry.merchant_code'),
                "customerName"      => $customer_data['customer_name'],
                "customerMobile"    => $customer_data['customer_mobile'],
                "customerEmail"     => $customer_data['customer_email'],
                "customerProfileId" => md5($customer_data['customer_id']),
                "cardNumber"        => $card_data['card_number'],
                "cardExpiryYear"    => $card_data['card_expiry_year'],
                "cardExpiryMonth"   => $card_data['card_expiry_month'],
                "cvv"               => $card_data['cvv'],
                "merchantRefNum"    => $merchantRefNum,
                "amount"            => number_format((float) $amount, 2, '.', ''),
                "currencyCode"      => config('fawry.currency'),
                "language"          => $language,
                "chargeItems"       => $chargeItems,
                "paymentMethod"     => "CARD",
                "description"       => $description,
                "enable3DS"         => true,
                "authCaptureModePayment" => $authCaptureModePayment,
                "returnUrl"         => $calbackURL,
                "signature"         => hash("sha256", config('fawry.merchant_code') .
                    $merchantRefNum .
                    md5($customer_data['customer_id']) .
                    'CARD' .
                    number_format((float) $amount, 2, '.', '') .
                    $card_data['card_number'] .
                    $card_data['card_expiry_year'] .
                    $card_data['card_expiry_month'] .
                    $card_data['cvv'] .
                    $calbackURL .
                    config('fawry.merchant_key')
                )
            ],
        ]);
        $response = json_decode($response->getBody(), true);
        return json_encode( $response ); 
    }

    public static function chargeViaFawry($merchantRefNum='', $customer_data = [], $paymentExpiry='', $amount = 1, $chargeItems = [], $description = '' )
    {
        $url = config('fawry.mode') == 'live' ? config('fawry.live_urls')['charge_card_url'] : config('fawry.test_urls')['charge_card_url'];
        $client    = new \GuzzleHttp\Client();
        $response  = $client->post($url, [
          \GuzzleHttp\RequestOptions::JSON => [
                "merchantCode"      => config('fawry.merchant_code'),
                "merchantRefNum"    => $merchantRefNum,
                "paymentMethod"     => 'PAYATFAWRY',
                "paymentExpiry"     => $paymentExpiry,
                "customerProfileId" => md5($customer_data['customer_id']),
                "customerName"      => $customer_data['customer_name'],
                "customerMobile"    => $customer_data['customer_mobile'],
                "customerEmail"     => $customer_data['customer_email'],
                "amount"            => number_format((float) $amount, 2, '.', ''),
                "currencyCode"      => config('fawry.currency'),
                "chargeItems"       => $chargeItems,
                "description"       => $description,
                "signature"         => hash("sha256", config('fawry.merchant_code') .
                    $merchantRefNum .
                    md5($customer_data['customer_id']) .
                    'PAYATFAWRY' .
                    number_format((float) $amount, 2, '.', '') .
                    config('fawry.merchant_key')
                )
            ],
        ]);
        $response = json_decode($response->getBody(), true);
        return json_encode( $response ); 
    }

    public static function refund($referenceNumber='', $refundAmount=1, $reason = '')
    {
        $url = config('fawry.mode') == 'live' ? config('fawry.live_urls')['refund_card_url'] : config('fawry.test_urls')['refund_card_url'];
        $client    = new \GuzzleHttp\Client();
        $response  = $client->post($url, [
          \GuzzleHttp\RequestOptions::JSON => [
                "merchantCode"      => config('fawry.merchant_code'),
                "referenceNumber"   => $referenceNumber,
                "refundAmount"      => number_format((float) $refundAmount, 2, '.', ''),
                "reason"            => $reason,
                "signature"         => hash("sha256", config('fawry.merchant_code') .
                    $referenceNumber .
                    number_format((float) $refundAmount, 2, '.', '').
                    $reason .
                    config('fawry.merchant_key')
                )
            ],
        ]);
        $response = json_decode($response->getBody(), true);
        return json_encode( $response );
    }

    public static function cancelUnpaidPayment($orderReferenceNumber='', $lang = 'en-gb') 
    {
        $url = config('fawry.mode') == 'live' ? config('fawry.live_urls')['cancel_unpaid_url'] : config('fawry.test_urls')['cancel_unpaid_url'];
        $client    = new \GuzzleHttp\Client();
        $response  = $client->post($url, [
          \GuzzleHttp\RequestOptions::JSON => [
                "merchantAccount"  => config('fawry.merchant_code'),
                'orderRefNo'       => $orderReferenceNumber,
                'lang'             => $lang,
                "signature"        => hash("sha256", config('fawry.merchant_code') .
                    $orderReferenceNumber .
                    $lang .
                    config('fawry.merchant_key')
                )
            ],
        ]);
        $response = json_decode($response->getBody(), true);
        return json_encode( $response );
    }  

}
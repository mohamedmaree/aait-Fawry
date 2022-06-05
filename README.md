# Fawry
## Installation

You can install the package via [Composer](https://getcomposer.org).

```bash
composer require maree/fawry
```
Publish your fawry config file with

```bash
php artisan vendor:publish --provider="maree\Fawry\FawryServiceProvider" --tag="fawry"
```
then change your fawry config from config/fawry.php file
```php
    "mode"                => "test",//live
    "currency"            => "EGP" ,
```
## Usage

## create Card Token

```php
use Maree\Fawry\Fawry;
    $customer_data = ['customer_id' => '1', 'customer_mobile' => '010027*****', 'customer_email' => 'm7mdmaree26@gmail.com'];
    $card_data     = ['card_number' => '1111111111111','expiry_year' => 2023, 'expiry_month' => '05', 'cvv' => '123', 'is_default' => false];
Fawry::createCardToken($customer_data = [], $card_data = []);  

```

## list Customer Tokens

```php
use Maree\Fawry\Fawry;
Fawry::listCustomerTokens($customer_id);  

```

## delete Card Token

```php
use Maree\Fawry\Fawry;
Fawry::deleteCardToken($customer_id='', $customer_card_token='');  

```

## pay By Card Token

```php
use Maree\Fawry\Fawry;
Fawry::payByCardToken($merchantRefNum='', $customer_card_token='', $customer_data =[], $amount = 1 , $chargeItems = [], $description = ''); 

```

## pay By Card Token 3DS

```php
use Maree\Fawry\Fawry;
	$customer_data = ['customer_id' => '1','customer_name' => 'mohamed maree' 'customer_mobile' => '010027*****', 'customer_email' => 'm7mdmaree26@gmail.com'];
Fawry::payByCardToken3DS($merchantRefNum='', $customer_card_token='', $cvv='', $customer_data = [], $amount = 1 , $callbackURL='', $chargeItems = [], $authCaptureModePayment = false, $language = 'en-gb', $description = '');

```

## pay By Card

```php
use Maree\Fawry\Fawry;
    $customer_data = ['customer_id' => '1','customer_name' => 'mohamed maree' 'customer_mobile' => '010027*****', 'customer_email' => 'm7mdmaree26@gmail.com'];
    $card_data     = ['card_number' => '11111**********','card_expiry_year' => '23', 'card_expiry_month' => '05','cvv' =>'123'];
Fawry::payByCard($merchantRefNum='', $card_data = [], $customer_data = [], $amount= 1, $chargeItems = [], $language = 'en-gb' , $description = '');

```

## pay By Card 3DS

```php
use Maree\Fawry\Fawry;
    $customer_data = ['customer_id' => '1','customer_name' => 'mohamed maree' 'customer_mobile' => '010027*****', 'customer_email' => 'm7mdmaree26@gmail.com'];
    $card_data     = ['card_number' => '11111**********','card_expiry_year' => '23', 'card_expiry_month' => '05','cvv' =>'123'];
Fawry::payByCard3DS($merchantRefNum='', $card_data = [], $customer_data = [], $amount =1 , $calbackURL='', $chargeItems = [], $authCaptureModePayment = false, $language = 'en-gb' , $description = '');

```

## charge Via Fawry

```php
use Maree\Fawry\Fawry;
    $customer_data = ['customer_id' => '1','customer_name' => 'mohamed maree' 'customer_mobile' => '010027*****', 'customer_email' => 'm7mdmaree26@gmail.com'];
    $card_data     = ['card_number' => '11111**********','card_expiry_year' => '23', 'card_expiry_month' => '05','cvv' =>'123'];
Fawry::chargeViaFawry($merchantRefNum='', $customer_data = [], $paymentExpiry='', $amount = 1, $chargeItems = [], $description = '' );

```

## refund

```php
use Maree\Fawry\Fawry;
Fawry::refund($referenceNumber='', $refundAmount=1, $reason = '');

```

## cancel Unpaid Payment

```php
use Maree\Fawry\Fawry;
Fawry::cancelUnpaidPayment($orderReferenceNumber='', $lang = 'en-gb') ;

```

## Test Cards.
- https://developer.fawrystaging.com/docs/testing/testing

## documentaion
- https://developer.fawrystaging.com/docs/get-started

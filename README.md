[![Latest Stable Version](https://poser.pugx.org/hellovoid/orangepay-php/v/stable)](https://packagist.org/packages/hellovoid/orangepay-php)
[![Total Downloads](https://poser.pugx.org/hellovoid/orangepay-php/downloads)](https://packagist.org/packages/hellovoid/orangepay-php)
[![License](https://poser.pugx.org/hellovoid/orangepay-php/license)](https://packagist.org/packages/hellovoid/orangepay-php)
# Orangepay PHP API Client Library

This is the client library for the [Orangepay API][1].

## Installation

Install the library using Composer. Please read the [Composer Documentation](https://getcomposer.org/doc/01-basic-usage.md) if you are unfamiliar with Composer or dependency managers in general.

```
composer require hellovoid/orangepay-php
```
## Authentication

Use an API key to access your Orangepay account.

```php
use Hellovoid\Orangepay\Client;
use Hellovoid\Orangepay\Configuration;


$client = \Hellovoid\Orangepay\Client::create(
    \Hellovoid\Orangepay\Configuration::apiKey(
        $apiKey,
        $apiUrl
    )
);
```
## Response

Every successful method request returns decoded from json array.

## Exceptions
It is recommended to handle exceptions in the event of a problem.
Example:
```php
use Hellovoid\Orangepay\Client;
use Hellovoid\Orangepay\Configuration;
use Hellovoid\Orangepay\Exception\UnauthorizedException;
use Hellovoid\Orangepay\Exception\ValidationException;
use Hellovoid\Orangepay\Exception\NotFoundException;
use Hellovoid\Orangepay\Exception\RateLimitException;
use Hellovoid\Orangepay\Exception\InternalServerException;
use Hellovoid\Orangepay\Exception\HttpException;


$client = \Hellovoid\Orangepay\Client::create(
    \Hellovoid\Orangepay\Configuration::apiKey(
        $apiKey,
        $apiUrl
    )
);
try {
    $charge = $client->initializeCharge([
        'amount'       => 10.00,
        'currency'     => 'USD',
        'pay_method'   => 'card',
        'description'  => 'Test description',
        'reference_id' => 'my_unique_reference_id',
        'email'        => 'client@domain.ltd',
    ]);
} catch (UnauthorizedException $exception) {
  
} catch (ValidationException $exception) {

} catch (NotFoundException $exception) {
    
} catch (RateLimitException $exception) {
     
} catch (InternalServerException $exception) {
   
} catch (HttpException $exception) {

}

```
## Retrieve wallet balances [#ref](https://docs.gdax.com/#balances)
```php
$client->getBalance();
```
## Charges [#ref](https://orange-pay.com/api#charges)
### List charges
Retrieve pagination of charges with maximum 200 items per page. 

| Parameter  | Description                                                                                 |
|------------|---------------------------------------------------------------------------------------------|
| pay_method | Request charges of provided type, for example: card, bitcoin, etc. (Optional, default null) |
| start_date | Request charges after provided date in Y-m-d format. (Required)                             |
| end_date   | Request charges after provided date in Y-m-d format (Required)                              |
| page       | Pagination cursor                                                                           |
```php
$client->getCharges([
    'pay_method' => 'card',
    'start_date' => '2018-01-01',
    'end_date'   => '2018-01-02',
]);
```
### Charge details [#ref](https://orange-pay.com/api#retrieve-charge-details)
Retrieve charge details
```php
$client->getCharge($chargeId);
```
### Initialization [#ref](https://orange-pay.com/api#initialization)
Create invoice for payment.
```php
$client->initializeCharge([
   'amount'             => 10.00,
   'currency'           => 'USD',
   'pay_method'         => 'card',
   'description'        => 'Test description',
   'reference_id'       => 'my-unique-reference-id',
   'email'              => 'client@domain.ltd',
   'return_success_url' => 'https://my-site.ltd/payment-gateway-success',
   'return_error_url'   => 'https://my-site.ltd/payment-gateway-error',
   'callback_url'       => 'https://my-site.ltd/payment-gateway-callback',
]);
```
## Refunds [#ref](https://www.orange-pay.com/api#refunds)
### Create refund request
Returns the specified amount to the payer in the payment currency.
```php
$client->refund([
    'charge_id' => '1499ae90-f860-11e6-a8b6-e74ae337c2e8',
    'amount'    => 10.00
]);
```
## Transfers
### List transfers
Retrieve pagination of transfers with maximum 200 items per page. 

| Parameter  | Description                                                                                   |
|------------|-----------------------------------------------------------------------------------------------|
| pay_method | Request transfers of provided type, for example: card, bitcoin, etc. (Optional, default null) |
| start_date | Request transfers after provided date in Y-m-d format. (Required)                             |
| end_date   | Request transfers after provided date in Y-m-d format (Required)                              |
| page       | Pagination cursor                                                                             |
```php
$client->getTransfers([
    'pay_method' => 'card',
    'start_date' => '2018-01-01',
    'end_date'   => '2018-01-02',
]);
```
### Transfer details [#ref](https://orange-pay.com/api#retrieve-transfer-details)
Retrieve Transfer details
```php
$client->getTransfer($transferId);
```
### Transfer to card [#ref](https://www.orange-pay.com/api#transfer-to-card)
```php
$client->transferToCard([
    'reference_id'      => 'my-unique-reference-id', // can be used as [#idempotency key](https://en.wikipedia.org/wiki/Idempotence)
    'amount'            => 10.00,
    'currency'          => 'USD',
    'description'       => 'Test description',
    'name'              => 'John Doe',
    'card_number'       => '4111111111111111',
    'card_expiry_month' => '02',
    'card_expiry_year'  => '19',
    'address_country'   => 'US',
    'address_city'      => 'New York',
    'address_line1'     => '123 East 169th Street Apt. 2A Bronx, NY 10456',
    'callback_url'      => 'https://my-site.ltd/payment-gateway-callback'
]);
```
There is an opportunity to make transfer to the payer, who made a payment earlier by using charge_id:
```php
$client->transferToCard([
    'charge_id'         => '1173157c-db4d-11e7-9296-cec278b6b50a',
    'reference_id'      => 'my-unique-reference-id',
    'amount'            => 10.00,
    'currency'          => 'USD',
    'description'       => 'Test description',
    'address_country'   => 'US',
    'address_city'      => 'New York',
    'address_line1'     => '123 East 169th Street Apt. 2A Bronx, NY 10456',
]);
```

## Transfer to bitcoin address [#ref](https://www.orange-pay.com/api#transfer-to-bitcoin)
Amount must be in satoshi (1 BTC = 100000000 satoshis). 
```php
$client->transferToBitcoinAddress([
    'reference_id' => 'my-unique-reference-id',
    'amount'       => 10000000, // 0.1 BTC
    'currency'     => 'BTC',
    'description'  => 'Test description',
    'address'      => '3**********FR',
]);
```

## Rates [#ref](https://www.orange-pay.com/api#rates)
### Retrieve rates
```php
$client->rates('BTC-EUR');
```
[1]: https://orange-pay.com/api
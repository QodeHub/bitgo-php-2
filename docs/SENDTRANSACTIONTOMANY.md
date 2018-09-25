# Wallet

## Code Snipet
### Send Transaction To Many
```php

	$recipients = [
		['address' => 'some-btc-address', 'amount' => 0.00101],
		['address' => 'another-btc-address', 'amount' => 0.2183231]
		['address' => 'yet-another-btc-address', 'amount' => 0.33232]
	];

    $response = 
        Bitgo::btc($config)
    	    ->wallet($walletId)
    	    ->sendTransactionToMany()
    	    ->setRecipients($recipients)
    	    ->passphrase($passphrase)
    	    ->run();


```
```
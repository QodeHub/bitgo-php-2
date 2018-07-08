<?php

/**
 * @package     Qodehub\Bitgo
 * @link        https://github.com/qodehub/bitgo-php
 *
 * @author      Ariama O. Victor (ovac4u) <victorariama@qodehub.com>
 * @link        http://www.ovac4u.com
 *
 * @license     https://github.com/qodehub/bitgo-php/blob/master/LICENSE
 * @copyright   (c) 2018, QodeHub, Ltd
 */

namespace Qodehub\Bitgo\Wallet;

use Qodehub\Bitgo\Api\Api;
use Qodehub\Bitgo\Coin;
use Qodehub\Bitgo\Utility\CanCleanParameters;
use Qodehub\Bitgo\Utility\MassAssignable;
use Qodehub\Bitgo\Wallet;

/**
 * SignTransactions Class
 *
 * This class  presigns a transaction that can be signed and sent to the bitgo server.
 *
 * This class will require that a walletId is present. Examples are attaches
 *
 * @example Bitgo::btc($config)->wallet($walletId)->transactions()->sign()->amount()->address()->get();
 * @example Bitgo::btc($config)->wallet($walletId)->transactions($transactionId)->get();
 */
class SignTransaction extends SendCoins
{
    use WalletTrait;
    use MassAssignable;
    use CanCleanParameters;
    use Coin;

    /**
     * {@inheritdoc}
     */
    protected $parametersRequired = [
        'prv',
        'key',
        'walletId',
        'keychain',
        'txPrebuild',
        'walletPassphrase',
    ];

    /**
     * {@inheritdoc}
     */
    protected $parametersOptional = [
        'coldDerivationSeed',
    ];

    /**
     * {@inheritdoc}
     */
    protected $parametersSwapable = [
        'prv' => [
            ['walletPassphrase', 'keychain'],
            ['walletPassphrase', 'key'],
        ],
        'walletPassphrase' => [
            ['prv'],
        ],
        'key' => [
            ['keychain'],
            ['prv'],
        ],
        'keychain' => [
            ['prv'],
            ['key'],
        ],
    ];

    /**
     * The transaction description object, output from 'Build Transaction’.
     *
     * @var Object
     */
    protected $txPrebuild;

    /**
     * The user keychain with an encryptedPrv property
     *
     * @var Object
     */
    protected $keychain;

    /**
     * alias for 'keychain’
     * @var Object
     */
    protected $key;

    /**
     * The seed used to derive the signing key
     * @var string
     */
    protected $coldDerivationSeed;

    /**
     * Construct for creating a new instance of this class
     *
     * @param array|string $data An array with assignable Parameters or the
     *                           transactionID
     */
    public function __construct($data = null)
    {
        if (is_array($data)) {
            $this->massAssign($data);
        }

        return $this;
    }

    /**
     * A fluent method for setting the keychain
     *
     * @param  Object $key A valid encrypted keychain object
     * @return self
     */
    public function key($key)
    {
        return $this->keychain($key);
    }

    /**
     * A fluent method for setting the keychain
     *
     * @param  Object $key A valid encrypted keychain object
     * @return self
     */
    public function keychain($key)
    {
        return $this->setKey($key);
    }

    /**
     * A fluent method for setting the prebuilt transaction
     *
     * @param  object $txPrebuild A valid txPrebuild object
     * @return self
     */
    public function txPrebuild(object $txPrebuild)
    {
        return $this->setTxPrebuild($txPrebuild);
    }

    /**
     * @return Object
     */
    public function getTxPrebuild()
    {
        return $this->txPrebuild;
    }

    /**
     * @param Object $txPrebuild
     *
     * @return self
     */
    public function setTxPrebuild($txPrebuild)
    {
        $this->txPrebuild = $txPrebuild;

        return $this;
    }

    /**
     * @return Object
     */
    public function getKeychain()
    {
        return $this->keychain;
    }

    /**
     * @param Object $keychain
     *
     * @return self
     */
    public function setKeychain($keychain)
    {
        $this->keychain = $keychain;

        return $this;
    }

    /**
     * @return Object
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param Object $key
     *
     * @return self
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getColdDerivationSeed()
    {
        return $this->coldDerivationSeed;
    }

    /**
     * @param string $coldDerivationSeed
     *
     * @return self
     */
    public function setColdDerivationSeed($coldDerivationSeed)
    {
        $this->coldDerivationSeed = $coldDerivationSeed;

        return $this;
    }

    /**
     * The method places the call to the Bitgo API
     *
     * @return Object
     */
    public function run()
    {
        $this->propertiesPassRequired();

        return $this->_post('/wallet/{walletId}/signtx', $this->propertiesToArray());
    }
}

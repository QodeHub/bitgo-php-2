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

namespace Qodehub\Bitgo\Tests\Usability\Wallet;

use PHPUnit\Framework\TestCase;
use Qodehub\Bitgo\Bitgo;
use Qodehub\Bitgo\Config;
use Qodehub\Bitgo\Wallet;

class SignTransactionTest extends TestCase
{
    /**
     * The bearer token that will be used by this API
     * @var string
     */
    protected $token = 'existing-token';

    /**
     * This will determine if HTTP(S) will be used
     * @var boolean
     */
    protected $secure = true;

    /**
     * This is the host on which the Bitgo API is running.
     * @var string
     */
    protected $host = 'some-host.com';

    /**
     * The configuration instance.
     * @var Config
     */
    protected $config;

    /**
     * This is the ID of the wallet used in this test
     * @var string
     */
    protected $walletId = 'existing-wallet-id';

    /**
     * This is the coin type used for this test. Can be changed for other coin tests.
     * @var string
     */
    protected $coin = 'tbtc';

    /**
     * This is an address or the ID of the address used in this test
     * @var string
     */
    protected $address = 'existing-address';

    /**
     * Passphrase to decrypt the wallet’s
     * private key used in this test.
     *
     * @var string
     */
    protected $passphrase = 'SecureWalletPassword$%#';

    /**
     * Values of other optional parameters
     * used in this test.
     */
    protected $prv = 'some-private-key';
    protected $keychain;
    protected $txPrebuild;
    protected $coldDerivationSeed = 'some-cold-derivation-key';

    /**
     * Setup the test environment viriables
     * @return [type] [description]
     */
    public function setup()
    {
        $this->config = new Config($this->token, $this->secure, $this->host);

        $this->keychain = (object) ['hello' => 'keychain'];
        $this->txPrebuild = (object) ['hello' => 'txPrebuild'];
    }

    /** @test */
    public function it_can_signTransaction_coins_expressively()
    {

        /**
         * This expression uses the signTransaction method
         * on the wallet instance
         */
        $instance1 =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)
            ->transactions()->sign()

            ->txPrebuild($this->txPrebuild)
            ->setkeychain($this->keychain)
            ->passphrase($this->passphrase)

        /**
         * Even more optional parameters.
         * ==============================
         *
         * I percieve that this will be rarely used
         * so I have left them as conventional
         * accessors using set and get
         */
            ->setPrv($this->prv)
            ->setKey($this->keychain)
            ->setColdDerivationSeed($this->coldDerivationSeed)

        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkSendCoinsInstanceValues($instance1);

        /**
         * This expression uses the sign method
         * on the transactions class
         */
        $instance2 =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)
            ->transactions()->sign()

            ->txPrebuild($this->txPrebuild)
            ->setkeychain($this->keychain)
            ->passphrase($this->passphrase)

        /**
         * Even more optional parameters.
         * ==============================
         *
         * I percieve that this will be rarely used
         * so I have left them as conventional
         * accessors using set and get
         */
            ->setPrv($this->prv)
            ->setKey($this->keychain)
            ->setColdDerivationSeed($this->coldDerivationSeed)

        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkSendCoinsInstanceValues($instance2);
    }

    /** @test */
    public function it_can_signTransaction_coins_using_massassignment()
    {
        /**
         * This expression uses the create
         * method from the wallet
         * instance.
         */
        $instance1 =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)->signTransaction([

            'keychain' => $this->keychain,
            'txPrebuild' => $this->txPrebuild,
            'walletPassphrase' => $this->passphrase,

            /**
             * Even more optional parameters.
             * ==============================
             *
             * I percieve that this will be rarely used
             * so I have left them as conventional
             * accessors using set and get
             */
            'prv' => $this->prv,
            'key' => $this->keychain,
            'coldDerivationSeed' => $this->coldDerivationSeed,

            ])
        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkSendCoinsInstanceValues($instance1);
    }

    protected function checkSendCoinsInstanceValues($instance)
    {

        $this->assertSame(
            $instance->getCoinType(),
            $this->coin,
            'Must have a coin type'
        );

        $this->assertEquals(
            $this->config,
            $instance->getConfig(),
            'It should match the config that was passed into the static currency.'
        );

        $this->assertEquals(
            $this->walletId,
            $instance->getWalletId(),
            'The walletId should match ' . $this->walletId . ' for this test'
        );

        $this->assertEquals(
            $this->prv,
            $instance->getPrv(),
            'The prv should match ' . $this->prv . ' for this test'
        );

        $this->assertEquals(
            $this->txPrebuild,
            $instance->getTxPrebuild(),
            'The txPrebuild should match ' . json_encode($this->txPrebuild) . ' for this test'
        );

        $this->assertEquals(
            $this->keychain,
            $instance->getKeychain(),
            'The keychain should match ' . json_encode($this->keychain) . ' for this test'
        );

        $this->assertEquals(
            $this->keychain,
            $instance->getKey(),
            'The key should match ' . json_encode($this->keychain) . ' for this test'
        );

        $this->assertEquals(
            $this->passphrase,
            $instance->getWalletPassphrase(),
            'The passphrase should match ' . $this->passphrase . ' for this test'
        );

        $this->assertEquals(
            $this->coldDerivationSeed,
            $instance->getColdDerivationSeed(),
            'The walletId should match ' . $this->coldDerivationSeed . ' for this test'
        );
    }
}

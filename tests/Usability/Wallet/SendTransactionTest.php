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

class SendTransactionTest extends TestCase
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
    protected $halfSigned;
    protected $comment = 'some-long-comment';
    protected $txHex = 'some-cold-derivation-key';
    protected $otp = 428;

    /**
     * Setup the test environment viriables
     * @return [type] [description]
     */
    public function setup()
    {
        $this->config = new Config($this->token, $this->secure, $this->host);

        $this->halfSigned = (object) ['hello' => 'halfSigned'];
    }

    /** @test */
    public function it_can_sendTransaction_coins_expressively()
    {

        /**
         * This expression uses the sendTransaction method
         * on the wallet instance
         */
        $instance1 =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)
            ->transactions()->send()

            ->txHex($this->txHex)
            ->setHalfSigned($this->halfSigned)

        /**
         * Even more optional parameters.
         * ==============================
         *
         * I percieve that this will be rarely used
         * so I have left them as conventional
         * accessors using set and get
         */
            ->comment($this->comment)
            ->otp($this->otp)

        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkSendCoinsInstanceValues($instance1);

        /**
         * This expression uses the sendTransaction method
         * on the wallet instance
         */
        $instance2 =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)
            ->sendTransaction()

            ->txHex($this->txHex)
            ->setHalfSigned($this->halfSigned)

        /**
         * Even more optional parameters.
         * ==============================
         *
         * I percieve that this will be rarely used
         * so I have left them as conventional
         * accessors using set and get
         */
            ->comment($this->comment)
            ->otp($this->otp)

        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkSendCoinsInstanceValues($instance2);
    }

    /** @test */
    public function it_can_sendTransaction_coins_using_massassendment()
    {
        /**
         * This expression uses the create
         * method from the wallet
         * instance.
         */
        $instance1 =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)->sendTransaction([

            'txHex' => $this->txHex,
            'halfSigned' => $this->halfSigned,
            'walletPassphrase' => $this->passphrase,

            /**
             * Even more optional parameters.
             * ==============================
             *
             * I percieve that this will be rarely used
             * so I have left them as conventional
             * accessors using set and get
             */
            'comment' => $this->comment,
            'otp' => $this->otp,

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
            $this->comment,
            $instance->getComment(),
            'The comment should match ' . $this->comment . ' for this test'
        );

        $this->assertEquals(
            $this->txHex,
            $instance->getTxHex(),
            'The txHex should match ' . json_encode($this->txHex) . ' for this test'
        );

        $this->assertEquals(
            $this->halfSigned,
            $instance->getHalfSigned(),
            'The halfSigned should match ' . json_encode($this->halfSigned) . ' for this test'
        );

        $this->assertEquals(
            $this->otp,
            $instance->getOtp(),
            'The otp should match ' . json_encode($this->otp) . ' for this test'
        );
    }
}

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

class BuildTransactionTest extends TestCase
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
     * This is the amount used in this test.
     *
     * @var string
     */
    protected $amount = 1.742;
    /**
     * Values of other optional parameters
     * used in this test.
     */
    protected $numBlocks = 100000;
    protected $feeRate = 10;
    protected $minConfirms = 3;
    protected $enforceMinConfirmsForChange = true;
    protected $targetWalletUnspents = true;
    protected $noSplitChange = true;
    protected $minValue = 200;
    protected $maxValue = 3000;
    protected $gasPrice = 10;
    protected $gasLimit = 80;
    protected $sequenceId = 5;
    protected $segwit = 1000;
    protected $lastLedgerSequence = 1;
    protected $ledgerSequenceDelta = 2;
    protected $recipients;
    protected $unspents = ['some' => 'unspents'];

    /**
     * Setup the test environment viriables
     * @return [type] [description]
     */
    public function setup()
    {
        $this->config = new Config($this->token, $this->secure, $this->host);

        $this->recipients = [
            [
                'amount' => $this->amount,
                'address' => $this->address,
            ],
        ];
    }

    /** @test */
    public function it_can_buildTransaction_coins_expressively()
    {
        /**
         * This expression uses the buildTransaction
         * method from the wallet instance
         */
        $instance1 =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)
            ->buildTransaction()
            ->amount($this->amount)
            ->address($this->address)

        /**
         * Even more optional parameters.
         * ==============================
         *
         * I percieve that this will be rarely used
         * so I have left them as conventional
         * accessors using set and get
         */

            ->setFeeRate($this->feeRate)
            ->setMinValue($this->minValue)
            ->setUnspents($this->unspents)
            ->setMaxValue($this->maxValue)
            ->setGasPrice($this->gasPrice)
            ->setNumBlocks($this->numBlocks)
            ->setChangeAddress($this->address)
            ->setMinConfirms($this->minConfirms)
            ->setNoSplitChange($this->noSplitChange)
            ->setLastLedgerSequence($this->lastLedgerSequence)
            ->setLedgerSequenceDelta($this->ledgerSequenceDelta)
            ->setTargetWalletUnspents($this->targetWalletUnspents)
            ->setEnforceMinConfirmsForChange($this->enforceMinConfirmsForChange)
        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkSendCoinsInstanceValues($instance1);

        /**
         * This expression uses the buildTransaction
         * method from the wallet instance
         */
        $instance2 =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)
            ->buildTransaction($this->amount)
            ->amount($this->amount)
            ->address($this->address)
        /**
         * Even more optional parameters.
         * ==============================
         *
         * I percieve that this will be rarely used
         * so I have left them as conventional
         * accessors using set and get
         */

            ->setFeeRate($this->feeRate)
            ->setMinValue($this->minValue)
            ->setMaxValue($this->maxValue)
            ->setUnspents($this->unspents)
            ->setGasPrice($this->gasPrice)
            ->setNumBlocks($this->numBlocks)
            ->setChangeAddress($this->address)
            ->setMinConfirms($this->minConfirms)
            ->setNoSplitChange($this->noSplitChange)
            ->setLastLedgerSequence($this->lastLedgerSequence)
            ->setLedgerSequenceDelta($this->ledgerSequenceDelta)
            ->setTargetWalletUnspents($this->targetWalletUnspents)
            ->setEnforceMinConfirmsForChange($this->enforceMinConfirmsForChange)
        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkSendCoinsInstanceValues($instance2);

        /**
         * This expression uses the buildTransaction and places the
         * amount on the amount helper method.
         */
        $instance3 =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)
            ->buildTransaction()
            ->amount($this->amount)
            ->receiver($this->address)
        /**
         * Even more optional parameters.
         * ==============================
         *
         * I percieve that this will be rarely used
         * so I have left them as conventional
         * accessors using set and get
         */

            ->setFeeRate($this->feeRate)
            ->setMinValue($this->minValue)
            ->setMaxValue($this->maxValue)
            ->setUnspents($this->unspents)
            ->setGasPrice($this->gasPrice)
            ->setNumBlocks($this->numBlocks)
            ->setChangeAddress($this->address)
            ->setMinConfirms($this->minConfirms)
            ->setNoSplitChange($this->noSplitChange)
            ->setLastLedgerSequence($this->lastLedgerSequence)
            ->setLedgerSequenceDelta($this->ledgerSequenceDelta)
            ->setTargetWalletUnspents($this->targetWalletUnspents)
            ->setEnforceMinConfirmsForChange($this->enforceMinConfirmsForChange)
        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkSendCoinsInstanceValues($instance3);

        /**
         * This expression uses the buildTransaction and places the
         * amount on and uses the address method
         * to set the receiving address.
         */
        $instance4 =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)
            ->buildTransaction()
            ->amount($this->amount)
            ->address($this->address)
        /**
         * Even more optional parameters.
         * ==============================
         *
         * I percieve that this will be rarely used
         * so I have left them as conventional
         * accessors using set and get
         */

            ->setFeeRate($this->feeRate)
            ->setMinValue($this->minValue)
            ->setMaxValue($this->maxValue)
            ->setUnspents($this->unspents)
            ->setGasPrice($this->gasPrice)
            ->setNumBlocks($this->numBlocks)
            ->setChangeAddress($this->address)
            ->setMinConfirms($this->minConfirms)
            ->setNoSplitChange($this->noSplitChange)
            ->setLastLedgerSequence($this->lastLedgerSequence)
            ->setLedgerSequenceDelta($this->ledgerSequenceDelta)
            ->setTargetWalletUnspents($this->targetWalletUnspents)
            ->setEnforceMinConfirmsForChange($this->enforceMinConfirmsForChange)
        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkSendCoinsInstanceValues($instance4);

        /**
         * This expression uses the buildTransaction and places the
         * amount on and uses the address method
         * to set the receiving address.
         */
        $instance5 =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)
            ->transactions()->build()
            ->amount($this->amount)
            ->address($this->address)
        /**
         * Even more optional parameters.
         * ==============================
         *
         * I percieve that this will be rarely used
         * so I have left them as conventional
         * accessors using set and get
         */

            ->setFeeRate($this->feeRate)
            ->setMinValue($this->minValue)
            ->setMaxValue($this->maxValue)
            ->setUnspents($this->unspents)
            ->setGasPrice($this->gasPrice)
            ->setNumBlocks($this->numBlocks)
            ->setChangeAddress($this->address)
            ->setMinConfirms($this->minConfirms)
            ->setNoSplitChange($this->noSplitChange)
            ->setLastLedgerSequence($this->lastLedgerSequence)
            ->setLedgerSequenceDelta($this->ledgerSequenceDelta)
            ->setTargetWalletUnspents($this->targetWalletUnspents)
            ->setEnforceMinConfirmsForChange($this->enforceMinConfirmsForChange)
        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkSendCoinsInstanceValues($instance5);
    }

    /** @test */
    public function it_can_buildTransaction_coins_using_massassignment()
    {
        /**
         * This expression uses the create
         * method from the wallet
         * instance.
         */
        $instance1 =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)->buildTransaction([

            'recipients' => $this->recipients,

            /**
             * Even more optional parameters.
             * ==============================
             *
             * I percieve that this will be rarely used
             * so I have left them as conventional
             * accessors using set and get
             */

            'feeRate' => $this->feeRate,
            'minValue' => $this->minValue,
            'maxValue' => $this->maxValue,
            'unspents' => $this->unspents,
            'gasPrice' => $this->gasPrice,
            'numBlocks' => $this->numBlocks,
            'changeAddress' => $this->address,
            'minConfirms' => $this->minConfirms,
            'noSplitChange' => $this->noSplitChange,
            'targetWalletUnspents' => $this->targetWalletUnspents,
            'lastLedgerSequence' => $this->lastLedgerSequence,
            'ledgerSequenceDelta' => $this->ledgerSequenceDelta,
            'enforceMinConfirmsForChange' => $this->enforceMinConfirmsForChange,

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
            $this->amount,
            $instance->getAmount(),
            'The amount should match ' . $this->amount . ' for this test'
        );

        $this->assertEquals(
            $this->walletId,
            $instance->getWalletId(),
            'The walletId should match ' . $this->walletId . ' for this test'
        );

        $this->assertEquals(
            $this->address,
            $instance->getAddress(),
            'The address should match ' . $this->address . ' for this test'
        );

        $this->assertEquals(
            $this->recipients,
            $instance->getRecipients(),
            'The recipients should be same as the first in setup.'
        );

        // Testing Optional Parameters.

        $this->assertEquals(
            $this->numBlocks,
            $instance->getNumBlocks(),
            'numBlocks is Optional but should match ' . $this->numBlocks . ' for this test'
        );

        $this->assertEquals(
            $this->feeRate,
            $instance->getFeeRate(),
            'feeRate is Optional but should match ' . $this->feeRate . ' for this test'
        );

        $this->assertEquals(
            $this->minValue,
            $instance->getMinValue(),
            'minValue is Optional but should match ' . $this->minValue . ' for this test'
        );

        $this->assertEquals(
            $this->maxValue,
            $instance->getMaxValue(),
            'maxValue is Optional but should match ' . $this->maxValue . ' for this test'
        );

        $this->assertEquals(
            $this->gasPrice,
            $instance->getGasPrice(),
            'gasPrice is Optional but should match ' . $this->gasPrice . ' for this test'
        );

        $this->assertEquals(
            $this->minConfirms,
            $instance->getMinConfirms(),
            'minConfirms is Optional but should match ' . $this->minConfirms . ' for this test'
        );

        $this->assertEquals(
            $this->noSplitChange,
            $instance->getNoSplitChange(),
            'noSplitChange is Optional but should match ' . $this->noSplitChange . ' for this test'
        );

        $this->assertEquals(
            $this->lastLedgerSequence,
            $instance->getlastLedgerSequence(),
            'lastLedgerSequence is Optional but should match ' . $this->lastLedgerSequence . ' for this test'
        );

        $this->assertEquals(
            $this->ledgerSequenceDelta,
            $instance->getLedgerSequenceDelta(),
            'ledgerSequenceDelta is Optional but should match ' . $this->ledgerSequenceDelta . ' for this test'
        );

        $this->assertEquals(
            $this->targetWalletUnspents,
            $instance->getTargetWalletUnspents(),
            'targetWalletUnspents is Optional but should match ' . $this->targetWalletUnspents . ' for this test'
        );

        $this->assertEquals(
            $this->unspents,
            $instance->getUnspents(),
            'unspents is Optional but should match the unspents array for this test'
        );

        $this->assertEquals(
            $this->enforceMinConfirmsForChange,
            $instance->getEnforceMinConfirmsForChange(),
            'enforceMinConfirmsForChange is Optional but should match ' . $this->enforceMinConfirmsForChange . ' for this test'
        );

        $this->assertEquals(
            $this->address,
            $instance->getChangeAddress(),
            'changeAddress is Optional but should match ' . $this->address . ' for this test'
        );

        $instance->addRecipient('another-address-added', '1000');

        $this->assertEquals(
            [
                'address' => 'another-address-added',
                'amount' => '1000',
            ],
            $instance->getRecipient(1),
            'Should return the lastest recipient information'
        );
    }
}

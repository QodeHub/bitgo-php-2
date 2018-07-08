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

class MaximiumSpendableTest extends TestCase
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
     * Max number of unspents to use (if less than 200)
     * @var int
     */
    protected $limit = 10;

    /**
     * Ignore unspents smaller than this amount of satoshis
     * @var int
     */
    protected $minValue = 5;

    /**
     * Ignore unspents larger than this amount of satoshis
     * @var int
     */
    protected $maxValue = 100;

    /**
     * Minimum block height of unspents to fetch
     * @var int
     */
    protected $minHeight = 200;

    /**
     * The desired fee rate for the transaction in satoshis/kB
     * @var int
     */
    protected $feeRate = 150;

    /**
     * The required number of confirmations for each non-change unspent
     * @var int
     */
    protected $minConfirms = 3;

    /**
     * Apply the required confirmations set in minConfirms for change outputs
     * @var boolean
     */
    protected $enforceMinConfirmsForChange = 10;

    /**
     * Setup the test environment viriables
     * @return [type] [description]
     */
    public function setup()
    {
        $this->config = new Config($this->token, $this->secure, $this->host);
    }

    /** @test */
    public function get_the_maximum_spendable_amount_expressively()
    {

        $instance =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)
            ->maximumSpendable()

        /**
         * Even more optional parameters.
         * ==============================
         *
         * I percieve that this will be rarely used
         * so I have left them as conventional
         * accessors using set and get
         */
            ->setLimit($this->limit)
            ->setFeeRate($this->feeRate)
            ->setMinValue($this->minValue)
            ->setMaxValue($this->maxValue)
            ->setMinHeight($this->minHeight)
            ->setMinConfirms($this->minConfirms)
            ->setEnforceMinConfirmsForChange($this->enforceMinConfirmsForChange)
        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkInstanceValues($instance);
    }

    /** @test */
    public function getting_the_maximum_spendable_using_massassignment()
    {
        $instance =

        Bitgo::{$this->coin}($this->config)
            ->wallet($this->walletId)
            ->maximumSpendable([
                'limit' => $this->limit,
                'feeRate' => $this->feeRate,
                'minValue' => $this->minValue,
                'maxValue' => $this->maxValue,
                'minHeight' => $this->minHeight,
                'minConfirms' => $this->minConfirms,
                'enforceMinConfirmsForChange' => $this->enforceMinConfirmsForChange,
            ])
        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkInstanceValues($instance);
    }

    protected function checkInstanceValues($instance)
    {

        $this->assertSame(
            $instance->getCoinType(),
            $this->coin,
            'Must have a coin type'
        );

        $this->assertSame(
            $instance->getWalletId(),
            $this->walletId,
            'The instance must have the valid wallet Id'
        );

        $this->assertEquals(
            $this->config,
            $instance->getConfig(),
            'It should match the config that was passed into the static currency.'
        );

        $this->assertEquals(
            $this->limit,
            $instance->getLimit(),
            'limit is Optional but should match ' . $this->limit . ' for this test'
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
            $this->minHeight,
            $instance->getMinHeight(),
            'minHeight is Optional but should match ' . $this->minHeight . ' for this test'
        );

        $this->assertEquals(
            $this->minConfirms,
            $instance->getMinConfirms(),
            'minConfirms is Optional but should match ' . $this->minConfirms . ' for this test'
        );

        $this->assertEquals(
            $this->enforceMinConfirmsForChange,
            $instance->getEnforceMinConfirmsForChange(),
            'enforceMinConfirmsForChange is Optional but should match ' . $this->enforceMinConfirmsForChange . ' for this test'
        );
    }
}

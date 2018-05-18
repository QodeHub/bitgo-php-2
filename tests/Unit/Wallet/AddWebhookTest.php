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

namespace Qodehub\Bitgo\Tests\Unit\WalletTest;

use PHPUnit\Framework\TestCase;
use Qodehub\Bitgo\Bitgo;
use Qodehub\Bitgo\Config;
use Qodehub\Bitgo\Exception\InvalidRequestException;
use Qodehub\Bitgo\Wallet;
use Qodehub\Bitgo\Wallet\AddWebhook;

class AddWebhookTest extends TestCase
{

    /**
     * This is the ID of the wallet used in this test
     * @var string
     */
    protected $walletId = 'existing-wallet-id';

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
     * This is the coin type used for this test. Can be changed for other coin tests.
     * @var string
     */
    protected $coin = 'tbtc';

    /**
     * This is the label we will use for testing the wallet.
     * @var string
     */
    protected $label = 'my new wallet';

    /**
     * This is the waller passphrase that will be used in this test.
     * @var string
     */
    protected $walletPassphrase = 'hello-world';

    /**
     * Values of other optional parameters
     * used in this test.
     */
    protected $url = 'http://someurl.com/some-path';
    protected $type = 'transfer';
    protected $tokenFlushThresholds = 1000;
    protected $numConfirmations = 10;

    /**
     * Setup the test environment viriables
     * @return [type] [description]
     */
    public function setup()
    {
        $this->config = new Config($this->token, $this->secure, $this->host);
    }

    /** @test */
    public function the_add_webhook_method_can_be_called_with_an_entry_static_coin_method()
    {
        /**
         * Set the currency name-space
         * @var AddWebhook
         */
        $instance = Bitgo::{$this->coin}()->wallet($this->walletId)->addWebhook();

        $this->assertInstanceOf(AddWebhook::class, $instance);

        return $instance;
    }

    /**
     * @test
     * @depends the_add_webhook_method_can_be_called_with_an_entry_static_coin_method
     */
    public function the_instance_has_a_chain_method_for_setting_the_type_method($AddWebhookInstance)
    {
        $AddWebhookInstance->type($this->type);

        $this->assertEquals($AddWebhookInstance->getType(), $this->type);

        $AddWebhookInstance->type('pendingaapproval');

        $this->assertEquals($AddWebhookInstance->getType(), 'pendingaapproval');

        return $AddWebhookInstance->type($this->type);
    }

    /**
     * @test
     * @depends the_add_webhook_method_can_be_called_with_an_entry_static_coin_method
     */
    public function the_instance_throws_exception_for_invalid_callback_type_in_request($AddWebhookInstance)
    {
        $this->expectException(InvalidRequestException::class);

        $AddWebhookInstance->type('invalid-data');
    }

    /**
     * @test
     * @depends the_add_webhook_method_can_be_called_with_an_entry_static_coin_method
     */
    public function the_instance_throws_exception_for_invalid_callback_url_in_request($AddWebhookInstance)
    {
        $this->expectException(InvalidRequestException::class);

        $AddWebhookInstance->url('invalid-url');
    }

    /**
     * @test
     * @depends the_add_webhook_method_can_be_called_with_an_entry_static_coin_method
     */
    public function the_instance_has_a_chain_method_for_setting_the_url_method($AddWebhookInstance)
    {
        $AddWebhookInstance->url($this->url);

        $this->assertEquals($AddWebhookInstance->getUrl(), $this->url);

        $AddWebhookInstance->url('http://anotherurl.com/another-path');

        $this->assertEquals($AddWebhookInstance->getUrl(), 'http://anotherurl.com/another-path');

        return $AddWebhookInstance->url($this->url);
    }

    /** @test */
    public function the_insance_can_be_accessed_statically_and_determine_the_coin_type_based_on_entry()
    {
        $instance = AddWebhook::{$this->coin}();

        $this->assertInstanceOf(AddWebhook::class, $instance);

        $this->assertEquals($instance->getCoinType(), $this->coin);
    }
}

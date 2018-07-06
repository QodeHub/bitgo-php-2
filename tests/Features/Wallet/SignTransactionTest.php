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

namespace Qodehub\Bitgo\Tests\Features\Wallet;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Qodehub\Bitgo\Bitgo;
use Qodehub\Bitgo\Config;
use Qodehub\Bitgo\Exception\MissingParameterException;
use Qodehub\Bitgo\Utility\BitgoHandler;
use Qodehub\Bitgo\Wallet;
use Qodehub\Bitgo\Wallet\SignTransaction;

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
     * This will be a receiving BTC address
     * @var string
     */
    protected $address = 'some-receiving-btc-address';

    /**
     * This will be the amount of coins we will use for this test.
     * @var integer
     */
    protected $amount = 100;

    /**
     * This is the waller passphrase that will be used in this test.
     * @var string
     */
    protected $walletPassphrase = 'hello-world';

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

        $this->txPrebuild = (object) ['hello' => 'txPrebuild'];

        $this->keychain = (object) ['hello' => 'keychain'];
    }

    /** @test */
    public function the_insance_can_be_accessed_statically_and_determine_the_coin_type_based_on_entry()
    {
        $instance = SignTransaction::{$this->coin}();

        $this->assertInstanceOf(SignTransaction::class, $instance);

        $this->assertEquals($instance->getCoinType(), $this->coin);
    }

    /** @test */
    public function it_should_throw_and_error_if_both_the_private_key_and_walletpasssphrase_with_keychain_are_absent()
    {
        /**
         * Mock the getClient method in the SignTransaction to intercept calls to the server
         */
        $mock = $this->getMockBuilder(SignTransaction::class)
            ->setMethods(['getClient'])
            ->getMock();

        $mock->method('getClient')->will($this->returnValue(null));

        $this->expectException(MissingParameterException::class);

        $mock
            ->injectConfig($this->config)
            ->coinType($this->coin)
            ->txPrebuild($this->txPrebuild)
            ->run();
    }

    /** @test */
    public function a_call_to_the_run_method_should_return_an_error_if_the_walletID_is_missing()
    {
        /**
         * Mock the getClient method in the SignTransaction to intercept calls to the server
         */
        $mock = $this->getMockBuilder(SignTransaction::class)
            ->setMethods(['getClient'])
            ->getMock();

        $mock->method('getClient')->will($this->returnValue(null));

        $this->expectException(MissingParameterException::class);

        $mock
            ->injectConfig($this->config)
            ->coinType($this->coin)
            ->setPrv($this->prv)
            ->run();
    }

    /** @test */
    public function a_call_to_the_run_method_should_return_an_error_if_the_coin_type_value_is_missing()
    {
        /**
         * Mock the getClient method in the SignTransaction to intercept calls to the server
         */
        $mock = $this->getMockBuilder(SignTransaction::class)
            ->setMethods(['getClient'])
            ->getMock();

        $mock->method('getClient')->will($this->returnValue(new Client()));

        $this->expectException(MissingParameterException::class);

        $mock
            ->injectConfig($this->config)
            ->wallet($this->walletId)
            ->address($this->address)
            ->run();
    }

    /** @test */
    public function it_should_throw_an_error_if_only_the_wallet_wallet_passphrase_is_given_without_the_keychain_or_prv_key()
    {
        /**
         * Mock the getClient method in the SignTransaction to intercept calls to the server
         */
        $mock = $this->getMockBuilder(SignTransaction::class)
            ->setMethods(['getClient'])
            ->getMock();

        $mock->method('getClient')->will($this->returnValue(new Client()));

        $this->expectException(MissingParameterException::class);

        $mock
            ->injectConfig($this->config)
            ->wallet($this->walletId)
            ->address($this->address)
            ->passphrase($this->walletPassphrase)
            ->run();
    }

    /** @test */
    public function it_should_throw_an_error_if_only_the_wallet_keychain__is_given_without_the_passphrase_or_prv_key()
    {
        /**
         * Mock the getClient method in the SignTransaction to intercept calls to the server
         */
        $mock = $this->getMockBuilder(SignTransaction::class)
            ->setMethods(['getClient'])
            ->getMock();

        $mock->method('getClient')->will($this->returnValue(new Client()));

        $this->expectException(MissingParameterException::class);

        $mock
            ->injectConfig($this->config)
            ->wallet($this->walletId)
            ->address($this->address)
            ->key($this->keychain)
            ->run();
    }

    /** @test */
    public function test_that_a_call_to_the_server_will_be_successful_if_all_is_right()
    {
        /**
         * Setup the Handler and middlewares interceptor to intercept the call to the server
         */
        $container = [];

        $history = Middleware::history($container);

        $httpMock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], json_encode(['X-Foo' => 'Bar'])),
        ]);

        $handlerStack = (new BitgoHandler($this->config, HandlerStack::create($httpMock)))->createHandler();

        $handlerStack->push($history);

        /**
         * Listen to the SignTransaction class method and use the interceptor
         *
         * Intercept all calls to the server from the createHandler method
         */
        $mock = $this->getMockBuilder(SignTransaction::class)
            ->setMethods(['createHandler'])
            ->getMock();

        $mock->expects($this->once())->method('createHandler')->will($this->returnValue($handlerStack));

        /**
         * Inject the configuration and use the
         */
        $mock
            ->injectConfig($this->config)

            //Setup the required parameters
            ->wallet($this->walletId)
            ->coinType($this->coin)
            ->txPrebuild($this->txPrebuild)
            ->setPrv($this->keychain)
            ->passphrase($this->walletPassphrase)
        ;

        /**
         * Run the call to the server
         */
        $result = $mock->run();

        /**
         * Run assertion that call reached the Mock Server
         */
        $this->assertEquals($result, json_decode(json_encode(['X-Foo' => 'Bar'])));

        /**
         * Grab the requests and test that the request parameters
         * are correct as expected.
         */
        $request = $container[0]['request'];

        $this->assertEquals($request->getMethod(), 'POST', 'it should be a post request.');
        $this->assertEquals($request->getUri()->getHost(), $this->host, 'Hostname should be' . $this->host);
        $this->assertEquals($request->getHeaderLine('User-Agent'), Bitgo::CLIENT . ' v' . Bitgo::VERSION);

        $this->assertEquals($request->getUri()->getScheme(), 'https', 'it should be a https scheme');

        $this->assertContains(
            "https://some-host.com/api/v2/" . $this->coin . "/wallet/" . $this->walletId . '/signtx',
            $request->getUri()->__toString()
        );
    }
}

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

class Keychains extends TestCase
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
     * This is the coin type used for this test. Can be changed for other coin tests.
     * @var string
     */
    protected $coin = 'tbtc';

    /**
     * Values of other optional parameters
     * used in this test.
     */
    protected $id = 'exisiting-key-id';

    /**
     * Setup the test environment viriables
     * @return [type] [description]
     */
    public function setup()
    {
        $this->config = new Config($this->token, $this->secure, $this->host);
    }

    /** @test */
    public function it_can_get_a_list_of_keychains_expressively()
    {
        /**
         * This expression uses the create method
         * from the walet addresses instance.
         */
        $instance1 =

        Bitgo::{$this->coin}($this->config)
            ->utilities()
            ->keychains()
        ;

        $this->checkInstanceValues($instance1);

        /**
         * This expression uses the create method
         * from the walet addresses instance.
         */
        $instance2 =

        Bitgo::{$this->coin}()
            ->utilities()
            ->keychains()
            ->injectConfig($this->config)
        ;

        $this->checkInstanceValues($instance2);

        /**
         * This expression uses the create method
         * from the walet addresses instance.
         */
        $instance3 =

        Bitgo::{$this->coin}()
            ->utilities($this->config)
            ->keychains()
        ;

        $this->checkInstanceValues($instance3);
    }

    /** @test */
    public function it_can_get_a_single_key_expressively()
    {
        /**
         * This expression uses the create method
         * from the walet addresses instance.
         */
        $instance1 =

        Bitgo::{$this->coin}($this->config)
            ->utilities()
            ->keychains()
            ->id($this->id)
        ;

        $this->checkSingleKeyInstanceValues($instance1);

        /**
         * This expression uses the create method
         * from the walet addresses instance.
         */
        $instance2 =

        Bitgo::{$this->coin}()
            ->utilities()
            ->keychains()
            ->id($this->id)
            ->injectConfig($this->config)
        ;

        $this->checkSingleKeyInstanceValues($instance2);

        /**
         * This expression uses the create method
         * from the walet addresses instance.
         */
        $instance3 =

        Bitgo::{$this->coin}()
            ->utilities($this->config)
            ->keychains()
            ->id($this->id)
        ;

        $this->checkSingleKeyInstanceValues($instance3);

        /**
         * This expression uses the create method
         * from the walet addresses instance.
         */
        $instance4 =

        Bitgo::{$this->coin}()
            ->utilities($this->config)
            ->keychains($this->id)
        ;

        $this->checkSingleKeyInstanceValues($instance4);
    }

    /** @test */
    public function mass_assignable()
    {
        /**
         * This expression uses the create
         * method from the wallet
         * instance.
         */
        $instance1 =

        Bitgo::{$this->coin}()
            ->utilities($this->config)
            ->keychains([
                // === Optional parameters
                'id' => $this->id,
            ])
        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkSingleKeyInstanceValues($instance1);

        /**
         * This expression uses the create
         * method from the wallet
         * instance.
         */
        $instance2 =

        Bitgo::{$this->coin}()
            ->utilities()
            ->keychains([
                // === Optional parameters
                'id' => $this->id,
            ])
            ->injectConfig($this->config)
        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkSingleKeyInstanceValues($instance2);

        /**
         * This expression uses the create
         * method from the wallet
         * instance.
         */
        $instance3 =

        Bitgo::{$this->coin}($this->config)
            ->utilities()
            ->keychains([
                // === Optional parameters
                'id' => $this->id,
            ])
            ->injectConfig($this->config)
        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkSingleKeyInstanceValues($instance3);
    }

    protected function checkInstanceValues($instance)
    {

        $this->assertEquals(
            $this->config,
            $instance->getConfig(),
            'It should match the config that was passed into the static currency.'
        );

        $this->assertSame(
            $instance->getCoinType(),
            $this->coin,
            'Must have a coin type'
        );
    }

    protected function checkSingleKeyInstanceValues($instance)
    {

        $this->assertEquals(
            $this->config,
            $instance->getConfig(),
            'It should match the config that was passed into the static currency.'
        );

        $this->assertSame(
            $instance->getCoinType(),
            $this->coin,
            'Must have a coin type'
        );

        $this->assertEquals(
            $this->id,
            $instance->getId(),
            'The key id should match ' . $this->id . ' for this test'
        );
    }
}

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

class CreateKeychain extends TestCase
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
     * Setup the test environment viriables
     * @return [type] [description]
     */
    public function setup()
    {
        $this->config = new Config($this->token, $this->secure, $this->host);
    }

    /** @test */
    public function it_can_get_create_a_keychain_expressively()
    {
        /**
         * This expression uses the create method
         * from the walet addresses instance.
         */
        $instance1 =

        Bitgo::{$this->coin}($this->config)
            ->utilities()
            ->createKeychain()
        ;

        $this->checkInstanceValues($instance1);

        /**
         * This expression uses the create method
         * from the walet addresses instance.
         */
        $instance2 =

        Bitgo::{$this->coin}()
            ->utilities()
            ->createKeychain()
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
            ->createKeychain()
        ;

        $this->checkInstanceValues($instance3);

        //Comment Out for end to end testing.
        // dd(Bitgo::{$this->coin}()
        //         ->utilities($this->config)
        //         ->createKeychain()
        //         ->injectConfig(
        //             new Config(null, false, 'localhost', 3080)
        //         )
        //         ->run()
        // );
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
}

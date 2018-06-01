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

class DecryptTest extends TestCase
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
     * Values of other optional parameters
     * used in this test.
     */
    protected $input = 'A-random-data to decrypt';
    protected $password = 'somepassword';

    /**
     * Setup the test environment viriables
     * @return [type] [description]
     */
    public function setup()
    {
        $this->config = new Config($this->token, $this->secure, $this->host);
    }

    /** @test */
    public function it_can_decrypt_data_expressively()
    {
        /**
         * This expression uses the create method
         * from the walet addresses instance.
         */
        $instance =

        Bitgo::utilities($this->config)
            ->decrypt($this->input)
            ->password($this->password)
        ;

        $this->checkInstanceValues($instance);
    }

    /** @test */
    public function decrypt_a_wallet_expressively()
    {
        /**
         * This expression uses the create
         * method from the wallet
         * instance.
         */
        $instance =

        Bitgo::utilities($this->config)
            ->decrypt([
                // === Optional parameters
                'input' => $this->input,
                'password' => $this->password,
            ])
        // ->run()  will execute the call to the server.
        // ->get()  can be used instead of ->run()
        ;

        $this->checkInstanceValues($instance);
    }

    protected function checkInstanceValues($instance)
    {

        $this->assertEquals(
            $this->config,
            $instance->getConfig(),
            'It should match the config that was passed into the static currency.'
        );

        $this->assertEquals(
            $this->password,
            $instance->getPassword(),
            'The password should match ' . $this->password . ' for this test'
        );

        $this->assertEquals(
            $this->input,
            $instance->getInput(),
            'The input should match ' . $this->input . ' for this test'
        );
    }
}

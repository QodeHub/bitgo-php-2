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

namespace Qodehub\Bitgo\Utilities;

use Qodehub\Bitgo\Api\Api;
use Qodehub\Bitgo\Coin;

/**
 * CreateKeychain Class
 *
 * This class creates keychains locally
 * without ever touching the
 * bitgo's  network.
 *
 * @example Bitgo::utilities($config)->createKeychain()->run()
 */
class CreateKeychain extends Api
{
    use Coin;

    /**
     * Construct for creating a new instance of this class
     *
     * @param array|string $data An array with assignable Parameters
     */
    public function __construct($data = [])
    {
        //
    }

    /**
     * This will call the api and create the wallet after all parameters
     * have been set.
     *
     * @return Response  A response from the bitgo server
     */
    public function run()
    {
        return $this->_post('/keychain/local');
    }
}

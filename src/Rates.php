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

namespace Qodehub\Bitgo;

use Qodehub\Bitgo\Api\Api;
use Qodehub\Bitgo\Coin;

/**
 * Rates Class
 *
 * This class is responsible for getting the rates
 * and latest market data for a given coin type.
 *
 * @example Bitgo::btc($config)->rates()->get()->latest
 */
class Rates extends Api
{
    use Coin;

    /**
     * Overwrite the parent's constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * The method places the call to the Bitgo API
     *
     * @return Response
     */
    public function run()
    {
        return $this->_get('/market/latest');
    }
}

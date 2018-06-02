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
use Qodehub\Bitgo\Config;

/**
 * Utilities Class
 *
 * This class is bridge to the utility methods
 * that are shipped with this package.
 *
 * @example Bitgo::utilities($config)->encrypt('data')->password('password')->run();
 * @example Bitgo::utilities($config)->decrypt('data')->password('password')->run();
 */
class Utilities extends Api
{
    use Coin;

    /**
     * This is a configuration instance that will
     * be used by any of the utility class that
     * may need it.
     *
     * @var Config
     */
    protected $utilityConfig;

    /**
     * The construct method accepts a the configuration instance
     *
     * @param Config $config the configuration instance.
     */
    public function __construct(Config $config = null)
    {
        if ($config instanceof Config) {

            $this->utilityConfig = $config;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt($attributes = [])
    {
        return $this->getUtilityInstance('Encrypt', $attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt($attributes = [])
    {
        return $this->getUtilityInstance('Decrypt', $attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function keychains($attributes = [])
    {
        return $this->getUtilityInstance('Keychains', $attributes);
    }

    /**
     * Dynamically handle calls to sub-classes and pass in the wallet instance ID.
     *
     * @param   string $method
     * @param   array  $parameters
     * @return  self
     * @throws  \BadMethodCallException
     * @example walletInstance::transactions()->get();
     * @example walletInstance::transactions()->get('transactionId');
     */
    protected function getUtilityInstance($method, $parameters)
    {
        /**
         * Append a capitalized name of the method
         * passed in, to create a class address
         *
         * @var string
         */
        $class = '\\Qodehub\\Bitgo\\Utilities\\' . ucwords($method);

        /**
         * Create a new instance of the class
         * since it exists and is in the
         * list of allowed magic
         * methods lists.
         */
        $executionInstace = new $class($parameters);

        /**
         * Inject the coin type if the instance
         * has a cointype method
         */
        if (method_exists($executionInstace, 'coinType')) {
            $executionInstace->coinType($this->getCoinType());
        }

        /**
         * Inject the Api configuration if
         * any exists on the chain.
         */
        if ($this->utilityConfig instanceof Config) {

            $executionInstace->injectConfig($this->utilityConfig);
        } elseif ($this->getConfig() instanceof Config) {

            $executionInstace->injectConfig($this->getConfig());
        }

        return $executionInstace;
    }
}

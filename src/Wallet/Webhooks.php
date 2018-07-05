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

namespace Qodehub\Bitgo\Wallet;

use GuzzleHttp\Psr7\Response;
use Qodehub\Bitgo\Api\Api;
use Qodehub\Bitgo\Coin;
use Qodehub\Bitgo\Utility\CanCleanParameters;
use Qodehub\Bitgo\Utility\MassAssignable;
use Qodehub\Bitgo\Wallet;

/**
 * Webhooks Class
 *
 * This class is responsible for listing all the webhooks for a given wallet.
 *
 * This class will require that a walletId is present. Examples are attaches
 *
 * @example Bitgo::btc($config)->wallet($walletId)->wabhooks()->run();
 */
class Webhooks extends Api
{
    use WalletTrait;
    use MassAssignable;
    use CanCleanParameters;
    use Coin;

    /**
     * {@inheritdoc}
     */
    protected $parametersRequired = [
        'walletId',
    ];

    /**
     * {@inheritdoc}
     */
    protected $parametersOptional = [
        'allTokens',
    ];

    /**
     * Gets details of all token webhooks for the wallet.
     * Only valid for eth/teth.
     *
     * @var bool
     */
    protected $allTokens;

    /**
     * Construct for creating a new instance of this class
     *
     * @param array $data An array with assignable Parameters
     */
    public function __construct($data = [])
    {
        $this->massAssign($data);
    }

    /**
     * A helper method for setting the allTokens type
     *
     * @param  bool $allTokens True or false
     * @return self
     */
    public function allTokens(bool $allTokens)
    {
        return $this->setAllTokens($allTokens);
    }

    /**
     * Add a webhook to a given wallet
     *
     * @param  any ...$args this will pass all arguments to the
     *                      addWebhook constructor and make
     *                      a new instance.
     * @return \Qodehub\Bitgo\Wallet\AddWebhook A new AddWebhook instance
     */
    public function add(...$args)
    {
        return (new AddWebhook(...$args))
            ->coinType($this->coinType)
            ->wallet($this->walletId)
            ->injectConfig($this->config);
    }

    /**
     * Remove a webhook from a given wallet
     *
     * @param  any ...$args this will pass all arguments to the
     *                      removeWebhook constructor and make
     *                      a new instance.
     * @return \Qodehub\Bitgo\Wallet\RemoveWebhook A new RemoveWebhook instance
     */
    public function remove(...$args)
    {
        return (new RemoveWebhook(...$args))
            ->coinType($this->coinType)
            ->wallet($this->walletId)
            ->injectConfig($this->config);
    }

    /**
     * @return string
     */
    public function getAllTokens()
    {
        return $this->allTokens;
    }

    /**
     * @param string $allTokens
     *
     * @return self
     */
    public function setAllTokens($allTokens)
    {
        $this->allTokens = $allTokens;

        return $this;
    }

    /**
     * This will call the api and create the wallet after all parameters
     * have been set.
     *
     * @return Response  A response from the bitgo server
     */
    public function run()
    {
        $this->propertiesPassRequired();

        return $this->_get('/wallet/{walletId}/webhooks', $this->propertiesToArray());
    }
}

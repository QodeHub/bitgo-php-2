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
use Qodehub\Bitgo\Exception\InvalidRequestException;
use Qodehub\Bitgo\Utility\CanCleanParameters;
use Qodehub\Bitgo\Utility\MassAssignable;
use Qodehub\Bitgo\Wallet;

/**
 * AddWebhook Class
 *
 * This class is responsible for adding a webhook url to a given wallet addresses.
 *
 * This class will require that a walletId is present. Examples are attaches
 *
 * @example Bitgo::btc($config)->wallet($walletId)->addWebhook($url)->run();
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class AddWebhook extends Api
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
        'url',
        'type',
    ];

    /**
     * {@inheritdoc}
     */
    protected $parametersOptional = [
        'numConfirmations',
    ];
    /**
     * A valid URL to fire the webhook to.
     * @var string
     */
    protected $url;
    /**
     * Type of event to listen to (can be transfer or pendingaapproval).
     * @var string
     */
    protected $type;
    /**
     * Number of confirmations before triggering the webhook. If 0 or
     * unspecified, requests will be sent to the callback endpoint
     * when the transfer is first seen and when it is confirmed.
     * @var number
     */
    protected $numConfirmations;

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
     * A helper method for setting the callback type
     * @param  string $type This should be either transfer or approvalrequired
     * @return self
     * @throws  InvalidRequestException
     */
    public function type($type)
    {
        return $this->setType($type);
    }

    /**
     * A helper method for setting the callback URL
     * @param  string $type This should be a valid callback url
     * @return self
     * @throws  InvalidRequestException
     */
    public function url($type)
    {
        return $this->setUrl($type);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return self
     * @throws  InvalidRequestException
     */
    public function setUrl($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $this->url = $url;

            return $this;
        }

        throw new InvalidRequestException('The url field can only accept a valid URL: ' . $url);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return self
     * @throws  InvalidRequestException
     */
    public function setType($type)
    {
        if (in_array($type, ['transfer', 'pendingaapproval'])) {

            $this->type = $type;

            return $this;
        }

        throw new InvalidRequestException('The type field can only be either \'transfer\' or \'pendingaapproval\': ' . $type);
    }

    /**
     * @return number
     */
    public function getNumConfirmations()
    {
        return $this->numConfirmations;
    }

    /**
     * @param number $numConfirmations
     *
     * @return self
     */
    public function setNumConfirmations($numConfirmations)
    {
        $this->numConfirmations = $numConfirmations;

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

        return $this->_post('/wallet/{walletId}/webhooks', $this->propertiesToArray());
    }
}

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

use Qodehub\Bitgo\Api\Api;
use Qodehub\Bitgo\Coin;
use Qodehub\Bitgo\Utility\CanCleanParameters;
use Qodehub\Bitgo\Utility\MassAssignable;
use Qodehub\Bitgo\Wallet;

/**
 * SignTransactions Class
 *
 * This class  presigns a transaction that can be signed and sent to the bitgo server.
 *
 * This class will require that a walletId is present. Examples are attaches
 *
 * @example Bitgo::btc($config)->wallet($walletId)->transactions()->sign()->amount()->address()->get();
 * @example Bitgo::btc($config)->wallet($walletId)->transactions($transactionId)->get();
 */
class SendTransaction extends SignTransaction
{
    use WalletTrait;
    use MassAssignable;
    use CanCleanParameters;
    use Coin;

    /**
     * {@inheritdoc}
     */
    protected $parametersRequired = [
        'txHex',
        'walletId',
        'halfSigned',
    ];

    /**
     * {@inheritdoc}
     */
    protected $parametersOptional = [
        'otp',
        'comment',
    ];

    protected $parametersSwapable = [

        'txHex' => [
            ['halfSigned'],
        ],

        'halfSigned' => [
            ['txHex'],
        ],
    ];

    /**
     * The half-signed info returned from 'Sign Transaction’
     *
     * @var Object
     */
    protected $halfSigned;

    /**
     * The half-signed, serialized transaction hex(alternative to halfSigned)
     *
     * @var string
     */
    protected $txHex;

    /**
     * The current 2FA code
     *
     * @var string
     */
    protected $otp;

    /**
     * Any additional comment to attach to the transaaction
     *
     * @var string
     */
    protected $comment;

    /**
     * Construct for creating a new instance of this class
     *
     * @param array|string $data An array with assignable Parameters or the
     *                           transactionID
     */
    public function __construct($data = null)
    {
        if (is_array($data)) {
            $this->massAssign($data);
        }

        return $this;
    }

    /**
     * A convinievt method for setting the half-signed transaction
     *
     * @param Object An object representing the half signed.
     * @return self
     */
    public function halfSigned(Object $halfSigned)
    {
        return $this->setHalfSigned($halfSigned);
    }

    /**
     * A convinievt method for setting the transactoon-hex
     *
     * @param Object An object representing the half signed.
     * @return self
     */
    public function txHex(string $txHex)
    {
        return $this->setTxHex($txHex);
    }

    /**
     * A convinievt method for setting the otp
     *
     * @param string A string representing the half signed.
     * @return self
     */
    public function otp(string $txHex)
    {
        return $this->setOtp($txHex);
    }

    /**
     * @return Object
     */
    public function getHalfSigned()
    {
        return $this->halfSigned;
    }

    /**
     * @param Object $halfSigned
     *
     * @return self
     */
    public function setHalfSigned(Object $halfSigned)
    {
        $this->halfSigned = $halfSigned;

        return $this;
    }

    /**
     * @return string
     */
    public function getTxHex()
    {
        return $this->txHex;
    }

    /**
     * @param string $txHex
     *
     * @return self
     */
    public function setTxHex($txHex)
    {
        $this->txHex = $txHex;

        return $this;
    }

    /**
     * @return string
     */
    public function getOtp()
    {
        return $this->otp;
    }

    /**
     * @param string $otp
     *
     * @return self
     */
    public function setOtp($otp)
    {
        $this->otp = $otp;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return self
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * The method places the call to the Bitgo API
     *
     * @return Object
     */
    public function run()
    {
        $this->propertiesPassRequired();

        return $this->_post('/wallet/{walletId}/tx/send', $this->propertiesToArray());
    }
}

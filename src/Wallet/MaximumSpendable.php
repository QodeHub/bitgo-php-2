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
 * MaximumSpensable Class
 *
 * This get the maximum spendable amount for a given wallet.
 *
 * This class requires that a walletId be present. Examples are attaches
 *
 * @example Btc::btc($config)->wallet($waletId)->maximumSpendable()->get();
 */
class MaximumSpendable extends Api
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
        'limit',
        'feeRate',
        'minValue',
        'maxValue',
        'minHeight',
        'minConfirms',
        'enforceMinConfirmsForChange',
    ];

    /**
     * Max number of unspents to use (if less than 200)
     * @var int
     */
    protected $limit;

    /**
     * Ignore unspents smaller than this amount of satoshis
     * @var int
     */
    protected $minValue;

    /**
     * Ignore unspents larger than this amount of satoshis
     * @var int
     */
    protected $maxValue;

    /**
     * Minimum block height of unspents to fetch
     * @var int
     */
    protected $minHeight;

    /**
     * The desired fee rate for the transaction in satoshis/kB
     * @var int
     */
    protected $feeRate;

    /**
     * The required number of confirmations for each non-change unspent
     * @var int
     */
    protected $minConfirms;

    /**
     * Apply the required confirmations set in minConfirms for change outputs
     * @var boolean
     */
    protected $enforceMinConfirmsForChange;

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
     * @return boolean
     */
    public function getEnforceMinConfirmsForChange()
    {
        return $this->enforceMinConfirmsForChange;
    }

    /**
     * @param boolean $enforceMinConfirmsForChange
     *
     * @return self
     */
    public function setEnforceMinConfirmsForChange($enforceMinConfirmsForChange)
    {
        $this->enforceMinConfirmsForChange = $enforceMinConfirmsForChange;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     *
     * @return self
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinValue()
    {
        return $this->minValue;
    }

    /**
     * @param int $minValue
     *
     * @return self
     */
    public function setMinValue($minValue)
    {
        $this->minValue = $minValue;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxValue()
    {
        return $this->maxValue;
    }

    /**
     * @param int $maxValue
     *
     * @return self
     */
    public function setMaxValue($maxValue)
    {
        $this->maxValue = $maxValue;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinHeight()
    {
        return $this->minHeight;
    }

    /**
     * @param int $minHeight
     *
     * @return self
     */
    public function setMinHeight($minHeight)
    {
        $this->minHeight = $minHeight;

        return $this;
    }

    /**
     * @return int
     */
    public function getFeeRate()
    {
        return $this->feeRate;
    }

    /**
     * @param int $feeRate
     *
     * @return self
     */
    public function setFeeRate($feeRate)
    {
        $this->feeRate = $feeRate;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinConfirms()
    {
        return $this->minConfirms;
    }

    /**
     * @param int $minConfirms
     *
     * @return self
     */
    public function setMinConfirms($minConfirms)
    {
        $this->minConfirms = $minConfirms;

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

        return $this->_get(
            '/wallet/{walletId}/maximumspendable',
            $this->propertiesToArray()
        );
    }
}

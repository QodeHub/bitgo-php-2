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
 * SendCoins Class
 *
 * This class implements methods for to create a transaction and send to multiple addresses.
 * This may be useful if you schedule outgoing transactions in bulk, as you
 * will be able to process multiple recipients and lower the
 * aggregate amount of blockchain fees paid.
 *
 * This class will require that a walletId is present. Examples are attaches
 *
 * @example Bitgo::btc($config)->wallet($walletId)->sendTransactionToMany()->setRecipients($this->recipients)->passphrase($passphrase)->run();
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class SendTransactionToMany extends BuildTransaction
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
        'recipients',
    ];

    /**
     * {@inheritdoc}
     */
    protected $parametersOptional = [
        'prv',
        'segwit',
        'comment',
        'feeRate',
        'unspents',
        'minValue',
        'maxValue',
        'gasPrice',
        'gasLimit',
        'numBlocks',
        'sequenceId',
        'maxFeeRate',
        'minConfirms',
        'noSplitChange',
        'changeAddress',
        'lastLedgerSequence',
        'ledgerSequenceDelta',
        'targetWalletUnspents',
        'enforceMinConfirmsForChange',
    ];

    /**
     * Array of recipient objects and the amount to send to each e.g.
     * [{address: ‘38BKDNZbPcLogvVbcx2ekJ9E6Vv94DqDqw’, amount: 1500}, …]
     *
     * The amount should be an Integer for all coin types
     * except Ethereum, ERC20 tokens, and Ripple
     * for which it should be a String.
     *
     * Objects describing the receive address and amount.
     *
     * @var Array
     */
    protected $recipients = [];

    /**
     * Custom upper limit for fee rate in satoshis/kB. Delimits dynamic
     * fee rates derived from numBlocks and default fee rates
     *
     * @var integer
     */
    protected $maxFeeRate;

    /**
     * @return integer
     */
    public function getMaxFeeRate()
    {
        return $this->maxFeeRate;
    }

    /**
     * @param integer $maxFeeRate
     *
     * @return self
     */
    public function setMaxFeeRate($maxFeeRate)
    {
        $this->maxFeeRate = $maxFeeRate;

        return $this;
    }

    /**
     * The method places the call to the Bitgo API
     *
     * @return Response
     */
    public function run()
    {
        $this->propertiesPassRequired();

        $this->testRecipients();

        return $this->_post(
            '/wallet/{walletId}/sendmany',
            $this->propertiesToArray()
        );
    }
}

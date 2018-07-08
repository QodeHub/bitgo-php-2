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
use Qodehub\Bitgo\Exception\InvalidRequestException;
use Qodehub\Bitgo\Exception\MissingParameterException;
use Qodehub\Bitgo\Utility\CanCleanParameters;
use Qodehub\Bitgo\Utility\MassAssignable;
use Qodehub\Bitgo\Wallet;

/**
 * BuildTransactions Class
 *
 * This class  prebuilds a transaction that can be signed and sent to the bitgo server.
 *
 * This class will require that a walletId is present. Examples are attaches
 *
 * @example Bitgo::btc($config)->wallet($walletId)->transactions()->build()->amount()->address()->get();
 * @example Bitgo::btc($config)->wallet($walletId)->transactions($transactionId)->get();
 */
class BuildTransaction extends SendCoins
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

    protected $parametersOptional = [
        'feeRate',
        'minValue',
        'maxValue',
        'gasPrice',
        'unspents',
        'numBlocks',
        'minConfirms',
        'noSplitChange',
        'changeAddress',
        'lastLedgerSequence',
        'ledgerSequenceDelta',
        'targetWalletUnspents',
        'enforceMinConfirmsForChange',
    ];

    /**
     * Objects describing the receive address and amount.
     *
     * @var Array
     */
    protected $recipients = [];

    /**
     * The unspents to use in the transaction.
     * Each unspent should be in the form prevTxId:nOutput
     *
     * @var array
     */
    protected $unspents;

    /**
     * Specifies the destination of the change output.
     *
     * @var string
     */
    protected $changeAddress;

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
     * A convinient method for setting the amount.  This will by default update
     * the amount for the first transaction. Original intention is foe setting
     * a single recipient. The index for the recipient to update can be
     * passed in as the second  argument.
     *
     * @param  int $amount This is the amount to set for the transaction
     * @param  int $index  the index of the recipient transaction to set/update
     * @return self
     */
    public function amount($amount, int $index = 0)
    {
        $this->recipients[$index]['amount'] = $amount;

        return $this;
    }

    /**
     * A convinient method for setting the address.  This will by default update
     * the address for the first transaction. Original intention is foe setting
     * a single recipient. The index for the recipient to update can be
     * passed in as the second  argument.
     *
     * @param  string $address This is the address to set for the transaction
     * @param  int    $index   the index of the recipient transaction to set/update
     * @return self
     */
    public function address(string $address, int $index = 0)
    {
        $this->recipients[$index]['address'] = $address;

        return $this;
    }

    /**
     * Get the address for the first or a given index
     * from a set of transactions/recipients
     *
     * @param  int|integer $index The index of the recipient
     * @return string             The address for the first or given index.
     */
    public function getAddress(int $index = 0)
    {
        return $this->recipients[$index]['address'];
    }

    /**
     * Get the amount for the first or a given index
     * from a set of transactions/recipients
     *
     * @param  int|integer $index The index of the recipient
     * @return int             The amount for the first or given index.
     */
    public function getAmount(int $index = 0)
    {
        return $this->recipients[$index]['amount'];
    }

    /**
     * Add a recipient to the transaction that will be built.
     *
     * @param  string $address A valid recieving wallet address
     * @param  int    $amount  The amount value in satoshi to be sent
     * @return self
     */
    public function addRecipient(string $address, int $amount)
    {
        $this->validateRecipient($recipient = compact('address', 'amount'));

        array_push($this->recipients, $recipient);

        return $this;
    }

    /**
     * Get all the recipients
     *
     * @return array An array of the recipients
     *               address and amount
     *               accordingly
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * Get a single recipient
     *
     * @param  int $index The index of the recipient in the array.
     * @return array A single recipient with address and amount.
     */
    public function getRecipient(int $index = 0)
    {
        return $this->recipients[$index];
    }

    /**
     * Set the recipients for the transaction
     *
     * @param array $recipients An array of recipients collection.
     */
    public function setRecipients(array $recipients)
    {
        foreach ($recipients as $recipient) {
            $this->validateRecipient($recipient);
        }

        $this->recipients = $recipients;
    }

    /**
     * Validate recipient information passed in.
     *
     * @param  array $recipient Recipient array containing amount and address.
     * @return void
     *
     * @throws InvalidRequestException
     */
    private function validateRecipient(array $recipient)
    {
        $result = filter_var_array(
            $recipient, [
            'amount' => FILTER_VALIDATE_FLOAT,
            'address' => array(
                'filter' => FILTER_VALIDATE_REGEXP | FILTER_SANITIZE_STRING,
                'options' => array('regexp' => '/^\w$/'),
            ),
            ]
        );

        if ($result['address'] && $result['amount']) {
            return $result;
        }

        throw new InvalidRequestException(
            "
            Invalid recipient information.
            address and amount are required for each recipient information.
            address: '" . $result['address'] . "',
            amount: '" . $result['amount'] . "'
        "
        );
    }

    /**
     * @return array
     */
    public function getUnspents()
    {
        return $this->unspents;
    }

    /**
     * @param array $unspents
     *
     * @return self
     */
    public function setUnspents(array $unspents)
    {
        $this->unspents = $unspents;

        return $this;
    }

    /**
     * @return string
     */
    public function getChangeAddress()
    {
        return $this->changeAddress;
    }

    /**
     * @param string $changeAddress
     *
     * @return self
     */
    public function setChangeAddress($changeAddress)
    {
        $this->changeAddress = $changeAddress;

        return $this;
    }

    /**
     * Test that the recipients are valid.
     *
     * @return boolean
     */
    private function testRecipients()
    {
        if ((bool) count($this->recipients)) {
            foreach ($this->recipients as $recipient) {
                $this->validateRecipient($recipient);
            }

            return $this->recipients;
        }

        throw new MissingParameterException('Recipients cannot be left empty');
    }

    /**
     * The method places the call to the Bitgo API
     *
     * @return Object
     */
    public function run()
    {
        $this->propertiesPassRequired();

        $this->testRecipients();

        return $this->_post('/wallet/{walletId}/tx/build', $this->propertiesToArray());
    }
}

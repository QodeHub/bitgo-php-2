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
use Qodehub\Bitgo\Utility\CanCleanParameters;
use Qodehub\Bitgo\Utility\MassAssignable;

/**
 * Encrypt Class
 *
 * This class uses the Bitgo Local encryption to create
 * assemetric encrypted data without touching
 * the bitgo's network.
 *
 * @example Bitgo::utilities($config)->keychains($keyId)->get()
 */
class Keychains extends Api
{
    use MassAssignable;
    use CanCleanParameters;
    use Coin;

    /**
     * {@inheritdoc}
     */
    protected $parametersRequired = [

    ];

    /**
     * {@inheritdoc}
     */
    protected $parametersOptional = [
        'id',
    ];

    /**
     * This will be the id of a keychain in the
     * case of getting a single one.
     *
     * @var string
     */
    protected $id;

    /**
     * Construct for creating a new instance of this class
     *
     * @param array|string $data An array with assignable Parameters
     */
    public function __construct($data = null)
    {
        if (is_string($data)) {

            $this->setId($data);
        }

        if (is_array($data)) {

            $this->massAssign($data);
        }
    }

    /**
     * Helper method for setting the ID of the key
     *
     * @param  string $id Key IDs
     * @return self
     */
    public function id($id)
    {
        return $this->keyId($id);
    }

    /**
     * Helper method for setting the ID of the key
     *
     * @param  string $id Key IDs
     * @return self
     */
    public function keyId($id)
    {
        return $this->setId($id);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

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

        return $this->_get('/key/{id}', $this->propertiesToArray());
    }
}

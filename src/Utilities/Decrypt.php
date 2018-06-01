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
use Qodehub\Bitgo\Utility\CanCleanParameters;
use Qodehub\Bitgo\Utility\MassAssignable;

/**
 * Decrypt Class
 *
 * This class uses the Bitgo Local decryption to create
 * assemetric decrypted data without touching
 * the bitgo's network.
 *
 * @example Bitgo::utilities($config)->decrypt($input)->password($password)->run()
 */
class Decrypt extends Api
{
    use MassAssignable;
    use CanCleanParameters;

    /**
     * {@inheritdoc}
     */
    protected $parametersRequired = [
        'password',
        'input',
    ];

    /**
     * {@inheritdoc}
     */
    protected $parametersOptional = [

    ];

    /**
     * This will be the password with which the
     * input will be decrypted.
     *
     * @var string
     */
    protected $password;

    /**
     * This is the input that will be
     * decrypted with the password.
     *
     * @var mixed
     */
    protected $input;

    /**
     * Construct for creating a new instance of this class
     *
     * @param array $data An array with assignable Parameters
     */
    public function __construct($data = null)
    {
        if (is_string($data)) {

            $this->decrypt($data);
        }

        if (is_array($data)) {

            $this->massAssign($data);
        }
    }

    /**
     * Helper method for setting the input to be
     * decrypted on the instance.
     *
     * @param  mixed $input Data to be decrypted
     * @return self
     */
    public function decrypt($input)
    {
        return $this->input($input);
    }

    /**
     * Helper method for setting the input to be
     * decrypted on the instance.
     *
     * @param  mixed $input Data to be decrypted
     * @return self
     */
    public function input($input)
    {
        return $this->setInput($input);
    }

    /**
     * Helper method for setting the password
     * for the encryption/encrypted data
     *
     * @param  mixed $input Encrypted data password
     * @return self
     */
    public function password($input)
    {
        return $this->setPassword($input);
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @param mixed $input
     *
     * @return self
     */
    public function setInput($input)
    {
        $this->input = $input;

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

        return $this->_post('/decrypt', $this->propertiesToArray());
    }
}

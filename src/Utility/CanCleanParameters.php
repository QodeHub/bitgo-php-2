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

namespace Qodehub\Bitgo\Utility;

use Qodehub\Bitgo\Exception\MissingParameterException;

/**
 * Trait CanCleanParameters
 */
trait CanCleanParameters
{
    /**
     * This method checks that all properties marked as
     * required have been assigned a value.
     *
     * A protected $parametersRequired property must contain the names of
     * the required parameters on the class that will use this trait method.
     * and all parameters must each have a defined get accessor on the
     * class object instance
     *
     * @return bool
     * @throws \Qodehub\Bitgo\Exception\MissingParameterException
     */
    protected function propertiesPassRequired()
    {
        $keys = '';

        foreach ($this->parametersRequired as $key) {
            if (is_null($this->accessPropertyByKey($key))) {
                if (array_key_exists($key, $this->parametersSwapable)) {
                    // Basically, here, we'd isolated and test each of the swappable arrays
                    // then return a count for each faild test in each swapable group.
                    //
                    // This way, if even a single value from the set of swappable group is not set,
                    // it will return a count for the number of failed result for a group and
                    // then each group will also count as a filed test.

                    $testFailed = array_map(

                        function ($values) {
                            return (int) (boolean) array_sum(array_map(function ($swap) {

                                return (int) is_null($this->accessPropertyByKey($swap));
                            }, $values));
                        },
                        $this->parametersSwapable[$key]
                    );

                    // Here, we check the number of failed groups and if it is the same as the
                    // number of optional groups, then it means that no single group passed
                    // the test. For this reason,  we will give a comphrehensive report
                    // on how o pass the test along with the set of possible swaps.

                    if (array_sum($testFailed) == count($this->parametersSwapable[$key])) {
                        $swaps = array_map(function ($swap) {
                            return implode(' & ', $swap);
                        }, $this->parametersSwapable[$key]);

                        $keys .= '[' . $key . ' or ' . implode(' or ', $swaps) . '], ';
                    }
                }

                // You get the basic idea ;)

                if (!array_key_exists($key, $this->parametersSwapable)) {
                    $keys .= $key . ', ';
                }
            }
        }

        if ($keys) {
            throw new MissingParameterException(
                str_replace(', .', '', 'The following parameters are required: ' . $keys . '.')
            );
        }

        return true;
    }
    /**
     * This method picks up all the defined properties the
     * $parameterRequired|$parameterOptional property
     * array list from the class object and returns
     * an array containing each list item name as
     * a key and the matching property value from
     * the class
     *
     * @return array An array of parameters with values
     */
    protected function propertiesToArray()
    {
        $properties = [
            $this->parametersRequired,
            $this->parametersOptional,
        ];

        $cleanProperty = [];

        foreach ($properties as $array) {
            foreach ($array as $key) {
                if ($this->accessPropertyByKey($key)) {
                    $cleanProperty[$key] = $this->accessPropertyByKey($key);
                }
            }
        }

        return $cleanProperty;
    }
    /**
     * This method calls the accessors for keys passed in
     * and returns back the value it receives from the
     * class instance
     *
     * throws an error if a defined parameter in the
     * $parameterRequired|$parameterOptional does
     * not have a reachable get[PropertyName] accessor
     * defined on the class instance.
     *
     * @param  string $key /$parameterRequired[(*)]|$parameterOptional[(*)]/
     * @return mixed
     * @throws \BadMethodCallException
     */
    protected function accessPropertyByKey($key)
    {
        if (method_exists($this, 'get' . $key)) {
            return $this->{'get' . ucwords($key)}();
        }

        throw new \RuntimeException(
            'The ' . $key . ' parameter must have a defined get' . ucwords($key) . ' method.'
        );
    }
}

<?php

/**
 * This file is part of the RollerworksRecordFilterBundle.
 *
 * (c) Rollerscapes
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link    http://projects.rollerscapes.net/RollerFramework
 * @license http://www.opensource.org/licenses/lgpl-license.php LGPL
 */

namespace Rollerworks\RecordFilterBundle\Formatter\Type;

use Rollerworks\RecordFilterBundle\Formatter\FilterType;

/**
 * Time Formatter value-type
 *
 * @author Sebastiaan Stok <s.stok@rollerscapes.net>
 */
class Time implements FilterType
{
    /**
     * Get timestamp of an value
     *
     * @param string $time
     * @return integer
     */
    protected function getTimestamp($time)
    {
        $date = new \DateTime($time);

        return $date->getTimestamp();
    }

    /**
     * Sanitize the input string to an normal useful value.
     *
     * @param string $input
     * @return string
     */
    public function sanitizeString($input)
    {
        return DateTimeHelper::timeToISO($input);
    }

    /**
     * Internal helper function for fixing cases with timezone usage.
     *
     * @param string $input
     * @param string $input2
     * @return array
     */
    protected function addTimezone($input, $input2)
    {
        $time1 = strpos($input, '+');
        $time2 = strpos($input2, '+');

        if (false !== $time1 && false === $time2) {
            return array($input, $input2 . \date('P'));
        }
        elseif (false === $time1 && false !== $time2) {
            return array($input . \date('P'), $input2);
        }
        else {
            return array($input, $input2);
        }
    }

    /**
     * Returns whether the first value is higher then the second
     *
     * @param string $input
     * @param string $nextValue
     * @return boolean
     */
    public function isHigher($input, $nextValue)
    {
        $times = $this->addTimezone($input, $nextValue);

        $input     = $times[ 0 ];
        $nextValue = $times[ 1 ];

        return ($this->getTimestamp($input) > $this->getTimestamp($nextValue));
    }

    /**
     * Returns whether the first value is lower then the second
     *
     * @param string $input
     * @param string $nextValue
     * @return boolean
     */
    public function isLower($input, $nextValue)
    {
        $times = $this->addTimezone($input, $nextValue);

        $input     = $times[ 0 ];
        $nextValue = $times[ 1 ];

        return ($this->getTimestamp($input) < $this->getTimestamp($nextValue));
    }

    /**
     * Returns whether the first value equals then the second
     *
     * @param string $input
     * @param string $nextValue
     * @return boolean
     */
    public function isEquals($input, $nextValue)
    {
        $times = $this->addTimezone($input, $nextValue);

        $input     = $times[ 0 ];
        $nextValue = $times[ 1 ];

        return ($this->getTimestamp($input) === $this->getTimestamp($nextValue));
    }

    /**
     * Returns whether the input value is legally formatted
     *
     * @param string $input
     * @param string $message
     * @return boolean
     */
    public function validateValue($input, &$message = null)
    {
        $input = str_replace('.', ':', $input);

        $message = 'This value is not an valid time';

        return DateTimeHelper::isTime($input);
    }
}
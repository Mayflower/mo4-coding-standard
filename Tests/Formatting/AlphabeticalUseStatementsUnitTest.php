<?php

/**
 * This file is part of the mo4-coding-standard (phpcs standard)
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer-MO4
 * @author   Xaver Loppenstedt <xaver@loppenstedt.de>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @version  GIT: master
 * @link     https://github.com/Mayflower/mo4-coding-standard
 */

/**
 * Unit test class for the AlphabeticalUseStatements sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer-MO4
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 * @copyright 2013 Xaver Loppenstedt, some rights reserved.
 * @license   http://spdx.org/licenses/MIT MIT License
 * @link      https://github.com/Mayflower/mo4-coding-standard
 */

namespace MO4\Tests\Formatting;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

class AlphabeticalUseStatementsUnitTest extends AbstractSniffUnitTest
{


    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @param string $testFile test file
     *
     * @return array(int => int)
     */
    protected function getErrorList($testFile = '')
    {
        switch ($testFile) {
        case 'AlphabeticalUseStatementsUnitTest.pass.inc':
            return array();
        case 'AlphabeticalUseStatementsUnitTest.fail.1.inc':
            return array(
                    4  => 1,
                    5  => 1,
                    8  => 1,
                    9  => 1,
                    12 => 1,
                   );
        // Take care, more than one fix will be applied.
        case 'AlphabeticalUseStatementsUnitTest.fail.2.inc':
            return array(
                    6 => 1,
                    8 => 1,
                   );
        case 'AlphabeticalUseStatementsUnitTest.fail.3.inc':
            return array(
                    7  => 1,
                    8  => 1,
                    10 => 1,
                    15 => 1,
                   );
        }//end switch

        return null;

    }//end getErrorList()


    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array(int => int)
     */
    protected function getWarningList()
    {
        return array();

    }//end getWarningList()


}//end class

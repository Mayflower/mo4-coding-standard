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
 * Variable in Double Quoted String sniff.
 *
 * Variables in double quoted strings must be surrounded by { }
 *
 * @category  PHP
 * @package   PHP_CodeSniffer-MO4
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 * @copyright 2013 Xaver Loppenstedt, some rights reserved.
 * @license   http://spdx.org/licenses/MIT MIT License
 * @link      https://github.com/Mayflower/mo4-coding-standard
 */
class MO4_Sniffs_Strings_VariableInDoubleQuotedStringSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * The PHP_CodeSniffer object controlling this run.
     *
     * @var PHP_CodeSniffer
     */
    private $phpCsFile = null;

    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array(int)
     * @see    Tokens.php
     */
    public function register()
    {
        return array(T_DOUBLE_QUOTED_STRING);
    }

    /**
     * Called when one of the token types that this sniff is listening for
     * is found.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The PHP_CodeSniffer file where the
     *                                        token was found.
     * @param int                  $stackPtr  The position in the PHP_CodeSniffer
     *                                        file's token stack where the token
     *                                        was found.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $this->phpCsFile = $phpcsFile;

        $varRegExp = '/\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/';

        $tokens  = $phpcsFile->getTokens();
        $content = $tokens[$stackPtr]['content'];

        $matches = array();

        if (preg_match_all($varRegExp, $content, $matches, PREG_OFFSET_CAPTURE) === 0) {
            return;
        }

        foreach ($matches as $match) {
            foreach ($match as $info) {
                list($var, $pos) = $info;

                if ($pos === 1 || $content[$pos - 1] !== '{') {
                    if (strpos(substr($content, 0, $pos), '{') && !strpos(substr($content, 0, $pos), '}')) {
                        continue;
                    }

                    $this->searchForBackslashes($content, $pos);


                    $fix = $this->phpCsFile->addFixableError(
                        sprintf(
                            'must surround variable %s with { }',
                            $var
                        ),
                        $stackPtr
                    );

                    if ($fix) {
                        $correctVariable = $this->surroundVariableWithBraces($content, $pos, $var);
                        $this->fixPhpCsFile($stackPtr, $correctVariable);
                    }
                }
            }
        }
    }

    /**
     * @param $content
     * @param $pos
     * @param $var
     * @return string
     */
    private function surroundVariableWithBraces($content, $pos, $var)
    {
        $before = substr($content, 0, $pos);
        $after = substr($content, $pos + strlen($var));
        $newContent = $before . "{" . $var . "}" . $after;
        return $newContent;
    }

    /**
     * @param $stackPtr
     * @param $correctVariable
     */
    private function fixPhpCsFile($stackPtr, $correctVariable)
    {
        $phpCsFile = $this->phpCsFile;

        $phpCsFile->fixer->beginChangeset();
        $phpCsFile->fixer->replaceToken($stackPtr, $correctVariable);
        $phpCsFile->fixer->endChangeset();
    }

    /**
     * @param $content
     * @param $pos
     */
    private function searchForBackslashes($content, $pos)
    {
        if ($content[$pos - 1] === "\\") {
            $backslashPos = $pos - 1;

            while ($content[$backslashPos] === "\\") {
                $backslashPos--;
            }

            if ((($pos - 1 - $backslashPos) % 2) === 1) {
                return;
            }
        }
    }
}
 
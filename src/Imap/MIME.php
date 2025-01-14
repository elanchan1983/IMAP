<?php

/*
 * This file is part of the Fetch package.
 *
 * (c) Robert Hafner <tedivm@tedivm.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imap;

/**
 * This library is a wrapper around the Imap library functions included in php.
 *
 * @package Fetch
 * @author  Robert Hafner <tedivm@tedivm.com>
 * @author  Sergey Linnik <linniksa@gmail.com>
 */
final class MIME
{
    /**
     * @param string $text
     * @param string $targetCharset
     *
     * @return string
     */
    public static function decode($text, $targetCharset = 'utf-8')
    {
        if (null === $text) {
            return null;
        }

        if(strtoupper(mb_detect_encoding($text)) == strtoupper($targetCharset)) {
            return $text;
        }

        $result = '';
        foreach (imap_mime_header_decode($text) as $word) {

            $ch = 'default' === $word->charset ? 'ascii' : $word->charset;
            try {
                //$result .= iconv($ch, $targetCharset, $word->text);
                $result .= mb_convert_encoding($word->text, $targetCharset, $ch);
            } catch (\Exception $e) {
                $result .= '';
            }
        }
        if(!$result) {
            $result = mb_decode_mimeheader($text);
        }
        return $result;
    }
}

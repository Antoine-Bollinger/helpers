<?php 
/*
 * This file is part of the Abollinger\Helpers package.
 *
 * (c) Antoine Bollinger <antoine.bollinger@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Abollinger;

final class Parsedown extends \Parsedown
{
    protected function blockHeader(
        $Line
    ) {
        $block = parent::blockHeader($Line);

        $link = strtolower(preg_replace('/[^A-Za-z0-9\-_]+/', '-', $block["element"]["text"]));

        $block["element"]["attributes"] = [
            "id" => $link
        ];

        return $block;
    }
}
<?php
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
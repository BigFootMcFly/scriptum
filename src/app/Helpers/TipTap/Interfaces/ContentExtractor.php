<?php

namespace App\Helpers\TipTap\Interfaces;

interface ContentExtractor
{
    /**
     * Type of the node
     */
    public const string contentId = 'nope';

    /**
     * Extracts extra data from the node
     *
     * @param  array<string, mixed>  $block  The node to extract the data from
     * @param  array<string, mixed>  $result  The list of the extracted data
     */
    public static function extraData(array $block, array &$result = []): void;

    /**
     * Automatically geerates tags based on the node data
     *
     * @param  array<string, mixed>  $block  The node to extract the data from
     * @param  array<string, mixed>  $result  The list of the extracted data
     */
    public static function autoTags(array $block, array &$result = []): void;
}

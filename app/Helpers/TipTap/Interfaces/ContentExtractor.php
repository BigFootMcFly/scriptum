<?php

namespace App\Helpers\TipTap\Interfaces;

interface ContentExtractor
{
    /**
     * Type of the node
     * @var string
     */
    public const string contentId  = 'nope';

    /**
     * Extracts extra data from the node
     * @param array $block The node to extract the data from
     * @param array $result The list of the extracted data
     * @return void
     */
    public static function extraData(array $block, array &$result = []): void;

    /**
     * Automatically geerates tags based on the node data
     * @param array $block The node to extract the data from
     * @param array $result The list of the extracted data
     * @return void
     */
    public static function autoTags(array $block, array &$result = []): void;

}

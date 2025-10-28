<?php

namespace App\Helpers\TipTap\Traits;

trait HasContentExtractor
{
    /**
     * Extracts the text contents of the node
     *
     * @param  array<string, mixed>  $block  The node to extract the data from
     * @return array<string, mixed> The list of the extracted contents
     *
     * Can be overwritten in the child classes, this is just a placeholder
     */
    public static function extractContent(array $block): array
    {
        return [];
    }

    /**
     * Extracts extra data from the node
     *
     * @param  array<string, mixed>  $block  The node to extract the data from
     * @param  array<string, mixed>  $result  The list of the extracted data
     * @return void
     *
     * Can be overwritten in the child classes, this is just a placeholder
     */
    public static function extraData(array $block, array &$result = []): void
    {
        //
    }

    /**
     * Automatically geerates tags based on the node data
     *
     * @param  array<string, mixed>  $block  The node to extract the data from
     * @param  array<string, mixed>  $result  The list of the extracted data
     * @return void
     *
     * Can be overwritten in the child classes, this is just a placeholder
     */
    public static function autoTags(array $block, array &$result = []): void
    {
        //
    }
}

<?php

namespace App\Helpers\TipTap;

use App\Helpers\TipTap\ContentExtractors\ImageContentExtractor;
use App\Helpers\TipTap\ContentExtractors\TextContentExtractor;
use App\Helpers\TipTap\CustomBlockContents\CodeCustomBlockContent;

/**
 * Extracts the human readable text from a TipTap rich content (Filament RichEditor)
 */
class TipTapJsonContentExtractor
{
    /**
     * List of the custom extractors to use ('text','image','bulletList', etc. see TipTap JSON format)
     */
    public static array $customExtractors = [
        ImageContentExtractor::contentId => ImageContentExtractor::class,
        TextContentExtractor::contentId => TextContentExtractor::class,
    ];

    /**
     * List of the custom block extractors (RicHEditor->customBlocks(...))
     */
    public static array $customBlocks = [
        CodeCustomBlockContent::contentId => CodeCustomBlockContent::class,
    ];

    /**
     * Recursively extracts the "human readable" text from a TipTap schema block
     *
     * @param  array  $block  The schema block containing the formatted text
     * @param  array|null  $result  The raw "human readable" text contents
     * @param  array|null  $extras  The extra attributes gathere by the custom extractors (@see HasContentExtractor::extractContent)
     * @param  array|null  $autoTags  The automatically created tags by the custom extractors (@see HasContentExtractor::autoTags)
     * @return array Returns the $result array for linkability
     */
    public static function extractContent(array $block, ?array &$result = [], ?array &$extras = [], ?array &$autoTags = []): array
    {

        // if it has cheld nodes, recursivelly call ourself for each child node
        if (array_key_exists('content', $block)) {
            foreach ($block['content'] as $key => $value) {
                static::extractContent($value, $result, $extras, $autoTags);
            }
        }

        // if the node has a 'text' field, collect it
        if (array_key_exists('text', $block)) {
            $result[] = $block['text'];
        }

        // if the type has an extended handler, call it
        if (array_key_exists($block['type'], static::$customExtractors)) {
            $className = static::$customExtractors[$block['type']];
            // gather text data
            $result = array_merge(
                $result,
                call_user_func("$className::extractContent", $block)
            );
            // gather extra data and auto tags
            call_user_func_array("$className::extraData", [$block, &$extras]);
            call_user_func_array("$className::autoTags", [$block, &$autoTags]);

            return $result;
        }

        // if this is "custom block" node (@see https://filamentphp.com/docs/4.x/forms/rich-editor#using-custom-blocks)
        if ($block['type'] === 'customBlock') {
            // only gather data from known types (which are registered in $customBlocks)
            if (array_key_exists($block['attrs']['id'], static::$customBlocks)) {
                $className = static::$customBlocks[$block['attrs']['id']];
                // gather text data
                $result = array_merge(
                    $result,
                    call_user_func("$className::extractContent", $block)
                );
                // gather extra data and auto tags
                call_user_func_array("$className::extraData", [$block, &$extras]);
                call_user_func_array("$className::autoTags", [$block, &$autoTags]);
            }

            return $result;
        }

        return $result;
    }
}

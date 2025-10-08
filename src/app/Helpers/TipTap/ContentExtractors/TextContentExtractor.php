<?php

namespace App\Helpers\TipTap\ContentExtractors;

use App\Helpers\TipTap\Interfaces\ContentExtractor;
use App\Helpers\TipTap\Traits\HasContentExtractor;

/**
 * Extracts contenets of 'text' node
 *
 * @extraAttributes url
 */
class TextContentExtractor implements ContentExtractor
{
    use HasContentExtractor;
    public const string contentId  = 'text';

    /**
     * @see ContentExtractor
     */
    public static function extraData(array $block, array &$result = []): void
    {
        // block has extra info
        if ($block['marks'] ?? null) {
            foreach ($block['marks'] as $mark) {
                // extra info is for a link
                if (($mark['type'] ?? null) === 'link') {
                    // link has a href attribute
                    if ($mark['attrs']['href'] ?? null) {
                        $result['url'][] = $mark['attrs']['href'];
                    }
                }
            }
        }
    }

}

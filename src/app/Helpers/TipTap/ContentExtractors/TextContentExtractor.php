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

    public const string contentId = 'text';

    /**
     * @see ContentExtractor
     * @param array<string, mixed> $block
     * @param array<string, mixed> $result
     */
    public static function extraData(array $block, array &$result = []): void
    {

        // search for urls in extra info
        foreach ($block['marks'] ?? [] as $mark) {
            // skip if it is not a link
            if (($mark['type'] ?? '') !== 'link') {
                continue;
            }
            // export url if it has a href attribute
            if ($href = $mark['attrs']['href'] ?? null) {
                $result['url'][] = $href;
            }
        }

    }
}

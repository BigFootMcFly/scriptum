<?php

namespace App\Helpers\TipTap\ContentExtractors;

use App\Helpers\TipTap\Interfaces\ContentExtractor;
use App\Helpers\TipTap\Traits\HasContentExtractor;

/**
 * Extracts contenets of 'image' node
 *
 * @extraAttributes alt
 */
class ImageContentExtractor implements ContentExtractor
{
    use HasContentExtractor;

    public const string contentId = 'image';

    /**
     * @see ContentExtractor
     *
     * @param  array<string, mixed>  $block
     * @return array<int, mixed>
     */
    public static function extractContent(array $block): array
    {
        return [
            $block['attrs']['alt'],
        ];
    }
}

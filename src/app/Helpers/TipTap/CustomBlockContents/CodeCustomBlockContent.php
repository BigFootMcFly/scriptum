<?php

namespace App\Helpers\TipTap\CustomBlockContents;

use App\Helpers\TipTap\Interfaces\ContentExtractor;
use App\Helpers\TipTap\Traits\HasContentExtractor;

class CodeCustomBlockContent implements ContentExtractor
{
    use HasContentExtractor;

    public const string contentId = 'code';

    /**
     * @see ContentExtractor
     * @param array<string, mixed> $block
     * @return array<int, mixed>
     */
    public static function extractContent(array $block): array
    {
        return [
            $block['attrs']['config']['code'],
        ];
    }

    /**
     * @see ContentExtractor
     * @param array<string, mixed> $block
     * @param array<int, string> $result
     */
    public static function autoTags(array $block, array &$result = []): void
    {
        $result[] = "#{$block['attrs']['config']['language']}";
    }
}

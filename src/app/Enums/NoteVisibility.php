<?php

namespace App\Enums;

use App\Attributes\Description;
use App\Traits\BackedEnumHelper;
use App\Traits\HasEnumDescription;

enum NoteVisibility: string
{
    use BackedEnumHelper;
    use HasEnumDescription;

    #[Description('Only the owner can access the note.')]
    case Private = 'private';

    #[Description('Everyone can read the note.')]
    case Public = 'public';

    #[Description('This note was hidden from listing.')]
    case Hidden = 'hidden';

    #[Description('This note is currently restricted from access.')]
    case Restricted = 'restricted';

    /**
     * @return array<string, string>
     */
    public static function userEditable(): array
    {
        $list = [
            self::Private,
            self::Public,
        ];

        return array_column($list, 'name', 'value');

    }
}

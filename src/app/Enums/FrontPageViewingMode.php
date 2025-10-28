<?php

namespace App\Enums;

use App\Attributes\Description;
use App\Traits\BackedEnumHelper;
use App\Traits\HasEnumDescription;

enum FrontPageViewingMode: string
{
    use BackedEnumHelper;
    use HasEnumDescription;

    #[Description('You can only see the public posts of others.')]
    case Guest = 'guest';

    #[Description('You only see your own notes.')]
    case Private = 'private';

    #[Description('You can see every public notes.')]
    case Public = 'public';

    #[Description('As an admin, you can see every notes.')]
    case Admin = 'admin';

    /**
     * @return array<string, string>
     */
    public static function userSelectable(): array
    {
        $list = [
            self::Private,
            self::Public,
        ];

        return array_column($list, 'name', 'value');

    }
}

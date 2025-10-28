<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\Eloquent\Model;

trait AssureNotNull
{
    /**
     * Assures, that the given object is not null
     *
     * The return values of functions, thtat have a return type "object|null"
     *  cannot be used as a parameter in functions, which requires an "object" as parameter
     *  and does not allow "null", this is a helper for that situation.
     * Inner php functons, composer packages and other third party functions/methods could be made used this way.
     *
     * Ex.: auth()->user() returns "\Illuminate\Contracts\Auth\Authenticatable|null"
     *      even if the code already had for ex. auth()->check() guarded before,
     *      using auth()->user()->id gives a PHPStan (lvl:8) error:
     *      Cannot access property $id on App\Models\User|null.
     *
     * @throws Exception
     */
    public static function assure(?Model $object = null): self
    {
        $className = explode('\\', self::class);
        $className = end($className);

        if ( ! $object instanceof self ) {
            throw new Exception("Cannot assure \"$className\"", 500);
        }

        return $object;
    }
}

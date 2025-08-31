<?php

namespace App\Filament\User\Pages\Auth;

use App\Models\User;
use Filament\Auth\Pages\Register;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class UserRegister extends Register
{

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent()
                    ->autoFocus()
                ,
                $this->getNameFormComponent()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('handle', Str::slug($state)))
                ,
                $this->getHandleFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getHandleFormComponent(): Component
    {
        return TextInput::make('handle')
            ->label(__('user name'))
            ->required()
            ->unique(User::class, 'handle')
            ->maxLength(255)
            ;
    }

}

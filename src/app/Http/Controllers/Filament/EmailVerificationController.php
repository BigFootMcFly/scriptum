<?php

namespace App\Http\Controllers\Filament;

use App\Http\Controllers\Controller;
use Filament\Auth\Http\Responses\Contracts\EmailVerificationResponse;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(EmailVerificationRequest $request): EmailVerificationResponse
    {
        $request->fulfill();

        Notification::make()
            ->title('Email sucessfully verified')
            ->body('You can now take notes...')
            ->success()
            ->send();

        return app(EmailVerificationResponse::class);
    }
}

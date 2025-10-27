<?php

namespace App\Http\Controllers;

use App\Actions\LogoutUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LogoutUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        LogoutUser::execute();

        // return redirect()->to(route('filament.user.pages.front-page'));
        return redirect()->to(route('filament.user.pages..'));
    }
}

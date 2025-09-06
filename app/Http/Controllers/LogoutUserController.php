<?php

namespace App\Http\Controllers;

use App\Actions\LogoutUser;
use Illuminate\Http\Request;

class LogoutUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        LogoutUser::execute();
        return redirect()->to(route('filament.user.pages.front-page'));
    }
}

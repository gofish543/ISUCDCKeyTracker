<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }
}

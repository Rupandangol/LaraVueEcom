<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;

class ApplicationController extends Controller
{
    public function __invoke()
    {
        return view('Frontend.app');
    }
}

<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;

class BackendApplicationController extends Controller
{
    public function __invoke()
    {
        return view('Frontend.Admin.app');
    }
}

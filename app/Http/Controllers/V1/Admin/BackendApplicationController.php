<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BackendApplicationController extends Controller
{
    public function __invoke()
    {
        return view('Frontend.Admin.app');
    }
}

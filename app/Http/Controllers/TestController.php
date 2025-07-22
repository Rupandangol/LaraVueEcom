<?php

namespace App\Http\Controllers;

use App\Models\TestModel;

class TestController extends Controller
{
    public function TestMethod()
    {
        $testData = TestModel::all();

        return view('TestView', ['testData' => $testData]);
    }
}

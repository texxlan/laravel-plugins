<?php

namespace App\Plugins\Test\Http\Controllers;

use App\Http\Controllers\Controller;

class TestController extends Controller
{
    const TEST = 'ABC';

    public function test()
    {
        return view('plugin:test::test');
    }

    public function test2()
    {
        return view('plugin:test::test');
    }
}

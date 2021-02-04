<?php

namespace App\Plugins\TestExtension\Plugins\Test\Http\Controllers;

use App\Http\Controllers\Controller;

class TestController extends Controller
{
    const TEST2 = '123';

    /**
     * @beforeReturn
     */
    public function test2()
    {
        return 'Extension!';
    }
}

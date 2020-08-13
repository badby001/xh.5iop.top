<?php

namespace App\Http\Controllers\Sys\Pages\System;

use App\Http\Controllers\Controller;

class SystemController extends Controller
{
    //
    //配色设置
    function alertSkin()
    {
        return view('.sys.pages.system.alertSkin');
    }

}

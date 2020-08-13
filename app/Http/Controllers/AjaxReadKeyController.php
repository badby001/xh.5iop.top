<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AjaxReadKeyController extends Controller
{
    //
    function show(Request $request, $type, $Id)
    {
        switch ($type) {
//            case 'community':
//                return getReadKeyCommunity($Id, '');
//                break;
//            case 'houseRoom':
//                return getReadKeyHouseRoom($Id, '');
//                break;
            case 'sex':
                return ['code' => 0, 'data' => getSex($Id)];
                break;
            default:
                return ['success' => false, 'msg' => '不存在的类型'];
                break;
        }
    }

}

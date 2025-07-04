<?php

namespace App\Http\Controllers;

use App\Traits\GetDatas;
use App\Traits\Services;
use App\Traits\ManageDatas;

abstract class Controller
{
    use ManageDatas, Services, GetDatas;
}

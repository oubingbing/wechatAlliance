<?php

namespace App\Http\Controllers;

use App\Models\Colleges;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function colleges()
    {
        $colleges = Colleges::query()->get([Colleges::FIELD_ID,Colleges::FIELD_NAME]);

        return webResponse('ok',200,$colleges);
    }
}

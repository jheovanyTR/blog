<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\call;

class ProcedureController extends Controller
{
    public function getUser(Request $request)
    {
        $array = [14, null];

        $query = DB::select('CALL sp_user(?,?)', $array);
        return $query;
    }

    public function getPost(Request $request)
    {
        $array = [1, null];

        $query = DB::select('CALL sp_post(?,?)', $array);
        return $query;
    }
}

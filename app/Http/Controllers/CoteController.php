<?php

namespace App\Http\Controllers;

use App\Models\AnnualWork;
use App\Models\StudentWork;
use Illuminate\Http\Request;

class CoteController extends Controller
{
    public function index(Request $request)
    {
        return StudentWork::all();
    }

    public function update(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            $id = explode('_', $key)[1];
            $cote = StudentWork::find((int) $id);
            $work = AnnualWork::find($cote->work_id);

            if ($value === "") {
                $cote->cote = null;
            } else {
                if ($value > $work->max) {
                    $cote->cote = (int) $work->max;
                } else {
                    $cote->cote = (int) $value;
                }
            }
            $cote->save();
        }
        return [
            'success' => true,
            'message' => "Cotes mis à jour avec succès"
        ];
    }
}

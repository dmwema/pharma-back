<?php

namespace App\Http\Controllers;

use App\Models\AnnualWork;
use Illuminate\Http\Request;

class AnnualWorkController extends Controller
{
    public function store (Request $request) {
        $work = new AnnualWork();
        $work->date_work = $request->date;
        $work->title = $request->title;
        $work->description = $request->description;
        $work->course_id = $request->course_id;

        if($work->save()) {
            return [
                'success' => true,
                'message' => 'Epreuve enrégistrée avec succès'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'enrégistrement'
            ];
        }
        
    }

    public function destroy (Request $request) {
        $work = AnnualWork::findOrFail($request->id)->delete();

        return [
            'success' => true,
            'message' => 'Epreuve supprimée avec succès'
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Jury;
use App\Models\JuryMember;
use App\Models\Professor;
use App\Models\Promotion;
use Illuminate\Http\Request;

class JuryController extends Controller
{
    private function get_jury($promotion_id)
    {
        $promotion = $promotion_id;
        $jury = Jury::where('promotion_id', $promotion)->first();

        $members = [];
        $jury_members = JuryMember::where('jury_id', $jury->id)->get();

        foreach ($jury_members as $m) {
            $prof = Professor::find($m->professor_id);
            $members[] = [
                'id' => $prof->id,
                'firstname' => $prof->firstname,
                'lastname' => $prof->lastname,
                'middlename' => $prof->middlename,
                'role' => JuryMember::where('jury_id', $jury->id)->where('professor_id', $prof->id)->first()->role,
            ];
        }

        return [
            "jury" => $jury,
            "members" => $members
        ];
    }

    private function posts($request)
    {
        $promotion = $request->promotion_id;
        $professor = $request->professor_id;
        $jury = Jury::where('promotion_id', $promotion)->first();

        $jury_member = JuryMember::where('jury_id', $jury->id)->where('professor_id', $professor)->first();
        $prof = Professor::find($professor);

        if ($jury_member === null) {
            return [
                'is_member' => false
            ];
        }

        return [
            'is_member' => true,
            'memberDatas' => $jury_member
        ];
    }

    public function index(Request $request)
    {
        return $this->get_jury($request->promotion_id);
    }

    public function store(Request $request)
    {
        $jury = Jury::find($request->jury);
        $prof = Professor::find($request->professor);
        $role = (int) $request->role;

        if ($jury === null) {
            $jury = new Jury();
            $jury->title = "Jury G1 Pharmacie";
            $jury->promotion_id = 1;
            $jury->status = 0;
            $jury->save();
        }

        $promotion = Promotion::find($jury->promotion_id);

        $jury_member = new JuryMember();
        $jury_member->professor_id = $prof->id;
        $jury_member->jury_id = $jury->id;
        $jury_member->role = $role;

        if ($jury_member->save()) {
            return [
                "success" => true,
                'jury' => $this->get_jury($jury->promotion_id),
                "message" => "Membre enrégistré avec succès"
            ];
        } else {
            return [
                "success" => false,
                "message" => "Erreu! une erreur est survenie lors de l'enrégistrement"
            ];
        }
    }

    public function destroy(Request $request)
    {
        $prof = Professor::find($request->prof);
        $jury = Jury::find($request->jury);

        $jury_member = JuryMember::where('professor_id', $prof->id)->where('jury_id', $jury->id)->first();

        if ($jury_member->delete()) {
            return [
                'success' => true,
                'jury' => $this->get_jury($request->promotion),
                'message' => "Membre retiré du bureau avec succès",
                'profs' => Professor::all()
            ];
        } else {
            return [
                'sucess' => false,
                'message' => "Echec"
            ];
        }
    }

    public function update(Request $request)
    {
        $jury = Jury::find($request->id);
        $jury->status = !$jury->status;

        if ($jury->save()) {
            $message = $jury->status ? 'activé' : 'desactivé';
            return [
                "success" => true,
                "jury" => $this->get_jury($request->promotion),
                "message" => 'Bureau du jury ' . $message . ' avec succès'
            ];
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Deliberation;
use App\Models\DeliberationCourse;
use Illuminate\Http\Request;

class DeliberationController extends Controller
{
    public function index(Request $request)
    {
        return Deliberation::where('promotion', $request->promotion)->get();
    }

    public function store(Request $request)
    {
        $date = $request->date;
        $promotion = $request->promotion_id;
        $title = $request->title;
        $message = $request->message;
        $destination = $request->destination ?? '1-2-3';
        $courses = Course::where('current_promotion_id', $promotion ?? 1)->get();

        $destination_arr = explode("-", $destination);

        // send to destination_arr

        $deliberation = new Deliberation();
        $deliberation->date = $date;
        $deliberation->promotion = $promotion;
        $deliberation->title = $title;
        $deliberation->message = $message;
        $deliberation->destination = $destination;
        $deliberation->published = false;

        $deliberation->save();

        // create deliberation_courses

        foreach ($courses as $course) {
            $deli_course = new DeliberationCourse();
            $deli_course->course_id = $course->id;
            $deli_course->deliberation_id = $deliberation->id;
            $deli_course->has_sent = false;
            $deli_course->save();
        }

        return [
            'success' => true,
            'message' => "Délibération convoquée avec succès",
            'deliberations' => Deliberation::all()
        ];
    }

    public function update(Request $request)
    {
        $deliberation = Deliberation::find($request->id);

        if ($request->date !== null) {
            $deliberation->date = $request->date;
        }

        if ($request->title !== null) {
            $deliberation->title = $request->title;
        }
        $deliberation->save();
        return [
            'success' => true,
            'message' => "Délibération réportée avec succès"
        ];
    }

    public function publish(Request $request)
    {
        $deliberation = Deliberation::find($request->deliberation_id);

        $deliberation->published = true;
        $deliberation->save();

        return [
            'success' => true,
            'message' => "Délibération publiée avec succès",
            'deliberations' => Deliberation::all()
        ];
    }

    public function destroy(Request $request)
    {
        $deliberation = Deliberation::find($request->deliberation_id);

        $deliberation->delete();

        return [
            'success' => true,
            'message' => "Délibération suprimée avec succès",
            'deliberations' => Deliberation::all()
        ];
    }
}

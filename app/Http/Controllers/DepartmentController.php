<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Promotion;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $return = [];
        $deps = [];

        $departments = Department::all();
        $promotions = Promotion::all();

        foreach ($departments as $department) {
            $promos =  [];
            $deps['department'] = $department->name;

            foreach ($promotions as $promotion) {
                if ($promotion->department_id === $department->id) {
                    $promos[] = $promotion;
                }
            }

            $deps['promotions'] = $promos;
            $return[] = $deps;
        }

        return $return;
    }
}

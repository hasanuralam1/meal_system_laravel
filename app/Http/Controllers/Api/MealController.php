<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MealController extends Controller
{
    /**
     * List all meals
     */
   

public function getAllMeals(Request $request)
{
    // Get inputs from JSON body
    $mealName = $request->input('meal_name');
    $date     = $request->input('date');
    $dayName  = $request->input('day_name');
    $day      = $request->input('day');      // NEW
    $night    = $request->input('night');    // NEW
    $limit    = $request->input('limit', 10);
    $offset   = $request->input('offset', 0);

    // Build query
    $query = Meal::query();

    // Filter by meal name
    if (!empty($mealName)) {
        $query->where('meal_name', 'LIKE', '%' . $mealName . '%');
    }

    // Filter by date
    if (!empty($date)) {
        $query->whereDate('date', $date);
    }

    // Filter by day name
    if (!empty($dayName)) {
        $query->where('day_name', $dayName);
    }

    // Filter by day (YES/NO or 1/0)
    if (!is_null($day)) {
        $query->where('day', $day);
    }

    // Filter by night (YES/NO or 1/0)
    if (!is_null($night)) {
        $query->where('night', $night);
    }

    // Total count before pagination
    $total = $query->count();

    // Pagination
    $meals = $query
        ->latest()
        ->offset($offset)
        ->limit($limit)
        ->get();

    return response()->json([
        'status' => true,
        'total'  => $total,
        'limit'  => (int) $limit,
        'offset' => (int) $offset,
        'data'   => $meals
    ]);
}


    /**
     * Create meal
     */
    public function mealDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'meal_name' => 'required|string|max:255',
            'date'      => 'required|date',
            'day'       => 'required|in:yes,no',
            'night'     => 'required|in:yes,no',
            'day_name'  => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $meal = Meal::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Meal created successfully',
            'data' => $meal
        ], 201);
    }

    /**
     * Show single meal
     */
    public function getmeal_ById($id)
    {
        $meal = Meal::find($id);

        if (!$meal) {
            return response()->json([
                'status' => false,
                'message' => 'Meal not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $meal
        ]);
    }

    /**
     * Update meal
     */
    public function updateMeal(Request $request, $id)
    {
        $meal = Meal::find($id);

        if (!$meal) {
            return response()->json([
                'status' => false,
                'message' => 'Meal not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'meal_name' => 'sometimes|required|string|max:255',
            'date'      => 'sometimes|required|date',
            'day'       => 'sometimes|required|in:yes,no',
            'night'     => 'sometimes|required|in:yes,no',
            'day_name'  => 'sometimes|required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $meal->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Meal updated successfully',
            'data' => $meal
        ]);
    }

    /**
     * Delete meal
     */
    public function deleteMeal($id)
    {
        $meal = Meal::find($id);

        if (!$meal) {
            return response()->json([
                'status' => false,
                'message' => 'Meal not found'
            ], 404);
        }

        $meal->delete();

        return response()->json([
            'status' => true,
            'message' => 'Meal deleted successfully'
        ]);
    }
}

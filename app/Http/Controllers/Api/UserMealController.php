<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserMeal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserMealController extends Controller
{
    /**
     * List all user meals
     */
   public function getusersAllMeal(Request $request)
{
    // Read values from JSON body
    $userName = $request->input('user_name');   // optional
    $date     = $request->input('date');        // optional
    $day      = $request->input('day');         // optional (0/1)
    $night    = $request->input('night');       // optional (0/1)
    $limit    = $request->input('limit', 10);   // default 10
    $offset   = $request->input('offset', 0);   // default 0

    // Base query
    $query = UserMeal::with('user');

    // Apply filters only if passed
    if (!empty($userName)) {
        $query->where('user_name', 'like', '%' . $userName . '%');
    }

    if (!empty($date)) {
        $query->whereDate('date', $date);
    }

    if ($day !== null && $day !== '') {
        $query->where('day', $day);
    }

    if ($night !== null && $night !== '') {
        $query->where('night', $night);
    }

    // Total count after filters
    $total = $query->count();

    // Pagination
    $data = $query
        ->latest()
        ->offset($offset)
        ->limit($limit)
        ->get();

    return response()->json([
        'status' => true,
        'total'  => $total,
        'limit'  => (int) $limit,
        'offset' => (int) $offset,
        'data'   => $data
    ]);
}


    /**
     * Create user meal entry
     */
    public function usermealDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'   => 'required|exists:users,id',
            'user_name' => 'required|string|max:255',
            'date'      => 'required|date',
            'day'    => 'required|in:eat,not',
            'night'    => 'required|in:eat,not',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $userMeal = UserMeal::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'User meal created successfully',
            'data' => $userMeal
        ], 201);
    }

    /**
     * Show single user meal
     */
    public function getuserMeal_byId($id)
    {
        $userMeal = UserMeal::with('user')->find($id);

        if (!$userMeal) {
            return response()->json([
                'status' => false,
                'message' => 'User meal not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $userMeal
        ]);
    }

    /**
     * Update user meal
     */
    public function updateUserMeal(Request $request, $id)
    {
        $userMeal = UserMeal::find($id);

        if (!$userMeal) {
            return response()->json([
                'status' => false,
                'message' => 'User meal not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id'   => 'sometimes|exists:users,id',
            'user_name' => 'sometimes|string|max:255',
            'date'      => 'sometimes|date',
            'day'    => 'sometimes|in:eat,not',
            'night'    => 'sometimes|in:eat,not',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $userMeal->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'User meal updated successfully',
            'data' => $userMeal
        ]);
    }

    /**
     * Delete user meal
     */
    public function deleteUserMeal($id)
    {
        $userMeal = UserMeal::find($id);

        if (!$userMeal) {
            return response()->json([
                'status' => false,
                'message' => 'User meal not found'
            ], 404);
        }

        $userMeal->delete();

        return response()->json([
            'status' => true,
            'message' => 'User meal deleted successfully'
        ]);
    }
}

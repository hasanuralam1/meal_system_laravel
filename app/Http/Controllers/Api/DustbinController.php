<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dustbin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DustbinController extends Controller
{
    /**
     * List all dustbin entries
     */
  
public function getAllDustbin(Request $request)
{
    // Read JSON body values
    $date     = $request->input('date');        // YYYY-MM-DD
    $dayName  = $request->input('day_name');    // monday, tuesday...
    $userName = $request->input('user_name');   // username / name
    $limit    = $request->input('limit', 10);   // default 10
    $offset   = $request->input('offset', 0);   // default 0

    // Base query with user relation
    $query = Dustbin::with('user');

    // Filter by date
    if (!empty($date)) {
        $query->whereDate('date', $date);
    }

    // Filter by day name
    if (!empty($dayName)) {
        $query->where('day_name', $dayName);
    }

    // Filter by user name
    if (!empty($userName)) {
        $query->whereHas('user', function ($q) use ($userName) {
            $q->where('name', 'LIKE', '%' . $userName . '%');
        });
    }

    // Total count (after filters, before pagination)
    $total = $query->count();

    // Pagination + ordering
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
     * Create dustbin entry
     */
    public function setDustbinDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'  => 'required|exists:users,id',
            'date'     => 'required|date',
            'day_name' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $dustbin = Dustbin::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Dustbin entry created successfully',
            'data' => $dustbin
        ], 201);
    }

    /**
     * Show single dustbin entry
     */
    public function getDustbin_ById($id)
    {
        $dustbin = Dustbin::with('user')->find($id);

        if (!$dustbin) {
            return response()->json([
                'status' => false,
                'message' => 'Dustbin entry not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $dustbin
        ]);
    }

    /**
     * Update dustbin entry
     */
    public function updateDustbin(Request $request, $id)
    {
        $dustbin = Dustbin::find($id);

        if (!$dustbin) {
            return response()->json([
                'status' => false,
                'message' => 'Dustbin entry not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id'  => 'sometimes|exists:users,id',
            'date'     => 'sometimes|date',
            'day_name' => 'sometimes|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $dustbin->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Dustbin entry updated successfully',
            'data' => $dustbin
        ]);
    }

    /**
     * Delete dustbin entry
     */
    public function deleteDustbin($id)
    {
        $dustbin = Dustbin::find($id);

        if (!$dustbin) {
            return response()->json([
                'status' => false,
                'message' => 'Dustbin entry not found'
            ], 404);
        }

        $dustbin->delete();

        return response()->json([
            'status' => true,
            'message' => 'Dustbin entry deleted successfully'
        ]);
    }
}

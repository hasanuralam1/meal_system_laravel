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
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Dustbin::with('user')->latest()->get()
        ]);
    }

    /**
     * Create dustbin entry
     */
    public function store(Request $request)
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
    public function show($id)
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
    public function update(Request $request, $id)
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
    public function destroy($id)
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

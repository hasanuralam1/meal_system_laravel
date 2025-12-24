<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Marketing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarketingController extends Controller
{
    /**
     * List all marketing entries
     */
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Marketing::with('user')->latest()->get()
        ]);
    }

    /**
     * Create marketing entry
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'market'  => 'required|string|max:255',
            'price'   => 'required|numeric|min:0',
            'date'    => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $marketing = Marketing::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Marketing entry created successfully',
            'data' => $marketing
        ], 201);
    }

    /**
     * Show single marketing entry
     */
    public function show($id)
    {
        $marketing = Marketing::with('user')->find($id);

        if (!$marketing) {
            return response()->json([
                'status' => false,
                'message' => 'Marketing entry not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $marketing
        ]);
    }

    /**
     * Update marketing entry
     */
    public function update(Request $request, $id)
    {
        $marketing = Marketing::find($id);

        if (!$marketing) {
            return response()->json([
                'status' => false,
                'message' => 'Marketing entry not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'market'  => 'sometimes|string|max:255',
            'price'   => 'sometimes|numeric|min:0',
            'date'    => 'sometimes|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $marketing->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Marketing entry updated successfully',
            'data' => $marketing
        ]);
    }

    /**
     * Delete marketing entry
     */
    public function destroy($id)
    {
        $marketing = Marketing::find($id);

        if (!$marketing) {
            return response()->json([
                'status' => false,
                'message' => 'Marketing entry not found'
            ], 404);
        }

        $marketing->delete();

        return response()->json([
            'status' => true,
            'message' => 'Marketing entry deleted successfully'
        ]);
    }
}

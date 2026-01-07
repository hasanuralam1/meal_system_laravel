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
   

public function getAllMarketing(Request $request)
{
    // Base query with user relation
    $query = Marketing::with('user');

    // 👤 Filter by USER NAME
    if ($request->filled('user_name')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'LIKE', '%' . $request->user_name . '%');
        });
    }

    // 🏪 Filter by MARKET
    if ($request->filled('market')) {
        $query->where('market', 'LIKE', '%' . $request->market . '%');
    }

    // 💰 Filter by PRICE MIN
    if ($request->filled('price_min')) {
        $query->where('price', '>=', $request->price_min);
    }

    // 💰 Filter by PRICE MAX
    if ($request->filled('price_max')) {
        $query->where('price', '<=', $request->price_max);
    }

    // 📅 Filter by DATE
    if ($request->filled('date')) {
        $query->whereDate('date', $request->date);
    }

    // 📄 Pagination
    $limit  = $request->input('limit', 10);
    $offset = $request->input('offset', 0);

    // Total count after filters
    $total = $query->count();

    // Fetch data
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
     * Create marketing entry
     */
    public function RegisterMarketing(Request $request)
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
    public function getMarketing_byId($id)
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
    public function updateMarketing(Request $request, $id)
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
    public function deleteMarket($id)
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

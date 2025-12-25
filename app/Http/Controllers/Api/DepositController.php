<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepositController extends Controller
{
    /**
     * List all deposits
     */
    public function getAllDeposite()
    {
        return response()->json([
            'status' => true,
            'data' => Deposit::with('user')->latest()->get()
        ]);
    }

    /**
     * Create deposit
     */
    public function addDeposite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'amount'  => 'required|numeric|min:0',
            'date'    => 'required|date',
            'mode'    => 'required|in:online,cash',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $deposit = Deposit::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Deposit created successfully',
            'data' => $deposit
        ], 201);
    }

    /**
     * Show single deposit
     */
    public function getDeposite_byId($id)
    {
        $deposit = Deposit::with('user')->find($id);

        if (!$deposit) {
            return response()->json([
                'status' => false,
                'message' => 'Deposit not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $deposit
        ]);
    }

    /**
     * Update deposit
     */
    public function updateDeposite(Request $request, $id)
    {
        $deposit = Deposit::find($id);

        if (!$deposit) {
            return response()->json([
                'status' => false,
                'message' => 'Deposit not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'amount'  => 'sometimes|numeric|min:0',
            'date'    => 'sometimes|date',
            'mode'    => 'sometimes|in:online,cash',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $deposit->update($request->all());
        
        $deposit->makeHidden(['created_at', 'updated_at']);

        return response()->json([
            'status' => true,
            'message' => 'Deposit updated successfully',
            'data' => $deposit
        ]);
    }

    // Delete Deposite
    public function deleteDeposite($id)
    {
        $deposit = Deposit::find($id);

        if (!$deposit) {
            return response()->json([
                'status' => false,
                'message' => 'Deposit not found'
            ], 404);
        }

        $deposit->delete();

        return response()->json([
            'status' => true,
            'message' => 'Deposit deleted successfully'
        ]);
    }
}

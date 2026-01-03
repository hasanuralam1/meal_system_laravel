<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Mail\MealNotificationMail;
use Illuminate\Support\Facades\Mail;

class AdminEmailController extends Controller
{
    public function indexsendMealEmail(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $users = User::where('role', 'user')
                     ->where('status', 'active')
                     ->get();

        foreach ($users as $user) {
            Mail::to($user->email)
                ->send(new MealNotificationMail($request->message));
        }

        return response()->json([
            'status' => true,
            'message' => 'Email sent to all users successfully'
        ]);
    }
}
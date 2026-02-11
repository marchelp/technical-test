<?php

namespace App\Http\Controllers\Api;

use App\Models\UserPocket;
use App\Models\Expenses;
use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExpensesController extends Controller
{
    use ApiResponse;

    public function store(Request $request) {
        $request->validate([
            'pocket_id' => 'required|exists:user_pockets,id',
            'amount' => 'required|integer|min:1',
            'notes' => 'required|string',
        ]);

        $pocket = UserPocket::where('id', $request->pocket_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$pocket) {
            return $this->errorResponse('Pocket tidak ditemukan', 404);
        }

        try {
            $expenses = DB::transaction(function() use ($request, $pocket){
                $newExpenses = Expenses::create([
                    'user_id' => Auth::id(),
                    'pocket_id' => $request->pocket_id,
                    'amount' => $request->amount,
                    'notes' => $request->notes,
                ]);

                $pocket->decrement('balance', $request->amount);

                return $newExpenses;
            });

            return $this->successResponse(
                'Berhasil menambah expense.',
                [
                    'id' => $expenses->id,
                    'pocket_id' => $pocket->id,
                    'current_balance' => (int) $pocket->balance
                ],
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Gagal menambah expense: ' . $e->getMessage());
        }
    }
}

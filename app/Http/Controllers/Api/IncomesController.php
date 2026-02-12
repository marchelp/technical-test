<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use App\Models\Incomes;
use App\Models\UserPocket;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IncomesController extends Controller
{
    use ApiResponse;

    // Create incomes to added into user pocket balance
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
            return $this->errorResponse('Pocket tidak ditemukan.', 404);
        }

        try {
            $incomes = DB::transaction(function() use ($request, $pocket) {
                $newIncomes = Incomes::create([
                    'user_id' => Auth::id(),
                    'pocket_id' => $request->pocket_id,
                    'amount' => $request->amount,
                    'notes' => $request->notes,
                ]);

                $pocket->increment('balance', $request->amount);

                return $newIncomes;
            });

            $pocket->refresh();

            return $this->successResponse(
                'Berhasil menambah income.',
                [
                    'id' => $incomes->id,
                    'pocket_id' => $pocket->id,
                    'current_balance' => (int) $pocket->balance
                ],
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Gagal menambah income: ' . $e->getMessage(), 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserPocketResource;
use App\Jobs\GeneratePocketReportJob;
use App\Models\UserPocket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserPocketController extends Controller
{
    use ApiResponse;

    // Create new pocket
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:100',
            'initial_balance' => 'required|integer|min:0',
        ]);

        $pocket = UserPocket::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'balance' => $request->initial_balance,
        ]);

        return $this->successResponse(
            'Berhasil membuat pocket baru.',
            new UserPocketResource($pocket),
            200
        );
    }

    // Get list pocket
    public function index() {
        $pockets = UserPocket::where('user_id', Auth::id())->get();

        return $this->successResponse(
            'Berhasil.',
            UserPocketResource::collection($pockets),
            200
        );
    }

    // Get total balance from pocket
    public function totalBalance() {
        $total = UserPocket::where('user_id', Auth::id())->sum('balance');

        return $this->successResponse(
            'Berhasil mendapatkan total balance.',
            [
                'total' => (int) $total
            ]
        );
    }

    // Create report for incomes and expenses from user pocket
    public function createReport(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:INCOME,EXPENSE',
            'date' => 'required|date_format:Y-m-d',
        ]);

        $pocket = UserPocket::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$pocket) {
            return $this->errorResponse('Pocket tidak ditemukan.', 404);
        }

        $uniqueFilename = Str::uuid() . '-' . time();

        GeneratePocketReportJob::dispatch(
            $pocket->id,
            $request->type,
            $request->date,
            $uniqueFilename
        );

        return $this->successResponse(
            'Report sedang dibuat. Silahkan check berkala pada link berikut.',
            [
                'link' => url("reports/{$uniqueFilename}")
            ],
        );
    }
}

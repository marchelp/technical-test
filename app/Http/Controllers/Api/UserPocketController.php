<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserPocketResource;
use App\Models\UserPocket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}

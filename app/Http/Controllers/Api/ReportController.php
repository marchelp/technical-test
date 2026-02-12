<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    // Get report incomes / expenses pocket file to download
    public function show($id)
    {
        $filePath = 'reports/' . $id . '.xlsx';

        if (!Storage::exists($filePath)) {
            return response()->json([
                'status' => 404,
                'error' => true,
                'message' => 'Report sedang dibuat. Silahkan check berkala pada link berikut.'
            ], 404);
        }

        return Storage::download($filePath);
    }
}

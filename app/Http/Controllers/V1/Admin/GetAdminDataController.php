<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AdminResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetAdminDataController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        try {
            $admin = Auth::user();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        return new AdminResource($admin);
    }
}

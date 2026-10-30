<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StaffController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::middleware('admin')->group(function () {
        Route::apiResource('staff', StaffController::class);
    });

    Route::middleware('permission:guests')->group(function () {
        Route::apiResource('guests', GuestController::class);
    });

    Route::middleware('permission:rooms')->group(function () {
        Route::apiResource('rooms', RoomController::class);
    });

    Route::middleware('permission:bookings')->group(function () {
        Route::apiResource('bookings', BookingController::class);
    });

    Route::get('/dashboard-stats', function () {
        return response()->json([
            'total_bookings' => \App\Models\Booking::count(),
            'total_guests' => \App\Models\Guest::count(),
            'total_rooms' => \App\Models\Room::count(),
            'total_revenue' => \App\Models\Booking::sum('total_amount'),
        ]);
    });

    Route::get('/room-overview', function () {
        $types = [
            'standard' => 'Standard Rooms',
            'deluxe' => 'Deluxe Rooms',
            'suite' => 'Executive Suites',
        ];

        $overview = [];

        foreach ($types as $type => $label) {
            $total = \App\Models\Room::where('room_type', $type)->count();
            $occupied = \App\Models\Room::where('room_type', $type)
                ->where('status', 'occupied')
                ->count();

            $occupancy = $total > 0 ? round(($occupied / $total) * 100) : 0;

            $overview[$type] = [
                'label' => $label,
                'active' => $total,
                'occupancy' => $occupancy,
            ];
        }

        return response()->json($overview);
    });    
});
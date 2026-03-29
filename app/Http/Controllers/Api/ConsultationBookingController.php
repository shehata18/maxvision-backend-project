<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConsultationBookingRequest;
use App\Models\ConsultationBooking;
use Illuminate\Http\JsonResponse;

class ConsultationBookingController extends Controller
{
    /**
     * Store a new consultation booking.
     */
    public function store(ConsultationBookingRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            \Log::info('Consultation booking request received', $validated);
            
            $booking = ConsultationBooking::create($validated);

            \Log::info('Consultation booking created successfully', [
                'id' => $booking->id,
                'email' => $booking->email,
            ]);

            return response()->json([
                'message' => 'Consultation booking submitted successfully. We will contact you shortly to confirm your appointment.',
                'data' => [
                    'id' => $booking->id,
                    'full_name' => $booking->full_name,
                    'email' => $booking->email,
                    'preferred_date' => $booking->preferred_date->format('Y-m-d'),
                    'preferred_time' => $booking->preferred_time,
                    'status' => $booking->status->value,
                ],
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Consultation booking validation failed', [
                'errors' => $e->errors(),
            ]);
            
            return response()->json([
                'message' => 'Validation failed. Please check your input.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Failed to create consultation booking', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'message' => 'Failed to submit consultation booking. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get available time slots for a specific date.
     */
    public function availableSlots(): JsonResponse
    {
        return response()->json([
            'data' => ConsultationBooking::getTimeSlots(),
        ]);
    }
}

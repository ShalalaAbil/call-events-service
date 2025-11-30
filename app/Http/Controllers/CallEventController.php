<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCallEventRequest;
use App\Services\CallEventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CallEventController extends Controller
{
    public function __construct(
        protected CallEventService $service
    ) {}

    public function store(StoreCallEventRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $this->service->handleIncomingEvent(validatedData: $data);
        } catch (Throwable $e) {
            Log::error('Failed to process call event', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to enqueue call event',
                'error' => $e->getCode(),

            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => 'queued',
        ], Response::HTTP_OK);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CallEventApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_422_when_duration_missing_for_call_ended(): void
    {
        $payload = [
            'call_id'     => 'test-call-1',
            'from_number' => '+994501112233',
            'to_number'   => '+994701112233',
            'event_type'  => 'call_ended',
            'timestamp'   => now()->toISOString(),
        ];

        $response = $this->withHeaders([
            'X-API-TOKEN' => config('app.call_events_api_token'),
        ])->postJson('/api/call-events', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['duration']);
    }
}

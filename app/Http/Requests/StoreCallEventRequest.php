<?php

namespace App\Http\Requests;

use App\Enums\CallEventType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCallEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'call_id'     => ['required', 'string', 'max:255'],
            'from_number' => ['required', 'string', 'max:50'],
            'to_number'   => ['required', 'string', 'max:50'],
            'event_type'  => [
                'required',
                'string',
                Rule::in(CallEventType::values()),
            ],
            'timestamp'   => ['required', 'date'],

            'duration'    => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $eventType = $this->input('event_type');

            if ($eventType === 'call_ended' && $this->input('duration') === null) {
                $validator->errors()->add(
                    'duration',
                    'Duration is required when event_type is call_ended.'
                );
            }
        });
    }

  
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors(),
                ],
                422
            )
        );
    }
}

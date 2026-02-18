<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bookings' => 'required|array|min:1|max:10',
            'bookings.*.consignment_no' => 'required|string|alpha_num:ascii|max:10|unique:m_booking',
            'bookings.*.client_id' => 'required|integer',
            'bookings.*.location_id' => 'required|integer',
            'bookings.*.quantity' => 'required|integer',
            'bookings.*.weight' => 'required|integer',
            'bookings.*.quantity_type' => "required|in:KG,GM",
            'bookings.*.party_name' => 'sometimes|required|string|max:255',
            'bookings.*.city_address' => 'sometimes|required|string|max:255',
            'bookings.*.status' => 'sometimes|required|string|max:2',
            'bookings.*.booking_status' => 'sometimes|required|string|max:50',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400)
        );
    }

    public function messages(): array
    {
        return [
            'bookings.required' => 'The booking is required and it should have at least one entry in it.',
            'bookings.*.consignment_no.required' => 'The consignment no is required',
            'bookings.*.consignment_no.unique' => 'The consignment no has already been taken.',
            'bookings.*.weight' => 'The weight is required.',
        ];
    }
}

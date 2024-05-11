<?php

namespace App\Http\Requests;

use App\Rules\SenderReceiverCountRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateShipmentRequest extends FormRequest
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
            'warehouse_id' => 'required|exists:warehouses,id',
            'type' => 'required|in:import,export',
            'payment_id' => 'required|integer',
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.description' => 'required|string',
            'items.*.weight' => 'required|numeric',
            'items.*.quantity' => 'required|numeric',
            'addresses' => ['required', 'array', new SenderReceiverCountRule()],
            'addresses.*.first_name' => 'required|string',
            'addresses.*.last_name' => 'required|string',
            'addresses.*.address' => 'required|string',
            'addresses.*.state' => 'required|string',
            'addresses.*.country' => 'required|string',
            'addresses.*.phone_number' => 'required|string',
            'addresses.*.landmark' => 'required|string',
            'addresses.*.type' => 'required|in:receiver,sender',
        ];
    }
}

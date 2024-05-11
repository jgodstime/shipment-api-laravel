<?php

namespace App\Http\Requests;

use App\Enums\ShipmentStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetShipmentRequest extends FormRequest
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
            'warehouse_id' => 'nullable',
            'type' => 'nullable|in:import,export',
            'status' => [Rule::in(array_column(ShipmentStatusEnum::cases(), 'name')), 'nullable'],
        ];
    }
}

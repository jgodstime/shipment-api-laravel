<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->whenNotNull($this->name),
            'description' => $this->whenNotNull($this->description),
            'amount' => $this->whenNotNull($this->amount),
            'quantity' => $this->whenNotNull($this->quantity),
        ];
    }
}

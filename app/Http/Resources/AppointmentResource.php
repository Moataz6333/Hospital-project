<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'name' => $this->patient->name,
            'phone' => $this->patient->phone,
            'doctor' => $this->doctor->user->name,
            'clinic' => [
                'name' => $this->doctor->clinic->name,
                'place' => $this->doctor->clinic->place
            ],
            'date' => date_create($this->date)->format("l d-m-Y"),
            'type' => $this->type,
            'payment_method' => $this->payment_method,
            'created_at' => date_create($this->created_at)->format("d-m-Y h-i a")
        ];
    }
}

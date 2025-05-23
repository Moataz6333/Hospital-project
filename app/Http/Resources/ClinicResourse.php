<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ClinicResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'place'=>$this->place,
            'description'=>$this->description,
            'photo'=>$this->photo ? Storage::disk('clinics')->url($this->photo) : "",
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ClinicWithDoctorsResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $doctors =[];
        foreach ($this->doctors as $doctor) {
            $doctors[]=[
                'id'=>$doctor->id,
                'name'=>$doctor->user->name,
                'profile'=>$doctor->profile ? Storage::disk('doctors')->url($doctor->profile) : asset('storage/profile.jfif') ,
                'specialty'=>$doctor->specialty,
                'experiance'=>$doctor->experiance,
                'price'=>$doctor->price ." ". config('app.currency'),
            ];
            
        }
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'place'=>$this->place,
            'description'=>$this->description,
            'photo'=>$this->photo ? Storage::disk('clinics')->url($this->photo) : "",
            'doctors'=>$doctors,
        ];
    }
}

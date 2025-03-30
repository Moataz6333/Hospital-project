<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $timeTable = [];
        $days = AllDays(); //sun=>Sunday
        $times = $this->timeTable;

        foreach ($days as $key => $day) {
            if ($times[$key . '_start'] != null && $times[$key . '_end']) {
                $timeTable[$day] =  " from " . date_create($times[$key . '_start'])->format("h:i a") . " To " . date_create($times[$key . '_end'])->format("h:i a") ;
            }
        }
       
        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'profile'=>$this->profile ? Storage::disk('doctors')->url($this->profile) : asset('storage/profile.jfif') ,
            'specialty' => $this->specialty,
            'experiance' => $this->experiance,
            'price' => $this->price . " " . config('app.currency'),
            'timeTable' => $timeTable
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResorce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $days =date_diff(now(),date_create($this->date))->days;
        return [
            'title'=>$this->title,
            'description'=>$this->description,
            'date'=>$this->date,
            'remaining_days'=>$days
        ];
    }
}

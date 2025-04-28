<?php

namespace App\Http\Resources;

use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $period='';
        switch ($this->period) {
            case  30:
                $period ='per Month';
                break;
            case  365:
                $period ='per Year';
                break;
            
            default:
                $period ='per Month';
                break;
        }
        $mostPop=false;
        $planService =new PlanService();
        if ($this->id == $planService->theMostPopular()) {
           $mostPop=true;
        }
        return [
         'id'=>$this->id,
         'title'=>$this->title,
         'period'=>$period,
         'icon_class'=>$this->icon,
         'price'=>$this->price,
         'currency'=>config('app.currency'),
         'isMostPopular'=>$mostPop,
         'features'=>explode("\r\n",$this->features)
        ];
    }
}

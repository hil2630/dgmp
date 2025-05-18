<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TournamentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'date' => $this->date,
            'prize_pool' => $this->prize_pool,
            'description' => $this->description,
            'status' => $this->status,
            'distribution' => $this->distribution,
            'season' => new SeasonResource($this->whenLoaded('season')),
            'winners' => TournamentWinnerResource::collection($this->whenLoaded('winners')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

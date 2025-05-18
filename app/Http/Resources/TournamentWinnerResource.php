<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TournamentWinnerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'position' => $this->position,
            'prize_amount' => $this->prize_amount,
            'tournament' => new TournamentResource($this->whenLoaded('tournament')),
            'team' => new TeamResource($this->whenLoaded('team')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return[
            'id' => $this->id,
            'topic' => $this->topic,
            'instructions' => $this->instructions,
            'group' => $this->group,
            'section' => $this->section,
            'shift' => $this->shift,
            'session' => $this->session,
            'due_date' => $this->due_date,
            'points' => $this->points,
            'image' => $this->image,
            'total_assignments' => $this->assignments ? $this->assignments->count() : 0,
        ];
    }
}

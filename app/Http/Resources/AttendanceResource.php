<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'group' => $this->group->name,
            'section_ids' => $this->sections->map(function ($s) {
                return $s->section_id;
            }),
            'sections' => $this->sections->map(function ($s) {
                return $s->section->name . '-' . $s->section->shift;
            }),
            'session' => $this->session->name .' | '.$this->session->type,
            'date' => $this->date,
            'type' => $this->type,
            'count' => count(json_decode($this->std_ids,true)) ?? 0,
        ];
    }
}

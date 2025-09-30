<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentDashboardResource extends JsonResource
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
            // 'id' => $this->id,
            // 'subject' => $this->subject->name,           
            // 'present' => $this->whereJsonContains('std_ids', auth()->id())->count(),
        ];
    }
}

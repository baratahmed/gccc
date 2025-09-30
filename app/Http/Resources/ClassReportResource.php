<?php

namespace App\Http\Resources;

use App\Models\StudentInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return[
            'id' => $this->id,
            'teacher' => $this->user->name,
            'subject' => $this->subject->name ?? 'N/A',
            'present' => present_count($this->std_ids,$this->section_id),
            'submitted_at' => Carbon::parse($this->created_at)->format('h:i A'),
        ];
    }
}

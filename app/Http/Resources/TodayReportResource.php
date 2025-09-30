<?php

namespace App\Http\Resources;

use App\Models\StudentInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodayReportResource extends JsonResource
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
            'teacher_name' => $this->user->name,
            'subject' => $this->user->teacher_info->subject->name ?? 'N/A',
            'session' => $this->session->name.' | '. $this->session->type,
            'present_count' => count(json_decode($this->std_ids, true)),
            'out_of' => StudentInfo::where('section_id', $this->section_id)->where('session_id', $this->session_id)->count(),
            'submitted_at' => Carbon::parse($this->created_at)->format('h:i A'),
        ];
    }
}

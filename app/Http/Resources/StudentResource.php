<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'name' => $this->name,
            'name_bn' => $this->student_info->name_bn,
            'f_name' => $this->student_info->f_name,
            'm_name' => $this->student_info->m_name,
            'roll_no' => $this->roll_no,
            'adm_roll_no' => $this->student_info->adm_roll_no,
            'gender' => $this->gender,
            'religion' => $this->religion,
            'phone' => $this->phone,
            'email' => $this->email,
            'group_id' => $this->student_info->group_id,
            'group' => $this->student_info->group->name,
            'section_ids' => $this->sections->map(function ($s) {
                return $s->section_id;
            }),
            'sections' => $this->sections->map(function ($s) {
                return $s->section->name . '-' . $s->section->shift;
            }),
            // 'section' => $this->student_info->section->name,
            // 'shift' => $this->student_info->section->shift,
            'session_id' => $this->student_info->session_id,
            'session' => $this->student_info->session->name,
            'type' => $this->student_info->session->type,
            'image' => $this->image,
            'is_verified' => $this->is_verified,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}

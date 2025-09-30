<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
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
            'subject_id' => $this->teacher_info->subject_id ?? '999',           
            'group_id' => $this->teacher_info->subject->group->id ?? '999',           
            'name' => $this->name,           
            'gender' => $this->gender,
            'religion' => $this->religion,
            'phone' => $this->phone,
            'email' => $this->email,
            'image' => $this->image,
            'is_verified' => $this->is_verified,
            'is_counsellor' => $this->teacher_info->is_counsellor ?? 0,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}

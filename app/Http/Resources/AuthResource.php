<?php

namespace App\Http\Resources;

use App\Models\StudentSections;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        if ($this->role()->name == 'Student') {
            $sections = StudentSections::with('section:id,name,shift')->where('user_id',$this->id)->get();
            return [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'group' => $this->student_info->group->name,
                'sections' => $sections,
                'shift' => $sections[0]->section->shift,
                'session' => $this->student_info->session,
                'image' => $this->image,
                'is_verified' => $this->is_verified,
                'role' => $this->role()->name,
                'permissions' => $this->getAllPermissions()->pluck('name')

            ];
        }
        else if ($this->role()->name == 'Teacher') {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'group_id' => $this->teacher_info->subject->group->id ?? '999',
                'group' => $this->teacher_info->subject->group->name ?? 'GENERAL',
                'subject_id' => $this->teacher_info->subject_id,
                'subject' => $this->teacher_info->subject->name,
                'image' => $this->image,
                'is_counsellor' => $this->teacher_info->is_counsellor,
                'is_verified' => $this->is_verified,
                'role' => $this->role()->name,
                'permissions' => $this->getAllPermissions()->pluck('name')

            ];
        }        
        else {           
            return [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'image' => $this->image,
                'is_verified' => $this->is_verified,
                'role' => $this->role()->name,
                'permissions' => $this->getAllPermissions()->pluck('name')
            ];
        }
        
        
    }
}

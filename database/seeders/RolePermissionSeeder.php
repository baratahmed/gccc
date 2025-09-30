<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $roleAdmin = Role::create(['name' => 'Admin','guard_name' => 'sanctum']);
        $roleTeacher = Role::create(['name' => 'Teacher','guard_name' => 'sanctum']);
        $roleStudent = Role::create(['name' => 'Student','guard_name' => 'sanctum']);

        // Create Permissions as Array
        $permissions= [
            [
                'group_name' => 'Dashboard',
                'permissions' => [
                    'dashboard.read',
                ]
            ],
            [
                'group_name' => 'Role',
                'permissions' => [
                    'role.create',
                    'role.read',
                    'role.update',
                    'role.delete',
                ]
            ],
            [
                'group_name' => 'User',
                'permissions' => [
                    'student.create',
                    'student.read',
                    'student.update',
                    'student.delete',
                    'teacher.create',
                    'teacher.read',
                    'teacher.update',
                    'teacher.delete',
                    'admin.create',
                    'admin.read',
                    'admin.update',
                    'admin.delete',
                ]
            ],

            [
                'group_name' => 'Attendance',
                'permissions' => [
                    'attendance.create',
                    'attendance.read',
                    'attendance.update',
                    'attendance.delete',
                ]
            ],

            [
                'group_name' => 'Topic',
                'permissions' => [
                    'topic.create',
                    'topic.read',
                    'topic.update',
                    'topic.delete',
                ]
            ],
           
            [
                'group_name' => 'Assignment',
                'permissions' => [
                    'assignment.create',
                    'assignment.read',
                    'assignment.update',
                    'assignment.delete',
                    'assignment.marking',
                ]
            ],

            [
                'group_name' => 'Report',
                'permissions' => [
                    'report.teacher',
                    'report.attendance',
                    'report.assignment',
                ]
            ],
        ];

        // Create & Assign Permissions
        for ($i = 0; $i < count($permissions); $i++) {
            $permissionGroup = $permissions[$i]['group_name'];
            for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
                $permission = Permission::create(['name' => $permissions[$i]['permissions'][$j],'group_name'=>$permissionGroup,'guard_name'=>'sanctum']);

                if($permission->name == 'dashboard.read'){
                    $roleAdmin->givePermissionTo($permission);
                    $roleTeacher->givePermissionTo($permission);
                    $roleStudent->givePermissionTo($permission);
                }

                if($permission->name == 'role.create' || $permission->name == 'role.read' || $permission->name == 'role.update' || $permission->name == 'role.delete'){
                    $roleAdmin->givePermissionTo($permission);
                }               

                if($permission->name == 'user.create' || $permission->name == 'user.read' || $permission->name == 'user.update' || $permission->name == 'user.delete'){
                    $roleAdmin->givePermissionTo($permission);
                    $roleTeacher->givePermissionTo($permission);
                }  
                
                if($permission->name == 'assignment_topic.create' || $permission->name == 'assignment_topic.read' || $permission->name == 'assignment_topic.update' || $permission->name == 'assignment_topic.delete'){
                    $roleAdmin->givePermissionTo($permission);
                    $roleTeacher->givePermissionTo($permission);
                }

                if($permission->name == 'attendance.create' || $permission->name == 'attendance.read' || $permission->name == 'attendance.update' || $permission->name == 'attendance.delete' || $permission->name == 'assignment.marking'){
                    if($permission->name != 'attendance.create'){
                        $roleAdmin->givePermissionTo($permission);
                    }
                    $roleTeacher->givePermissionTo($permission);
                }
              
                
            }
            
        }
        
        
    }
}

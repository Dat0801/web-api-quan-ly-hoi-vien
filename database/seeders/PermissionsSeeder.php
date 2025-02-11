<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::insert([
            ['permission_name' => 'Chức năng 1.1', 'group_name' => 'Nhóm chức năng 1'],
            ['permission_name' => 'Chức năng 1.2', 'group_name' => 'Nhóm chức năng 1'],
            ['permission_name' => 'Chức năng 1.3', 'group_name' => 'Nhóm chức năng 1'],
            ['permission_name' => 'Chức năng 1.4', 'group_name' => 'Nhóm chức năng 1'],
            ['permission_name' => 'Chức năng 2.1', 'group_name' => 'Nhóm chức năng 2'],
            ['permission_name' => 'Chức năng 2.2', 'group_name' => 'Nhóm chức năng 2'],
            ['permission_name' => 'Chức năng 2.3', 'group_name' => 'Nhóm chức năng 2'],
            ['permission_name' => 'Chức năng 2.4', 'group_name' => 'Nhóm chức năng 2'],
            ['permission_name' => 'Chức năng 3.1', 'group_name' => 'Nhóm chức năng 3'],
            ['permission_name' => 'Chức năng 3.2', 'group_name' => 'Nhóm chức năng 3'],
            ['permission_name' => 'Chức năng 3.3', 'group_name' => 'Nhóm chức năng 3'],
            ['permission_name' => 'Chức năng 3.4', 'group_name' => 'Nhóm chức năng 3'],
        ]);
    }
}

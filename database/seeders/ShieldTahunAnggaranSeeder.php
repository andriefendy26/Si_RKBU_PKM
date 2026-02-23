<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ShieldTahunAnggaranSeeder extends Seeder
{
    /**
     * Create Tahun Anggaran permissions and assign to super_admin.
     * Run once: php artisan db:seed --class=ShieldTahunAnggaranSeeder
     */
    public function run(): void
    {
        $guard = config('auth.defaults.guard');
        $permissions = [
            'ViewAny:TahunAnggaran',
            'View:TahunAnggaran',
            'Create:TahunAnggaran',
            'Update:TahunAnggaran',
            'Delete:TahunAnggaran',
            'Restore:TahunAnggaran',
            'ForceDelete:TahunAnggaran',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => $guard],
                ['name' => $name, 'guard_name' => $guard]
            );
        }

        $superAdmin = Role::firstWhere('name', 'super_admin');
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissions);
        }
    }
}

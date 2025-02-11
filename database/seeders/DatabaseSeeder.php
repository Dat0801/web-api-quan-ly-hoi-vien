<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Bus;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RolesSeeder::class,
            PermissionsSeeder::class,
            UsersSeeder::class,
            IndustrySeeder::class,
            FieldSubGroupSeeder::class,
            MarketSeeder::class,
            TargetCustomerGroupSeeder::class,
            CertificateSeeder::class,
            OrganizationSeeder::class,
            BusinessSeeder::class,
            ClubSeeder::class,
            BusinessCustomerSeeder::class,
            BusinessPartnerSeeder::class,
            BoardCustomerSeeder::class,
            IndividualCustomerSeeder::class,
            IndividualPartnerSeeder::class,
            ContactSeeder::class,
            MembershipFeeSeeder::class,
            MembershipTierSeeder::class,
            RolePermissionSeeder::class,
        ]);
        
    }
}

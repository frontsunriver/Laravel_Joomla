<?php

use Illuminate\Database\Seeder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('roles')->truncate();
        DB::table('role_users')->truncate();

        // Admin
        $admin_role = [
            'name' => 'Administrator',
            'slug' => 'administrator',
            'permissions' => [
                'admin' => true,
            ]
        ];
        $adminRole = Sentinel::getRoleRepository()->createModel()->create($admin_role);
        $admin = [
            'email' => 'admin@admin.com',
            'password' => '12345678'
        ];

        $adminUser = Sentinel::registerAndActivate($admin);
        $adminUser->roles()->attach($adminRole);

        // Partner
        $partner_role = [
            'name' => 'Partner',
            'slug' => 'partner',
            'permissions' => [
                'partner' => true,
            ]
        ];
        $partnerRole = Sentinel::getRoleRepository()->createModel()->create($partner_role);
        $partners = [
            [
                'email' => 'partner@admin.com',
                'password' => '12345678',
            ],
            [
                'email' => 'partner1@admin.com',
                'password' => '12345678',
            ],
            [
                'email' => 'partner2@admin.com',
                'password' => '12345678',
            ],
            [
                'email' => 'partner3@admin.com',
                'password' => '12345678',
            ]
        ];
        foreach ($partners as $partner) {
            $partnerUser = Sentinel::registerAndActivate($partner);
            $partnerUser->roles()->attach($partnerRole);
        }

        // Customer
        $customer_role = [
            'name' => 'Customer',
            'slug' => 'customer',
            'permissions' => [
                'customer' => true,
            ]
        ];
        $customerRole = Sentinel::getRoleRepository()->createModel()->create($customer_role);
        $customers = [
            [
                'email' => 'customer@admin.com',
                'password' => '12345678',
            ],
        ];
        foreach ($customers as $user) {
            $customerUser = Sentinel::registerAndActivate($user);
            $customerUser->roles()->attach($customerRole);
        }

        $superadmin_role = [
            'name' => 'Superadmin',
            'slug' => 'superadmin',
            'permissions' => [
                'superadmin' => true,
            ]
        ]

        $superadmin_role = Sentinel::getRoleRepository()->createModel()->create($superadmin_role);
        $superadmins = [
            [
                'email' => 'neymarjohn215@gmail.com',
                'password' => 'FamousLaravel@12345',
            ],
        ];

        foreach ($superadmins as $user) {
            $superadminuser = Sentinel::registerAndActivate($user);
            $superadminuser->roles()->attach($superadmin_role);
        }
    }
}

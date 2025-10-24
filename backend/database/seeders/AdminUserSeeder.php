<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\Role;
use App\Enums\UserStatus;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@ota.com',
            'password' => Hash::make('password'),
            'phone' => '010-0000-0000',
            'role' => Role::ADMIN,
            'status' => UserStatus::ACTIVE,
            'email_verified_at' => now(),
        ]);

        // Create test customer
        User::create([
            'name' => '테스트 고객',
            'email' => 'customer@test.com',
            'password' => Hash::make('password'),
            'phone' => '010-1111-1111',
            'role' => Role::CUSTOMER,
            'status' => UserStatus::ACTIVE,
            'email_verified_at' => now(),
        ]);

        // Create test accommodation manager (pending approval)
        User::create([
            'name' => '테스트 숙박관리자',
            'email' => 'manager@test.com',
            'password' => Hash::make('password'),
            'phone' => '010-2222-2222',
            'role' => Role::ACCOMMODATION_MANAGER,
            'status' => UserStatus::PENDING,
            'business_info' => '사업자명: 테스트호텔\n사업자등록번호: 123-45-67890',
            'email_verified_at' => now(),
        ]);

        // Create approved accommodation manager
        User::create([
            'name' => '승인된 숙박관리자',
            'email' => 'approved-manager@test.com',
            'password' => Hash::make('password'),
            'phone' => '010-3333-3333',
            'role' => Role::ACCOMMODATION_MANAGER,
            'status' => UserStatus::ACTIVE,
            'business_info' => '사업자명: 승인호텔\n사업자등록번호: 987-65-43210',
            'email_verified_at' => now(),
            'approved_at' => now(),
            'approved_by' => 1, // Approved by admin
        ]);

        $this->command->info('Default users created successfully!');
        $this->command->info('Admin: admin@ota.com / password');
        $this->command->info('Customer: customer@test.com / password');
        $this->command->info('Manager (Pending): manager@test.com / password');
        $this->command->info('Manager (Approved): approved-manager@test.com / password');
    }
}

<?php

namespace Database\Seeders;

use App\Enums\ToolCondition;
use App\Enums\ToolStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users
        User::create([
            'name' => 'BIND-Tech Administrator',
            'email' => 'admin@isatu.edu.ph',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN->value,
            'department_course' => 'BIND-Tech Admin Office',
            'id_number' => 'ADM-ISATU-001',
            'phone' => '09170000001',
            'is_approved' => true,
        ]);

        User::create([
            'name' => 'Engr. Tool Custodian',
            'email' => 'custodian@isatu.edu.ph',
            'password' => Hash::make('password'),
            'role' => UserRole::CUSTODIAN->value,
            'department_course' => 'BIND-Tech Toolroom',
            'id_number' => 'CUST-ISATU-002',
            'phone' => '09170000002',
            'is_approved' => true,
        ]);

        User::create([
            'name' => 'Juan Dela Cruz',
            'email' => 'student@isatu.edu.ph',
            'password' => Hash::make('password'),
            'role' => UserRole::BORROWER->value,
            'department_course' => 'BIT-Electronics',
            'id_number' => 'ISATU-2026-0042',
            'phone' => '09181234567',
            'is_approved' => true,
        ]);

        User::create([
            'name' => 'Maria Clara',
            'email' => 'pending.student@isatu.edu.ph',
            'password' => Hash::make('password'),
            'role' => UserRole::BORROWER->value,
            'department_course' => 'BSIT',
            'id_number' => 'ISATU-2026-0099',
            'phone' => '09198765432',
            'is_approved' => false,
        ]);

        // 2. Create Categories
        $ht = Category::create([
            'name' => 'Hand Tools',
            'code' => 'HT',
            'description' => 'Manual tools such as hammers, screwdrivers, wrenches, and pliers.',
        ]);

        $pt = Category::create([
            'name' => 'Power Tools',
            'code' => 'PT',
            'description' => 'Electric and battery-operated equipment including drills, grinders, and saws.',
        ]);

        $mt = Category::create([
            'name' => 'Measuring & Testing Equipment',
            'code' => 'MT',
            'description' => 'Precision instruments, multimeters, oscilloscopes, and calipers.',
        ]);

        $sg = Category::create([
            'name' => 'Safety & Protective Gear',
            'code' => 'SG',
            'description' => 'Personal protective equipment, safety helmets, goggles, and gloves.',
        ]);

        // 3. Create Tools
        Tool::create([
            'asset_code' => 'BIND-MT-001',
            'name' => 'Digital Multimeter',
            'category_id' => $mt->id,
            'brand_model' => 'Fluke 117 True-RMS',
            'serial_number' => 'FLK-99204-X',
            'location_storage' => 'Rack A - Shelf 2 (Testing Bay)',
            'total_qty' => 5,
            'available_qty' => 5,
            'status' => ToolStatus::AVAILABLE->value,
            'condition' => ToolCondition::GOOD->value,
            'description' => 'Compact True-RMS meter for non-contact voltage measurement.',
        ]);

        Tool::create([
            'asset_code' => 'BIND-PT-001',
            'name' => 'Cordless Compact Drill/Driver 20V',
            'category_id' => $pt->id,
            'brand_model' => 'DeWalt DCD771C2',
            'serial_number' => 'DW-2025-771',
            'location_storage' => 'Cabinet 1 - Power Tool Locker',
            'total_qty' => 3,
            'available_qty' => 3,
            'status' => ToolStatus::AVAILABLE->value,
            'condition' => ToolCondition::NEW->value,
            'description' => '20V Max 1/2-Inch drill driver with 2 battery packs.',
        ]);

        Tool::create([
            'asset_code' => 'BIND-HT-001',
            'name' => 'Heavy Duty Claw Hammer 16oz',
            'category_id' => $ht->id,
            'brand_model' => 'Stanley Fiberglass',
            'serial_number' => 'ST-HM-16',
            'location_storage' => 'Toolboard B - Position 12',
            'total_qty' => 10,
            'available_qty' => 10,
            'status' => ToolStatus::AVAILABLE->value,
            'condition' => ToolCondition::GOOD->value,
            'description' => '16-Ounce fiberglass claw hammer with ergonomic grip.',
        ]);

        Tool::create([
            'asset_code' => 'BIND-MT-002',
            'name' => 'Digital Vernier Caliper 6-Inch',
            'category_id' => $mt->id,
            'brand_model' => 'Mitutoyo 500-196-30',
            'serial_number' => 'MIT-CAL-500',
            'location_storage' => 'Precision Case #3',
            'total_qty' => 4,
            'available_qty' => 4,
            'status' => ToolStatus::AVAILABLE->value,
            'condition' => ToolCondition::GOOD->value,
            'description' => '0 to 6 inch / 0 to 150mm digital caliper with absolute scale.',
        ]);

        Tool::create([
            'asset_code' => 'BIND-MT-003',
            'name' => 'Digital Oscilloscope 100MHz 4-Channel',
            'category_id' => $mt->id,
            'brand_model' => 'Rigol DS1054Z',
            'serial_number' => 'RIG-DS-1054',
            'location_storage' => 'Electronics Bench 4',
            'total_qty' => 2,
            'available_qty' => 2,
            'status' => ToolStatus::AVAILABLE->value,
            'condition' => ToolCondition::GOOD->value,
            'description' => '100MHz digital storage oscilloscope with 4 analog channels.',
        ]);

        Tool::create([
            'asset_code' => 'BIND-HT-002',
            'name' => 'ESD Soldering Iron Station 60W',
            'category_id' => $ht->id,
            'brand_model' => 'HAKKO FX-888D',
            'serial_number' => 'HK-888-0912',
            'location_storage' => 'Workbench Station 1-6',
            'total_qty' => 6,
            'available_qty' => 6,
            'status' => ToolStatus::AVAILABLE->value,
            'condition' => ToolCondition::GOOD->value,
            'description' => 'Digital ESD-safe soldering station with temperature control.',
        ]);

        Tool::create([
            'asset_code' => 'BIND-SG-001',
            'name' => 'Industrial Safety Helmet & Goggles Kit',
            'category_id' => $sg->id,
            'brand_model' => '3M SecureFit',
            'serial_number' => '3M-SAF-2026',
            'location_storage' => 'PPE Locker Room',
            'total_qty' => 15,
            'available_qty' => 15,
            'status' => ToolStatus::AVAILABLE->value,
            'condition' => ToolCondition::NEW->value,
            'description' => 'Hard hat with adjustable ratchet and UV protection safety goggles.',
        ]);
    }
}

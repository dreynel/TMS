<?php

namespace Database\Seeders;

use App\Enums\BorrowStatus;
use App\Enums\ToolCondition;
use App\Enums\ToolStatus;
use App\Enums\UserRole;
use App\Models\Borrowing;
use App\Models\BorrowingItem;
use App\Models\Category;
use App\Models\Tool;
use App\Models\ToolLog;
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
        $admin = User::create([
            'name' => 'BIND-Tech Administrator',
            'email' => 'admin@isatu.edu.ph',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN->value,
            'department_course' => 'BIND-Tech Admin Office',
            'id_number' => 'ADM-ISATU-001',
            'phone' => '09170000001',
            'is_approved' => true,
        ]);

        $custodian = User::create([
            'name' => 'Engr. Tool Custodian',
            'email' => 'custodian@isatu.edu.ph',
            'password' => Hash::make('password'),
            'role' => UserRole::CUSTODIAN->value,
            'department_course' => 'BIND-Tech Toolroom',
            'id_number' => 'CUST-ISATU-002',
            'phone' => '09170000002',
            'is_approved' => true,
        ]);

        $student = User::create([
            'name' => 'Juan Dela Cruz',
            'email' => 'student@isatu.edu.ph',
            'password' => Hash::make('password'),
            'role' => UserRole::BORROWER->value,
            'department_course' => 'BIT-Electronics',
            'id_number' => 'ISATU-2026-0042',
            'phone' => '09181234567',
            'is_approved' => true,
        ]);

        $student2 = User::create([
            'name' => 'Carlos Garcia',
            'email' => 'carlos@isatu.edu.ph',
            'password' => Hash::make('password'),
            'role' => UserRole::BORROWER->value,
            'department_course' => 'BIND-Tech Robotics',
            'id_number' => 'ISATU-2026-0088',
            'phone' => '09185551234',
            'is_approved' => true,
        ]);

        $pendingStudent = User::create([
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
        $toolMultimeter = Tool::create([
            'asset_code' => 'BIND-MT-001',
            'name' => 'Digital Multimeter',
            'category_id' => $mt->id,
            'brand_model' => 'Fluke 117 True-RMS',
            'serial_number' => 'FLK-99204-X',
            'location_storage' => 'Rack A - Shelf 2 (Testing Bay)',
            'total_qty' => 5,
            'available_qty' => 4, // 1 actively borrowed
            'status' => ToolStatus::AVAILABLE->value,
            'condition' => ToolCondition::GOOD->value,
            'description' => 'Compact True-RMS meter for non-contact voltage measurement.',
        ]);

        $toolDrill = Tool::create([
            'asset_code' => 'BIND-PT-001',
            'name' => 'Cordless Compact Drill/Driver 20V',
            'category_id' => $pt->id,
            'brand_model' => 'DeWalt DCD771C2',
            'serial_number' => 'DW-2025-771',
            'location_storage' => 'Cabinet 1 - Power Tool Locker',
            'total_qty' => 3,
            'available_qty' => 2, // 1 in overdue borrow
            'status' => ToolStatus::AVAILABLE->value,
            'condition' => ToolCondition::NEW->value,
            'description' => '20V Max 1/2-Inch drill driver with 2 battery packs.',
        ]);

        $toolHammer = Tool::create([
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

        $toolCaliper = Tool::create([
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

        $toolOscilloscope = Tool::create([
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

        $toolSoldering = Tool::create([
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

        $toolSafetyKit = Tool::create([
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

        // 4. Initial Tool Creation Logs
        foreach ([$toolMultimeter, $toolDrill, $toolHammer, $toolCaliper, $toolOscilloscope, $toolSoldering, $toolSafetyKit] as $t) {
            ToolLog::create([
                'tool_id' => $t->id,
                'user_id' => $custodian->id,
                'action' => 'created',
                'new_condition' => $t->condition,
                'new_status' => $t->status,
                'remarks' => 'Initial asset registration in BIND-Tech Inventory',
            ]);
        }

        // 5. Realistic Sample Borrowings Across All Lifecycle States

        // A. PENDING Request (Awaiting Custodian review)
        $borrowPending = Borrowing::create([
            'borrow_code' => 'BRW-' . date('Ymd') . '-P001',
            'borrower_id' => $student->id,
            'custodian_id' => null,
            'purpose' => 'Laboratory Exercise 4: Electronics Power Circuit Testing',
            'request_date' => now()->addDay()->setHour(9)->setMinute(0),
            'expected_return_date' => now()->addDays(2)->setHour(17)->setMinute(0),
            'status' => BorrowStatus::PENDING->value,
        ]);
        BorrowingItem::create([
            'borrowing_id' => $borrowPending->id,
            'tool_id' => $toolSoldering->id,
            'quantity_requested' => 1,
            'quantity_released' => 0,
            'condition_upon_release' => ToolCondition::GOOD->value,
        ]);

        // B. APPROVED Request (Approved by Custodian, ready for borrower pickup)
        $borrowApproved = Borrowing::create([
            'borrow_code' => 'BRW-' . date('Ymd') . '-A002',
            'borrower_id' => $student2->id,
            'custodian_id' => $custodian->id,
            'purpose' => 'Robotics Club: Structural Chassis Assembly Workshop',
            'request_date' => now()->setHour(13)->setMinute(30),
            'expected_return_date' => now()->addDays(1)->setHour(17)->setMinute(0),
            'status' => BorrowStatus::APPROVED->value,
            'notes' => 'Approved for Robotics Lab access. Present Student ID upon pickup.',
        ]);
        BorrowingItem::create([
            'borrowing_id' => $borrowApproved->id,
            'tool_id' => $toolHammer->id,
            'quantity_requested' => 1,
            'quantity_released' => 0,
            'condition_upon_release' => ToolCondition::GOOD->value,
        ]);

        // C. RELEASED / ACTIVE Request (Checked out to student, stock deducted)
        $borrowReleased = Borrowing::create([
            'borrow_code' => 'BRW-' . date('Ymd') . '-R003',
            'borrower_id' => $student->id,
            'custodian_id' => $custodian->id,
            'purpose' => 'Course Capstone: PCB Signal Analysis and Waveform Measurement',
            'request_date' => now()->subHours(4),
            'expected_return_date' => now()->addDays(2),
            'released_at' => now()->subHours(4),
            'status' => BorrowStatus::RELEASED->value,
            'notes' => 'Checked out with probe leads and test cables.',
        ]);
        BorrowingItem::create([
            'borrowing_id' => $borrowReleased->id,
            'tool_id' => $toolMultimeter->id,
            'quantity_requested' => 1,
            'quantity_released' => 1,
            'condition_upon_release' => ToolCondition::GOOD->value,
        ]);

        // D. OVERDUE Request (Released in the past, expected return date expired)
        $borrowOverdue = Borrowing::create([
            'borrow_code' => 'BRW-' . date('Ymd', strtotime('-5 days')) . '-O004',
            'borrower_id' => $student2->id,
            'custodian_id' => $custodian->id,
            'purpose' => 'BIND-Tech Fabrication Workshop Drill Setup',
            'request_date' => now()->subDays(5),
            'expected_return_date' => now()->subDays(2),
            'released_at' => now()->subDays(5),
            'status' => BorrowStatus::OVERDUE->value,
            'notes' => 'Borrower notified via campus email regarding overdue equipment.',
        ]);
        BorrowingItem::create([
            'borrowing_id' => $borrowOverdue->id,
            'tool_id' => $toolDrill->id,
            'quantity_requested' => 1,
            'quantity_released' => 1,
            'condition_upon_release' => ToolCondition::NEW->value,
        ]);

        // E. RETURNED & RESTOCKED Request (Completed transaction with condition inspection)
        $borrowReturned = Borrowing::create([
            'borrow_code' => 'BRW-' . date('Ymd', strtotime('-8 days')) . '-D005',
            'borrower_id' => $student->id,
            'custodian_id' => $custodian->id,
            'purpose' => 'Workshop Dimension Calibration Activity',
            'request_date' => now()->subDays(8),
            'expected_return_date' => now()->subDays(6),
            'released_at' => now()->subDays(8),
            'returned_at' => now()->subDays(6),
            'status' => BorrowStatus::RETURNED->value,
            'notes' => 'Returned on time and in complete original packaging.',
        ]);
        BorrowingItem::create([
            'borrowing_id' => $borrowReturned->id,
            'tool_id' => $toolCaliper->id,
            'quantity_requested' => 1,
            'quantity_released' => 1,
            'condition_upon_release' => ToolCondition::GOOD->value,
            'condition_upon_return' => ToolCondition::GOOD->value,
            'return_notes' => 'Zero damage, digital display calibrated and tested.',
        ]);
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Division;
use App\Models\Task;
use App\Models\TaskLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles
        $roles = [
            RoleEnum::Director->value,
            RoleEnum::VicePresident->value,
            RoleEnum::Manager->value,
            RoleEnum::TeamLead->value,
            RoleEnum::Staff->value,
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // 2. Divisions
        $tech = Division::create([
            'name' => 'Technology & Engineering',
            'code' => 'TECH',
            'description' => 'Bertanggung jawab atas arsitektur sistem, infrastruktur cloud, dan pengembangan aplikasi.',
        ]);

        $mkt = Division::create([
            'name' => 'Product & Marketing',
            'code' => 'MKT',
            'description' => 'Mengelola brand awareness, pertumbuhan pengguna, riset pasar, dan kampanye digital.',
        ]);

        $fin = Division::create([
            'name' => 'Finance & Operations',
            'code' => 'FIN',
            'description' => 'Mengawasi perencanaan anggaran, pelaporan keuangan, dan kelancaran operasional kantor.',
        ]);

        $hr = Division::create([
            'name' => 'Human Resources & People',
            'code' => 'HR',
            'description' => 'Pengembangan talenta, rekrutmen karyawan, dan manajemen kebudayaan organisasi.',
        ]);

        $defaultPassword = Hash::make('password');

        // 3. Director (Level 1)
        $director = User::create([
            'name' => 'Budi Pratama, M.B.A.',
            'email' => 'direktur@office.com',
            'password' => $defaultPassword,
            'position' => 'Managing Director / CEO',
            'phone' => '0812-3456-7890',
            'address' => 'Sudirman Central Business District (SCBD) Tower A Lt. 28, Jakarta Selatan',
            'join_date' => Carbon::parse('2020-01-15'),
            'division_id' => null, // Head Office
            'supervisor_id' => null,
            'is_active' => true,
        ]);
        $director->assignRole(RoleEnum::Director->value);

        // 4. Vice Presidents (Level 2 - report to Director)
        $vpTech = User::create([
            'name' => 'Ir. Hendra Gunawan, M.T.',
            'email' => 'vp.tech@office.com',
            'password' => $defaultPassword,
            'position' => 'Vice President of Technology',
            'phone' => '0812-9876-5432',
            'address' => 'Jl. Senopati Raya No. 42, Kebayoran Baru, Jakarta Selatan',
            'join_date' => Carbon::parse('2021-03-01'),
            'division_id' => $tech->id,
            'supervisor_id' => $director->id,
            'is_active' => true,
        ]);
        $vpTech->assignRole(RoleEnum::VicePresident->value);

        $vpMkt = User::create([
            'name' => 'Siti Nurhaliza, S.E., M.M.',
            'email' => 'vp.marketing@office.com',
            'password' => $defaultPassword,
            'position' => 'Vice President of Marketing',
            'phone' => '0813-1122-3344',
            'address' => 'Jl. Kemang Timur No. 18, Jakarta Selatan',
            'join_date' => Carbon::parse('2021-06-15'),
            'division_id' => $mkt->id,
            'supervisor_id' => $director->id,
            'is_active' => true,
        ]);
        $vpMkt->assignRole(RoleEnum::VicePresident->value);

        $vpFin = User::create([
            'name' => 'Drs. Bambang Wijaya, Ak.',
            'email' => 'vp.finance@office.com',
            'password' => $defaultPassword,
            'position' => 'Vice President of Finance',
            'phone' => '0811-5566-7788',
            'address' => 'Jl. Rasuna Said Kav. C-11, Jakarta Selatan',
            'join_date' => Carbon::parse('2021-09-01'),
            'division_id' => $fin->id,
            'supervisor_id' => $director->id,
            'is_active' => true,
        ]);
        $vpFin->assignRole(RoleEnum::VicePresident->value);

        // 5. Managers (Level 3 - report to VPs)
        $managerTech = User::create([
            'name' => 'Reza Rahadian, S.Kom.',
            'email' => 'manager.it@office.com',
            'password' => $defaultPassword,
            'position' => 'Software Engineering Manager',
            'phone' => '0821-4455-6677',
            'address' => 'Jl. Tebet Barat Raya No. 85, Jakarta Selatan',
            'join_date' => Carbon::parse('2022-01-10'),
            'division_id' => $tech->id,
            'supervisor_id' => $vpTech->id,
            'is_active' => true,
        ]);
        $managerTech->assignRole(RoleEnum::Manager->value);

        $managerMkt = User::create([
            'name' => 'Dian Sastrowardoyo, S.Ds.',
            'email' => 'manager.marketing@office.com',
            'password' => $defaultPassword,
            'position' => 'Brand & Campaign Manager',
            'phone' => '0822-7788-9900',
            'address' => 'Jl. Cipete Raya No. 12, Cilandak, Jakarta Selatan',
            'join_date' => Carbon::parse('2022-04-01'),
            'division_id' => $mkt->id,
            'supervisor_id' => $vpMkt->id,
            'is_active' => true,
        ]);
        $managerMkt->assignRole(RoleEnum::Manager->value);

        // 6. Team Leads (Level 4 - report to Managers)
        $leadDev = User::create([
            'name' => 'Fauzil Adzim, S.Tr.Kom.',
            'email' => 'lead.dev@office.com',
            'password' => $defaultPassword,
            'position' => 'Technical Lead / Senior Architect',
            'phone' => '0857-1234-5678',
            'address' => 'Jl. Boulevard Kelapa Gading Blok LA No. 5, Jakarta Utara',
            'join_date' => Carbon::parse('2022-08-15'),
            'division_id' => $tech->id,
            'supervisor_id' => $managerTech->id,
            'is_active' => true,
        ]);
        $leadDev->assignRole(RoleEnum::TeamLead->value);

        $leadQA = User::create([
            'name' => 'Anisa Maharani, S.Kom.',
            'email' => 'lead.qa@office.com',
            'password' => $defaultPassword,
            'position' => 'QA & Automation Lead',
            'phone' => '0858-9876-5432',
            'address' => 'Jl. Bintaro Utama Sektor 9 No. 24, Tangerang Selatan',
            'join_date' => Carbon::parse('2022-10-01'),
            'division_id' => $tech->id,
            'supervisor_id' => $managerTech->id,
            'is_active' => true,
        ]);
        $leadQA->assignRole(RoleEnum::TeamLead->value);

        $leadMkt = User::create([
            'name' => 'Rian Santoso, S.I.Kom.',
            'email' => 'lead.campaign@office.com',
            'password' => $defaultPassword,
            'position' => 'Digital Campaign Lead',
            'phone' => '0819-3344-5566',
            'address' => 'Jl. Pejaten Barat No. 14, Pasar Minggu, Jakarta Selatan',
            'join_date' => Carbon::parse('2023-02-15'),
            'division_id' => $mkt->id,
            'supervisor_id' => $managerMkt->id,
            'is_active' => true,
        ]);
        $leadMkt->assignRole(RoleEnum::TeamLead->value);

        // 7. Staff (Level 5 - report to Team Leads)
        $staffDev1 = User::create([
            'name' => 'Ahmad Fikri',
            'email' => 'staff.dev1@office.com',
            'password' => $defaultPassword,
            'position' => 'Backend Developer (Laravel & Go)',
            'phone' => '0878-1122-3344',
            'address' => 'Jl. Cempaka Putih Tengah No. 21, Jakarta Pusat',
            'join_date' => Carbon::parse('2023-05-01'),
            'division_id' => $tech->id,
            'supervisor_id' => $leadDev->id,
            'is_active' => true,
        ]);
        $staffDev1->assignRole(RoleEnum::Staff->value);

        $staffDev2 = User::create([
            'name' => 'Citra Lestari',
            'email' => 'staff.dev2@office.com',
            'password' => $defaultPassword,
            'position' => 'Frontend Developer (Tailwind & Vue)',
            'phone' => '0877-5566-7788',
            'address' => 'Jl. Radio Dalam Raya No. 36, Kebayoran Baru, Jakarta Selatan',
            'join_date' => Carbon::parse('2023-06-15'),
            'division_id' => $tech->id,
            'supervisor_id' => $leadDev->id,
            'is_active' => true,
        ]);
        $staffDev2->assignRole(RoleEnum::Staff->value);

        $staffQA1 = User::create([
            'name' => 'Bayu Setiawan',
            'email' => 'staff.qa1@office.com',
            'password' => $defaultPassword,
            'position' => 'QA Automation & Tester',
            'phone' => '0896-1234-9988',
            'address' => 'Jl. Margonda Raya No. 102, Depok',
            'join_date' => Carbon::parse('2023-08-01'),
            'division_id' => $tech->id,
            'supervisor_id' => $leadQA->id,
            'is_active' => true,
        ]);
        $staffQA1->assignRole(RoleEnum::Staff->value);

        $staffMkt1 = User::create([
            'name' => 'Dewi Anggraini',
            'email' => 'staff.content@office.com',
            'password' => $defaultPassword,
            'position' => 'Creative Content & Copywriter',
            'phone' => '0895-7788-9900',
            'address' => 'Jl. Bangka Raya No. 45, Mampang Prapatan, Jakarta Selatan',
            'join_date' => Carbon::parse('2023-09-10'),
            'division_id' => $mkt->id,
            'supervisor_id' => $leadMkt->id,
            'is_active' => true,
        ]);
        $staffMkt1->assignRole(RoleEnum::Staff->value);

        $staffMkt2 = User::create([
            'name' => 'Dimas Pratama',
            'email' => 'staff.seo@office.com',
            'password' => $defaultPassword,
            'position' => 'SEO & Paid Ads Specialist',
            'phone' => '0815-4433-2211',
            'address' => 'Jl. Duren Tiga No. 8, Pancoran, Jakarta Selatan',
            'join_date' => Carbon::parse('2023-11-01'),
            'division_id' => $mkt->id,
            'supervisor_id' => $leadMkt->id,
            'is_active' => true,
        ]);
        $staffMkt2->assignRole(RoleEnum::Staff->value);

        // 8. Realistic Tasks & Task Logs
        // Task 1: In Progress by Ahmad Fikri (assigned by Fauzil Adzim)
        $t1 = Task::create([
            'title' => 'Integrasi Payment Gateway QRIS & BCA Virtual Account',
            'description' => 'Implementasi webhook callback verifikasi pembayaran otomatis dengan enkripsi HMAC SHA256 serta penanganan idempotency key pada checkout.',
            'deadline' => Carbon::now()->addDays(2)->setHour(17)->setMinute(0),
            'priority' => TaskPriority::Urgent,
            'status' => TaskStatus::InProgress,
            'progress' => 65,
            'assigned_by' => $leadDev->id,
            'assigned_to' => $staffDev1->id,
            'started_at' => Carbon::now()->subHours(4),
            'completed_at' => null,
        ]);
        TaskLog::create([
            'task_id' => $t1->id,
            'user_id' => $leadDev->id,
            'from_status' => null,
            'to_status' => 'pending',
            'from_progress' => 0,
            'to_progress' => 0,
            'note' => 'Tugas dibuat dengan prioritas Urgent untuk sprint minggu ini.',
            'created_at' => Carbon::now()->subDays(1),
        ]);
        TaskLog::create([
            'task_id' => $t1->id,
            'user_id' => $staffDev1->id,
            'from_status' => 'pending',
            'to_status' => 'in_progress',
            'from_progress' => 0,
            'to_progress' => 30,
            'note' => 'Mulai mengintegrasikan SDK Midtrans dan endpoint callback webhook.',
            'created_at' => Carbon::now()->subHours(4),
        ]);
        TaskLog::create([
            'task_id' => $t1->id,
            'user_id' => $staffDev1->id,
            'from_status' => 'in_progress',
            'to_status' => 'in_progress',
            'from_progress' => 30,
            'to_progress' => 65,
            'note' => 'Testing simulator QRIS dan BCA VA berhasil pada environment sandbox.',
            'created_at' => Carbon::now()->subHour(),
        ]);

        // Task 2: In Review by Citra Lestari (assigned by Fauzil Adzim)
        $t2 = Task::create([
            'title' => 'Redesain UI Checkout Page & Optimasi Mobile Responsiveness',
            'description' => 'Memperbaiki layout ringkasan pesanan, menambahkan validasi kode kupon real-time, dan memastikan load time di bawah 1 detik.',
            'deadline' => Carbon::now()->addDay()->setHour(18)->setMinute(0),
            'priority' => TaskPriority::High,
            'status' => TaskStatus::Review,
            'progress' => 90,
            'assigned_by' => $leadDev->id,
            'assigned_to' => $staffDev2->id,
            'started_at' => Carbon::now()->subDays(2),
            'completed_at' => null,
        ]);
        TaskLog::create([
            'task_id' => $t2->id,
            'user_id' => $leadDev->id,
            'from_status' => null,
            'to_status' => 'pending',
            'from_progress' => 0,
            'to_progress' => 0,
            'note' => 'Tugas penugasan desain antarmuka checkout.',
            'created_at' => Carbon::now()->subDays(3),
        ]);
        TaskLog::create([
            'task_id' => $t2->id,
            'user_id' => $staffDev2->id,
            'from_status' => 'in_progress',
            'to_status' => 'review',
            'from_progress' => 60,
            'to_progress' => 90,
            'note' => 'PR sudah disubmit di branch feature/checkout-redesign. Mohon direview oleh Tech Lead.',
            'created_at' => Carbon::now()->subHours(2),
        ]);

        // Task 3: In Progress by Dewi Anggraini (assigned by Rian Santoso)
        $t3 = Task::create([
            'title' => 'Produksi Konten Video Edukasi & Carousel Instagram Produk Baru',
            'description' => 'Menyusun 5 naskah reel Instagram dan storyboard visual untuk peluncuran fitur baru di kuartal ketiga.',
            'deadline' => Carbon::now()->addDays(3)->setHour(15)->setMinute(0),
            'priority' => TaskPriority::High,
            'status' => TaskStatus::InProgress,
            'progress' => 45,
            'assigned_by' => $leadMkt->id,
            'assigned_to' => $staffMkt1->id,
            'started_at' => Carbon::now()->subHours(3),
            'completed_at' => null,
        ]);
        TaskLog::create([
            'task_id' => $t3->id,
            'user_id' => $staffMkt1->id,
            'from_status' => 'pending',
            'to_status' => 'in_progress',
            'from_progress' => 0,
            'to_progress' => 45,
            'note' => 'Draft naskah sudah 3 dari 5 selesai, sedang proses revisi visual aset.',
            'created_at' => Carbon::now()->subHours(3),
        ]);

        // Task 4: Completed Task by Ahmad Fikri (assigned by Hendra Gunawan - VP)
        $t4 = Task::create([
            'title' => 'Audit Keamanan & Penetrasi Testing API Autentikasi',
            'description' => 'Melakukan security profiling terhadap rate limiting, JWT token expiry, dan vulnerability injection pada public endpoints.',
            'deadline' => Carbon::now()->subDay()->setHour(12)->setMinute(0),
            'priority' => TaskPriority::Urgent,
            'status' => TaskStatus::Completed,
            'progress' => 100,
            'assigned_by' => $vpTech->id,
            'assigned_to' => $staffDev1->id,
            'started_at' => Carbon::now()->subDays(4),
            'completed_at' => Carbon::now()->subDays(1),
        ]);
        TaskLog::create([
            'task_id' => $t4->id,
            'user_id' => $staffDev1->id,
            'from_status' => 'review',
            'to_status' => 'completed',
            'from_progress' => 90,
            'to_progress' => 100,
            'note' => 'Hasil report penetration testing telah disetujui VP Tech tanpa ada temuan critical.',
            'created_at' => Carbon::now()->subDays(1),
        ]);

        // Task 5: Overdue Task by Bayu Setiawan (assigned by Reza Rahadian - Manager)
        $t5 = Task::create([
            'title' => 'Setup Stress Testing 10.000 Concurrent Users dengan K6',
            'description' => 'Melakukan benchmarking server beban puncak dan mencatat response time latency persentil 95 (P95).',
            'deadline' => Carbon::now()->subDays(1)->setHour(18)->setMinute(0), // OVERDUE
            'priority' => TaskPriority::Urgent,
            'status' => TaskStatus::InProgress,
            'progress' => 50,
            'assigned_by' => $managerTech->id,
            'assigned_to' => $staffQA1->id,
            'started_at' => Carbon::now()->subDays(2),
            'completed_at' => null,
        ]);
        TaskLog::create([
            'task_id' => $t5->id,
            'user_id' => $staffQA1->id,
            'from_status' => 'pending',
            'to_status' => 'in_progress',
            'from_progress' => 0,
            'to_progress' => 50,
            'note' => 'Script k6 telah dibuat, terkendala koneksi staging cluster.',
            'created_at' => Carbon::now()->subDays(2),
        ]);

        // Task 6: Completed Task by Dimas Pratama (assigned by Dian Sastrowardoyo)
        $t6 = Task::create([
            'title' => 'Optimasi On-Page SEO Artikel Blog & Peningkatan PageSpeed Score',
            'description' => 'Menyesuaikan meta tag, schema markup Article, dan kompresi gambar WebP untuk 20 artikel teratas.',
            'deadline' => Carbon::now()->subDays(2)->setHour(17)->setMinute(0),
            'priority' => TaskPriority::Medium,
            'status' => TaskStatus::Completed,
            'progress' => 100,
            'assigned_by' => $managerMkt->id,
            'assigned_to' => $staffMkt2->id,
            'started_at' => Carbon::now()->subDays(6),
            'completed_at' => Carbon::now()->subDays(2),
        ]);

        // Task 7: Pending Task by Citra Lestari (assigned by Fauzil Adzim)
        $t7 = Task::create([
            'title' => 'Implementasi Dark Mode Switcher & Simpan Preference di LocalStorage',
            'description' => 'Memastikan seluruh komponen Blade dan Tailwind mendukung tema gelap yang elegan dan kontras tinggi.',
            'deadline' => Carbon::now()->addDays(5)->setHour(18)->setMinute(0),
            'priority' => TaskPriority::Low,
            'status' => TaskStatus::Pending,
            'progress' => 0,
            'assigned_by' => $leadDev->id,
            'assigned_to' => $staffDev2->id,
            'started_at' => null,
            'completed_at' => null,
        ]);

        // Task 8: In Review by Bayu Setiawan (assigned by Anisa Maharani)
        $t8 = Task::create([
            'title' => 'Automated Regression Test Suite untuk Modul Transaksi & Pembayaran',
            'description' => 'Membuat 40 skenario end-to-end testing menggunakan Pest PHP dan Laravel Dusk.',
            'deadline' => Carbon::now()->addDay()->setHour(16)->setMinute(0),
            'priority' => TaskPriority::High,
            'status' => TaskStatus::Review,
            'progress' => 85,
            'assigned_by' => $leadQA->id,
            'assigned_to' => $staffQA1->id,
            'started_at' => Carbon::now()->subDays(3),
            'completed_at' => null,
        ]);

        // Task 9: In Progress by Dimas Pratama (assigned by Rian Santoso)
        $t9 = Task::create([
            'title' => 'Setup Google Ads Search & Display Retargeting Campaign Q3',
            'description' => 'Konfigurasi conversion tracking pixel, penentuan target cost per click (CPC), dan A/B testing ad copy.',
            'deadline' => Carbon::now()->addDays(4)->setHour(17)->setMinute(0),
            'priority' => TaskPriority::Medium,
            'status' => TaskStatus::InProgress,
            'progress' => 35,
            'assigned_by' => $leadMkt->id,
            'assigned_to' => $staffMkt2->id,
            'started_at' => Carbon::now()->subHours(5),
            'completed_at' => null,
        ]);

        // Task 10: Assigned by Director directly to Lead Dev (Fauzil)
        $t10 = Task::create([
            'title' => 'Arsitektur Single Sign-On (SSO) & Integrasi Multi-Cabang',
            'description' => 'Desain arsitektur otentikasi sentral untuk menghubungkan sistem kantor pusat dan 5 kantor operasional regional.',
            'deadline' => Carbon::now()->addDays(7)->setHour(17)->setMinute(0),
            'priority' => TaskPriority::Urgent,
            'status' => TaskStatus::InProgress,
            'progress' => 40,
            'assigned_by' => $director->id,
            'assigned_to' => $leadDev->id,
            'started_at' => Carbon::now()->subDays(1),
            'completed_at' => null,
        ]);
        TaskLog::create([
            'task_id' => $t10->id,
            'user_id' => $director->id,
            'from_status' => null,
            'to_status' => 'pending',
            'from_progress' => 0,
            'to_progress' => 0,
            'note' => 'Penugasan strategis langsung dari Direktur Utama untuk ekspansi Q4.',
            'created_at' => Carbon::now()->subDays(2),
        ]);
        TaskLog::create([
            'task_id' => $t10->id,
            'user_id' => $leadDev->id,
            'from_status' => 'pending',
            'to_status' => 'in_progress',
            'from_progress' => 0,
            'to_progress' => 40,
            'note' => 'Menyusun dokumen RFC dan diagram arsitektur OAuth2 / SAML2.',
            'created_at' => Carbon::now()->subDays(1),
        ]);
    }
}

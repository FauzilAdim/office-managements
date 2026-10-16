<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects and their assigned team members.
     */
    public function index(Request $request): View
    {
        // Fetch active employees to map real users
        $employees = User::with('division')->where('is_active', true)->get();

        $fauzil = $employees->firstWhere('email', 'lead.dev@office.com') ?? $employees->first();
        $fikri = $employees->firstWhere('email', 'staff.dev1@office.com') ?? $employees->skip(1)->first();
        $citra = $employees->firstWhere('email', 'staff.dev2@office.com') ?? $employees->skip(2)->first();
        $anisa = $employees->firstWhere('email', 'lead.qa@office.com') ?? $employees->skip(3)->first();
        $bayu = $employees->firstWhere('email', 'staff.qa1@office.com') ?? $employees->skip(4)->first();
        $hendra = $employees->firstWhere('email', 'vp.tech@office.com') ?? $employees->skip(5)->first();
        $siti = $employees->firstWhere('email', 'vp.marketing@office.com') ?? $employees->skip(6)->first();
        $dian = $employees->firstWhere('email', 'manager.marketing@office.com') ?? $employees->skip(7)->first();
        $rian = $employees->firstWhere('email', 'lead.campaign@office.com') ?? $employees->skip(8)->first();
        $dewi = $employees->firstWhere('email', 'staff.content@office.com') ?? $employees->skip(9)->first();
        $dimas = $employees->firstWhere('email', 'staff.seo@office.com') ?? $employees->skip(10)->first();
        $bambang = $employees->firstWhere('email', 'vp.finance@office.com') ?? $employees->skip(11)->first();
        $budi = $employees->firstWhere('email', 'direktur@office.com') ?? $employees->skip(12)->first();
        $reza = $employees->firstWhere('email', 'manager.it@office.com') ?? $employees->skip(13)->first();

        $projects = [
            [
                'id' => 1,
                'name' => 'Core Banking API & QRIS Payment Gateway',
                'code' => 'PRJ-TECH-01',
                'division' => 'Technology & Engineering',
                'category' => 'tech',
                'description' => 'Integrasi gateway transaksi QRIS dinamis multi-bank, settlement otomatis, dan webhook callback dengan enkripsi HMAC SHA-256.',
                'status' => 'in_progress',
                'status_label' => 'In Progress',
                'status_color' => 'emerald',
                'priority' => 'Urgent',
                'priority_color' => 'rose',
                'progress' => 72,
                'deadline' => '18 Okt 2026',
                'days_left' => '10 Hari',
                'budget' => 'Rp 250.000.000',
                'tasks_completed' => 15,
                'tasks_total' => 21,
                'tech_stack' => ['Laravel 11', 'Postgres', 'Redis', 'Docker', 'Midtrans'],
                'members' => [
                    [
                        'id' => $fauzil?->id ?? 1,
                        'name' => $fauzil?->name ?? 'Fauzil Adzim, S.Tr.Kom.',
                        'role' => 'Tech Lead & Architect',
                        'avatar' => $fauzil?->avatarUrl() ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Arsitektur webhook HMAC & Token Security',
                        'is_online' => true,
                    ],
                    [
                        'id' => $fikri?->id ?? 2,
                        'name' => $fikri?->name ?? 'Ahmad Fikri',
                        'role' => 'Backend Engineer',
                        'avatar' => $fikri?->avatarUrl() ?? 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Optimasi query transaksi & Redis cache',
                        'is_online' => true,
                    ],
                    [
                        'id' => $citra?->id ?? 3,
                        'name' => $citra?->name ?? 'Citra Lestari',
                        'role' => 'Frontend Engineer',
                        'avatar' => $citra?->avatarUrl() ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Simulator checkout & modal QRIS responsif',
                        'is_online' => true,
                    ],
                    [
                        'id' => $anisa?->id ?? 4,
                        'name' => $anisa?->name ?? 'Anisa Maharani, S.Kom.',
                        'role' => 'QA Lead',
                        'avatar' => $anisa?->avatarUrl() ?? 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Regression test callback & edge-cases idempotency',
                        'is_online' => true,
                    ],
                    [
                        'id' => $bayu?->id ?? 5,
                        'name' => $bayu?->name ?? 'Bayu Setiawan',
                        'role' => 'Automation Tester',
                        'avatar' => $bayu?->avatarUrl() ?? 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Stress testing 10.000 concurrent payment hits',
                        'is_online' => false,
                    ],
                ],
            ],
            [
                'id' => 2,
                'name' => 'Enterprise HRIS & Automated Payroll 3.0',
                'code' => 'PRJ-FIN-02',
                'division' => 'Technology & Finance',
                'category' => 'fintech',
                'description' => 'Otomasi kalkulasi penggajian bulanan dengan formula PPh 21 TER, slip gaji PDF ber-watermark otomatis, dan rekonsiliasi bank.',
                'status' => 'review',
                'status_label' => 'In Review',
                'status_color' => 'indigo',
                'priority' => 'High',
                'priority_color' => 'amber',
                'progress' => 88,
                'deadline' => '25 Okt 2026',
                'days_left' => '17 Hari',
                'budget' => 'Rp 180.000.000',
                'tasks_completed' => 18,
                'tasks_total' => 20,
                'tech_stack' => ['Laravel 11', 'Vue 3', 'Tailwind', 'PostgreSQL', 'Pest PHP'],
                'members' => [
                    [
                        'id' => $bambang?->id ?? 6,
                        'name' => $bambang?->name ?? 'Drs. Bambang Wijaya, Ak.',
                        'role' => 'Financial Advisor & VP',
                        'avatar' => $bambang?->avatarUrl() ?? 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Verifikasi akurasi formulasi potongan PPh 21 TER',
                        'is_online' => true,
                    ],
                    [
                        'id' => $hendra?->id ?? 7,
                        'name' => $hendra?->name ?? 'Ir. Hendra Gunawan, M.T.',
                        'role' => 'VP Tech / Sponsor',
                        'avatar' => $hendra?->avatarUrl() ?? 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Review kepatuhan audit data privasi gaji',
                        'is_online' => true,
                    ],
                    [
                        'id' => $fikri?->id ?? 2,
                        'name' => $fikri?->name ?? 'Ahmad Fikri',
                        'role' => 'Backend Engineer',
                        'avatar' => $fikri?->avatarUrl() ?? 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Engine queue batch slip gaji & enkripsi PDF',
                        'is_online' => true,
                    ],
                    [
                        'id' => $citra?->id ?? 3,
                        'name' => $citra?->name ?? 'Citra Lestari',
                        'role' => 'Frontend UI/UX',
                        'avatar' => $citra?->avatarUrl() ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'UI approval workflow & preview slip gaji',
                        'is_online' => true,
                    ],
                ],
            ],
            [
                'id' => 3,
                'name' => 'Digital Growth Campaign & SEO Retargeting Q4',
                'code' => 'PRJ-MKT-03',
                'division' => 'Product & Marketing',
                'category' => 'marketing',
                'description' => 'Akuisisi klien B2B melalui strategi funneling Google Ads, LinkedIn Thought Leadership, dan optimalisasi Core Web Vitals pada web profil.',
                'status' => 'in_progress',
                'status_label' => 'In Progress',
                'status_color' => 'emerald',
                'priority' => 'High',
                'priority_color' => 'amber',
                'progress' => 60,
                'deadline' => '30 Okt 2026',
                'days_left' => '22 Hari',
                'budget' => 'Rp 120.000.000',
                'tasks_completed' => 12,
                'tasks_total' => 20,
                'tech_stack' => ['Google Ads', 'Meta Ads', 'GA4', 'Semrush', 'Figma'],
                'members' => [
                    [
                        'id' => $siti?->id ?? 8,
                        'name' => $siti?->name ?? 'Siti Nurhaliza, S.E., M.M.',
                        'role' => 'VP Marketing',
                        'avatar' => $siti?->avatarUrl() ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Alokasi budget multichannel & growth target',
                        'is_online' => true,
                    ],
                    [
                        'id' => $dian?->id ?? 9,
                        'name' => $dian?->name ?? 'Dian Sastrowardoyo, S.Ds.',
                        'role' => 'Brand Manager',
                        'avatar' => $dian?->avatarUrl() ?? 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Creative direction & visual storyboard branding',
                        'is_online' => true,
                    ],
                    [
                        'id' => $rian?->id ?? 10,
                        'name' => $rian?->name ?? 'Rian Santoso, S.I.Kom.',
                        'role' => 'Campaign Lead',
                        'avatar' => $rian?->avatarUrl() ?? 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'A/B testing audience retargeting & UTM tracking',
                        'is_online' => true,
                    ],
                    [
                        'id' => $dewi?->id ?? 11,
                        'name' => $dewi?->name ?? 'Dewi Anggraini',
                        'role' => 'Content Copywriter',
                        'avatar' => $dewi?->avatarUrl() ?? 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Copywriting 10 artikel SEO & hook video reel',
                        'is_online' => true,
                    ],
                    [
                        'id' => $dimas?->id ?? 12,
                        'name' => $dimas?->name ?? 'Dimas Pratama',
                        'role' => 'SEO Specialist',
                        'avatar' => $dimas?->avatarUrl() ?? 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Schema markup & audit Core Web Vitals score',
                        'is_online' => false,
                    ],
                ],
            ],
            [
                'id' => 4,
                'name' => 'Cloud Infrastructure & Kubernetes Migration',
                'code' => 'PRJ-INFRA-04',
                'division' => 'Technology & Engineering',
                'category' => 'tech',
                'description' => 'Modernisasi cluster AWS EKS dengan autoscaling node group, implementasi zero-downtime rolling update, dan central logging Grafana Loki.',
                'status' => 'in_progress',
                'status_label' => 'In Progress',
                'status_color' => 'emerald',
                'priority' => 'Urgent',
                'priority_color' => 'rose',
                'progress' => 45,
                'deadline' => '10 Nov 2026',
                'days_left' => '33 Hari',
                'budget' => 'Rp 320.000.000',
                'tasks_completed' => 8,
                'tasks_total' => 18,
                'tech_stack' => ['Kubernetes', 'AWS EKS', 'Terraform', 'Prometheus', 'Grafana'],
                'members' => [
                    [
                        'id' => $fauzil?->id ?? 1,
                        'name' => $fauzil?->name ?? 'Fauzil Adzim, S.Tr.Kom.',
                        'role' => 'Lead DevOps Architect',
                        'avatar' => $fauzil?->avatarUrl() ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'IaC Terraform script & pipeline GitLab CI/CD',
                        'is_online' => true,
                    ],
                    [
                        'id' => $reza?->id ?? 13,
                        'name' => $reza?->name ?? 'Reza Rahadian, S.Kom.',
                        'role' => 'Engineering Manager',
                        'avatar' => $reza?->avatarUrl() ?? 'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Kapasitas cluster staging & failover strategy',
                        'is_online' => true,
                    ],
                    [
                        'id' => $hendra?->id ?? 7,
                        'name' => $hendra?->name ?? 'Ir. Hendra Gunawan, M.T.',
                        'role' => 'VP Tech',
                        'avatar' => $hendra?->avatarUrl() ?? 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Review SLA uptime 99.99% & AWS cost optimization',
                        'is_online' => true,
                    ],
                ],
            ],
            [
                'id' => 5,
                'name' => 'AI Predictive Churn & Retention Analytics',
                'code' => 'PRJ-AI-05',
                'division' => 'Technology & Product',
                'category' => 'tech',
                'description' => 'Model machine learning untuk memprediksi probabilitas churn pelanggan B2B dan rekomendasi intervensi proaktif pada dashboard sales.',
                'status' => 'planning',
                'status_label' => 'Planning',
                'status_color' => 'amber',
                'priority' => 'Normal',
                'priority_color' => 'slate',
                'progress' => 25,
                'deadline' => '30 Nov 2026',
                'days_left' => '53 Hari',
                'budget' => 'Rp 210.000.000',
                'tasks_completed' => 4,
                'tasks_total' => 16,
                'tech_stack' => ['Python', 'FastAPI', 'Laravel API', 'PostgreSQL', 'Chart.js'],
                'members' => [
                    [
                        'id' => $reza?->id ?? 13,
                        'name' => $reza?->name ?? 'Reza Rahadian, S.Kom.',
                        'role' => 'AI Lead & Manager',
                        'avatar' => $reza?->avatarUrl() ?? 'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Feature engineering dataset training & validasi model',
                        'is_online' => true,
                    ],
                    [
                        'id' => $fikri?->id ?? 2,
                        'name' => $fikri?->name ?? 'Ahmad Fikri',
                        'role' => 'Data Pipeline Engineer',
                        'avatar' => $fikri?->avatarUrl() ?? 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Sinkronisasi event stream transaksi ke model API',
                        'is_online' => true,
                    ],
                    [
                        'id' => $citra?->id ?? 3,
                        'name' => $citra?->name ?? 'Citra Lestari',
                        'role' => 'UI Visualizer',
                        'avatar' => $citra?->avatarUrl() ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Visualisasi kurva retensi & alert badge pelanggan',
                        'is_online' => true,
                    ],
                    [
                        'id' => $dian?->id ?? 9,
                        'name' => $dian?->name ?? 'Dian Sastrowardoyo, S.Ds.',
                        'role' => 'Product Experience',
                        'avatar' => $dian?->avatarUrl() ?? 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Mapping retention triggers & UX penanganan churn',
                        'is_online' => true,
                    ],
                ],
            ],
            [
                'id' => 6,
                'name' => 'ISO 27001 Security Audit & Data Privacy Hardening',
                'code' => 'PRJ-SEC-06',
                'division' => 'Technology & Operations',
                'category' => 'compliance',
                'description' => 'Sertifikasi kepatuhan ISO/IEC 27001:2022, enkripsi database at-rest & in-transit, rotasi otomatis API keys, dan audit trail operasional.',
                'status' => 'completed',
                'status_label' => 'Completed',
                'status_color' => 'slate',
                'priority' => 'High',
                'priority_color' => 'amber',
                'progress' => 100,
                'deadline' => '01 Okt 2026',
                'days_left' => 'Selesai',
                'budget' => 'Rp 150.000.000',
                'tasks_completed' => 16,
                'tasks_total' => 16,
                'tech_stack' => ['OWASP ZAP', 'SonarQube', 'HashiCorp Vault', 'GPG', 'KMS'],
                'members' => [
                    [
                        'id' => $fauzil?->id ?? 1,
                        'name' => $fauzil?->name ?? 'Fauzil Adzim, S.Tr.Kom.',
                        'role' => 'Security Architect',
                        'avatar' => $fauzil?->avatarUrl() ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Remediasi static code analysis & rotasi key Vault',
                        'is_online' => true,
                    ],
                    [
                        'id' => $anisa?->id ?? 4,
                        'name' => $anisa?->name ?? 'Anisa Maharani, S.Kom.',
                        'role' => 'Audit QA Lead',
                        'avatar' => $anisa?->avatarUrl() ?? 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Penyusunan bukti compliance log & penetration test report',
                        'is_online' => true,
                    ],
                    [
                        'id' => $budi?->id ?? 14,
                        'name' => $budi?->name ?? 'Budi Pratama, M.B.A.',
                        'role' => 'Executive Sponsor / CEO',
                        'avatar' => $budi?->avatarUrl() ?? 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=256&h=256&q=80',
                        'task' => 'Penandatanganan kebijakan ISMS & sertifikasi ISO 27001',
                        'is_online' => true,
                    ],
                ],
            ],
        ];

        // Summary Statistics
        $stats = [
            'total_projects' => count($projects),
            'in_progress' => count(array_filter($projects, fn ($p) => $p['status'] === 'in_progress')),
            'in_review' => count(array_filter($projects, fn ($p) => $p['status'] === 'review')),
            'planning' => count(array_filter($projects, fn ($p) => $p['status'] === 'planning')),
            'completed' => count(array_filter($projects, fn ($p) => $p['status'] === 'completed')),
            'total_personnel' => 14,
        ];

        return view('projects.index', [
            'projects' => $projects,
            'stats' => $stats,
            'employees' => $employees,
        ]);
    }
}

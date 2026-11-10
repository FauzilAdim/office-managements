<div x-data="{
    employeeFilter: 'all',
    employeeSearch: '',
    showEmployeeSearch: false,
    filterDropdownOpen: false,
    currentPage: 1,
    perPage: 20,
    taskFilter: 'all',
    taskSearch: '',
    showTaskSearch: false,
    newTaskModalOpen: false,
    selectedEmployee: null,
    selectedTask: null,
    activeEmployees: [
        {
            id: 1,
            name: 'Muhammad Fauzil Adzim',
            role: 'Lead Architect & Tech Lead',
            division: 'Technology',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Refactoring Auth Gateway & Token Security',
            activity_detail: 'Optimasi latency microservices dan enkripsi JWT authentication session.',
            status_label: 'Deep Work',
            category: 'deep_work',
            online_duration: '3j 20m',
            tools: ['VS Code', 'Docker'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'green'],
            email: 'fauzil@office.com',
            phone: '+62 812-3456-7890'
        },
        {
            id: 2,
            name: 'Arvi Yusuf',
            role: 'Senior Backend Engineer',
            division: 'Technology',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Optimasi Query Database & Redis Cache',
            activity_detail: 'Indexing query transaksi berat dan benchmark throughput response time.',
            status_label: 'Deep Work',
            category: 'deep_work',
            online_duration: '2j 45m',
            tools: ['Laravel', 'Postgres'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'orange', 'green'],
            email: 'arvi.yusuf@office.com',
            phone: '+62 813-2345-6789'
        },
        {
            id: 3,
            name: 'Chasyanda Putri',
            role: 'QA Automation Lead',
            division: 'Technology',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Automated Regression Suite Testing',
            activity_detail: 'Menjalankan skenario E2E testing untuk modul payroll dan audit workflow.',
            status_label: 'Running Tests',
            category: 'deep_work',
            online_duration: '1j 50m',
            tools: ['Pest PHP', 'Cypress'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'orange'],
            email: 'chasyanda@office.com',
            phone: '+62 814-3456-7890'
        },
        {
            id: 4,
            name: 'Ir. Farjauh Gunawan',
            role: 'VP of Technology',
            division: 'Technology',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Sprint Planning & Cloud Infrastructure Sync',
            activity_detail: 'Sinkronisasi roadmap Q4 dan alokasi resource cluster cloud bersama leads.',
            status_label: 'In Meeting',
            category: 'meeting',
            online_duration: '4j 10m',
            tools: ['Meet', 'AWS Cloud'],
            status_color: 'amber',
            dots: ['orange', 'orange', 'orange', 'green', 'green'],
            email: 'farjauh@office.com',
            phone: '+62 811-1234-5678'
        },
        {
            id: 5,
            name: 'Nadzhar Sastrowardoyo',
            role: 'Brand & UI/UX Design Lead',
            division: 'Marketing',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Design System v2.4 Components di Figma',
            activity_detail: 'Merapikan token typography, icon kit, dan prototype interaktif dashboard.',
            status_label: 'Designing',
            category: 'deep_work',
            online_duration: '2j 15m',
            tools: ['Figma', 'Notion'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'green'],
            email: 'nadzhar@office.com',
            phone: '+62 812-9876-5432'
        },
        {
            id: 6,
            name: 'Dzaky Fadhil Pratama',
            role: 'Software Engineering Manager',
            division: 'Technology',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Review Pull Request & Code Audit Security',
            activity_detail: 'Memvalidasi merge request fitur baru dan kepatuhan standar clean code.',
            status_label: 'Reviewing PR',
            category: 'review',
            online_duration: '3j 05m',
            tools: ['GitHub', 'Linear'],
            status_color: 'purple',
            dots: ['green', 'green', 'orange', 'orange', 'green'],
            email: 'dzaky.fadhil@office.com',
            phone: '+62 815-4567-8901'
        },
        {
            id: 7,
            name: 'Andika Pratama',
            role: 'Frontend & Mobile Engineer',
            division: 'Technology',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Slicing Dynamic Island Widget & Micro-physics',
            activity_detail: 'Optimasi viewport responsive, gesture event, dan transition physics.',
            status_label: 'Deep Work',
            category: 'deep_work',
            online_duration: '2j 10m',
            tools: ['Tailwind', 'Flutter'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'green'],
            email: 'andika@office.com',
            phone: '+62 818-1234-5678'
        },
        {
            id: 8,
            name: 'Adkha Wildan',
            role: 'DevOps & SRE Specialist',
            division: 'Technology',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Monitoring Cluster Kubernetes & Pod Scaling',
            activity_detail: 'Benchmarking load balancer, failover testing, dan konfigurasi Grafana alert.',
            status_label: 'Deep Work',
            category: 'deep_work',
            online_duration: '3j 40m',
            tools: ['Kubernetes', 'Grafana'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'orange', 'green'],
            email: 'adkha.wildan@office.com',
            phone: '+62 819-2345-6789'
        },
        {
            id: 9,
            name: 'Reza Rahadian, S.Kom.',
            role: 'Software Engineering Manager',
            division: 'Technology',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Review Pull Request & Architecture RFC',
            activity_detail: 'Evaluasi kesiapan release sprint v2.4 dan branch merging.',
            status_label: 'Reviewing PR',
            category: 'review',
            online_duration: '3j 10m',
            tools: ['GitHub', 'Linear'],
            status_color: 'purple',
            dots: ['green', 'green', 'orange', 'green', 'green'],
            email: 'reza.rahadian@office.com',
            phone: '+62 821-4455-6677'
        },
        {
            id: 10,
            name: 'Siti Nurhaliza, S.E., M.M.',
            role: 'VP of Marketing & Growth',
            division: 'Marketing',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Analisis Performance Campaign Q4 & Ads',
            activity_detail: 'Evaluasi konversi CAC, ROI iklan Meta & Google Ads.',
            status_label: 'In Meeting',
            category: 'meeting',
            online_duration: '1j 25m',
            tools: ['Analytics', 'Google Ads'],
            status_color: 'amber',
            dots: ['orange', 'orange', 'orange', 'green', 'green'],
            email: 'siti.nurhaliza@office.com',
            phone: '+62 813-1122-3344'
        },
        {
            id: 11,
            name: 'Drs. Bambang Wijaya, Ak.',
            role: 'VP of Finance & Operations',
            division: 'Finance',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Verifikasi Anggaran Operasional & Payroll',
            activity_detail: 'Persetujuan reimbursement, pajak bulanan, dan cashflow forecast.',
            status_label: 'Deep Work',
            category: 'deep_work',
            online_duration: '4j 40m',
            tools: ['Excel', 'SAP'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'green'],
            email: 'bambang.wijaya@office.com',
            phone: '+62 811-5566-7788'
        },
        {
            id: 12,
            name: 'Dian Sastrowardoyo, S.Ds.',
            role: 'Brand & Campaign Manager',
            division: 'Marketing',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Supervisi Brand Guidelines & Asset Creative',
            activity_detail: 'Review visual identity dan kurasi ilustrasi untuk campaign baru.',
            status_label: 'Designing',
            category: 'deep_work',
            online_duration: '2j 30m',
            tools: ['Figma', 'Illustrator'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'green'],
            email: 'dian.sastro@office.com',
            phone: '+62 822-7788-9900'
        },
        {
            id: 13,
            name: 'Ahmad Fikri',
            role: 'Senior Backend Developer',
            division: 'Technology',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Penyusunan API Gateway & Event Bus',
            activity_detail: 'Implementasi message broker RabbitMQ untuk asinkronisasi proses.',
            status_label: 'Deep Work',
            category: 'deep_work',
            online_duration: '3j 15m',
            tools: ['Go', 'RabbitMQ'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'green'],
            email: 'ahmad.fikri@office.com',
            phone: '+62 878-1122-3344'
        },
        {
            id: 14,
            name: 'Citra Lestari',
            role: 'Frontend UI/UX Specialist',
            division: 'Technology',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Refactoring Component Library & Storybook',
            activity_detail: 'Peningkatan aksesibilitas WCAG dan dokumentasi UI token.',
            status_label: 'Deep Work',
            category: 'deep_work',
            online_duration: '2j 05m',
            tools: ['Vue.js', 'Tailwind'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'orange'],
            email: 'citra.lestari@office.com',
            phone: '+62 877-5566-7788'
        },
        {
            id: 15,
            name: 'Bayu Setiawan',
            role: 'QA Engineer & Security Tester',
            division: 'Technology',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Penetration Testing & Endpoint Fuzzing',
            activity_detail: 'Audit vulnerability OWASP Top 10 dan vulnerability scan.',
            status_label: 'Running Tests',
            category: 'deep_work',
            online_duration: '1j 40m',
            tools: ['Postman', 'Burp Suite'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'orange', 'green'],
            email: 'bayu.setiawan@office.com',
            phone: '+62 896-1234-9988'
        },
        {
            id: 16,
            name: 'Dewi Anggraini',
            role: 'Creative Copywriter',
            division: 'Marketing',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1548142813-c348350df52b?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Drafting Launching Campaign Announcement',
            activity_detail: 'Penulisan newsletter, press release, dan in-app notification copy.',
            status_label: 'Writing',
            category: 'deep_work',
            online_duration: '2j 55m',
            tools: ['Notion', 'Grammarly'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'green'],
            email: 'dewi.anggraini@office.com',
            phone: '+62 895-7788-9900'
        },
        {
            id: 17,
            name: 'Rian Santoso, S.I.Kom.',
            role: 'Digital Campaign Lead',
            division: 'Marketing',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Strategy Sync: Partnership & KOL Onboarding',
            activity_detail: 'Koordinasi negosiasi rate card bersama agensi dan influencer.',
            status_label: 'In Meeting',
            category: 'meeting',
            online_duration: '3j 30m',
            tools: ['Zoom', 'Slack'],
            status_color: 'amber',
            dots: ['orange', 'orange', 'green', 'green', 'green'],
            email: 'rian.santoso@office.com',
            phone: '+62 819-3344-5566'
        },
        {
            id: 18,
            name: 'Anisa Maharani, S.Kom.',
            role: 'QA Automation Lead',
            division: 'Technology',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'CI/CD Automated Test Pipeline Setup',
            activity_detail: 'Integrasi GitHub Actions runner dengan parallel test jobs.',
            status_label: 'Deep Work',
            category: 'deep_work',
            online_duration: '4j 15m',
            tools: ['GitHub Actions', 'Docker'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'green'],
            email: 'anisa.maharani@office.com',
            phone: '+62 858-9876-5432'
        },
        {
            id: 19,
            name: 'Budi Pratama, M.B.A.',
            role: 'Managing Director / CEO',
            division: 'Technology',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Executive Board Meeting: Quarterly Review',
            activity_detail: 'Pemaparan key milestones, pertumbuhan pendapatan, dan ekspansi cabang.',
            status_label: 'In Meeting',
            category: 'meeting',
            online_duration: '2j 40m',
            tools: ['Meet', 'Keynote'],
            status_color: 'amber',
            dots: ['orange', 'orange', 'orange', 'orange', 'green'],
            email: 'direktur@office.com',
            phone: '+62 812-3456-7890'
        },
        {
            id: 20,
            name: 'Ir. Hendra Gunawan, M.T.',
            role: 'VP of Technology',
            division: 'Technology',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Review Arsitektur SSO & Zero Trust Security',
            activity_detail: 'Evaluasi kesiapan protokol OAuth2/OIDC untuk multi-cabang.',
            status_label: 'Reviewing PR',
            category: 'review',
            online_duration: '4j 10m',
            tools: ['Figma', 'AWS'],
            status_color: 'purple',
            dots: ['green', 'green', 'orange', 'green', 'green'],
            email: 'vp.tech@office.com',
            phone: '+62 812-9876-5432'
        },
        {
            id: 21,
            name: 'Kevin Sanjaya, S.Bns.',
            role: 'Growth & Acquisition Specialist',
            division: 'Marketing',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'A/B Testing Funnel Landing Page & Conversion',
            activity_detail: 'Eksperimen variasi headline dan CTA pada halaman registrasi.',
            status_label: 'Deep Work',
            category: 'deep_work',
            online_duration: '3j 25m',
            tools: ['Mixpanel', 'Hotjar'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'green'],
            email: 'kevin.sanjaya@office.com',
            phone: '+62 812-4455-6677'
        },
        {
            id: 22,
            name: 'Indah Permatasari, S.E.',
            role: 'Corporate Tax & Accounting Officer',
            division: 'Finance',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Rekonsiliasi Faktur Pajak & e-Faktur PPN',
            activity_detail: 'Pencocokan invoice vendor cloud dan pelaporan SPT Masa.',
            status_label: 'Deep Work',
            category: 'deep_work',
            online_duration: '4j 15m',
            tools: ['DJP Online', 'Excel'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'green'],
            email: 'indah.permatasari@office.com',
            phone: '+62 813-7788-9900'
        },
        {
            id: 23,
            name: 'Maya Anggraini, S.T.',
            role: 'Mobile iOS & Flutter Developer',
            division: 'Technology',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Optimasi Offline Storage & SQLite Cache',
            activity_detail: 'Sinkronisasi background sync data saat kembali terkoneksi internet.',
            status_label: 'Deep Work',
            category: 'deep_work',
            online_duration: '2j 10m',
            tools: ['Flutter', 'Xcode'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'green'],
            email: 'maya.anggraini@office.com',
            phone: '+62 818-9900-1122'
        },
        {
            id: 24,
            name: 'Dimas Arya, S.Kom.',
            role: 'Cyber Security & Network Engineer',
            division: 'Technology',
            telegram: 'MuhammadFauzil',
            avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Vulnerability Scanning & OWASP API Audit',
            activity_detail: 'Pengecekan WAF Cloudflare rules dan pengujian SSL certificate rotation.',
            status_label: 'Auditing',
            category: 'review',
            online_duration: '3j 45m',
            tools: ['Wireshark', 'Cloudflare'],
            status_color: 'purple',
            dots: ['green', 'green', 'green', 'orange', 'green'],
            email: 'dimas.arya@office.com',
            phone: '+62 815-2233-4455'
        }
    ],
    filteredEmployees() {
        return this.activeEmployees.filter(emp => {
            let matchesCat = true;
            if (this.employeeFilter === 'deep_work') matchesCat = emp.category === 'deep_work';
            else if (this.employeeFilter === 'meeting') matchesCat = emp.category === 'meeting';
            else if (this.employeeFilter === 'review') matchesCat = emp.category === 'review';
            else if (this.employeeFilter === 'tech') matchesCat = emp.division === 'Technology';
            else if (this.employeeFilter === 'marketing') matchesCat = emp.division === 'Marketing';
            else if (this.employeeFilter === 'finance') matchesCat = emp.division === 'Finance';
            
            const q = this.employeeSearch.toLowerCase().trim();
            const matchesSearch = !q || 
                emp.name.toLowerCase().includes(q) || 
                emp.role.toLowerCase().includes(q) || 
                emp.current_activity.toLowerCase().includes(q) ||
                (emp.telegram && emp.telegram.toLowerCase().includes(q.replace('@', ''))) ||
                emp.division.toLowerCase().includes(q);

            return matchesCat && matchesSearch;
        });
    },
    paginatedEmployees() {
        const start = (this.currentPage - 1) * this.perPage;
        return this.filteredEmployees().slice(start, start + this.perPage);
    },
    totalPages() {
        return Math.ceil(this.filteredEmployees().length / this.perPage) || 1;
    },
    goToPage(page) {
        if (page >= 1 && page <= this.totalPages()) {
            this.currentPage = page;
            const grid = document.getElementById('employeeGridContainer');
            if (grid) {
                grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    }
}">

    <!-- ========================================== -->
    <!-- 1. WORKSPACE HEADER SECTION WITH TITLE & STATS -->
    <!-- ========================================== -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <!-- Left: New Task Button -->
        <div class="flex items-center flex-wrap gap-4">
            <!-- "+ New Task" Rounded Premium Dark Button -->
            <button 
                type="button" 
                @click="newTaskModalOpen = true"
                class="bg-zinc-900 hover:bg-zinc-800 text-white font-bold text-xs sm:text-sm px-5 py-2.5 rounded-full shadow-lg shadow-zinc-900/15 flex items-center gap-1.5 transition-all active:scale-95 cursor-pointer ml-1"
            >
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>New Task</span>
            </button>
        </div>

        <!-- Right: Real-time Deals Statistics Counter -->
        <div class="flex items-center space-x-6 sm:space-x-8 shrink-0">
            <!-- Stat 1: 34 Deals (+4 Deals) - Hijau Berhasil -->
            <div class="flex items-baseline space-x-2.5">
                <span class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">34</span>
                <span class="bg-emerald-600 text-white text-[11px] font-extrabold px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-xs">
                    <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.527.82-1.127 2.18-1.503 3.664a13.38 13.38 0 00-2.457 1.57A6.47 6.47 0 004 12c0 3.314 2.686 6 6 6s6-2.686 6-6a7.29 7.29 0 00-.735-3.218 10.3 10.3 0 00-2.87-3.229zm-1.077 4.148c.37-.93.75-1.745 1.13-2.443.43.518.84 1.126 1.206 1.813.626 1.173.946 2.378.946 3.482 0 2.209-1.791 4-4 4s-4-1.791-4-4c0-.986.357-2.072 1.055-3.118a11.39 11.39 0 012.146-2.346c-.035.88-.046 1.77.117 2.612z" clip-rule="evenodd" />
                    </svg>
                    <span>4 Deals</span>
                </span>
            </div>

            <!-- Stat 2: 20 Deals (+3 Deals) - Biru Progres -->
            <div class="flex items-baseline space-x-2.5">
                <span class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">20</span>
                <span class="bg-blue-600 text-white text-[11px] font-extrabold px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-xs">
                    <svg class="w-3 h-3 fill-none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    <span>3 Deals</span>
                </span>
            </div>

            <!-- Stat 3: 3 Deals (-1 Deals) - Abu-abu Netral -->
            <div class="flex items-baseline space-x-2.5">
                <span class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">3</span>
                <span class="bg-slate-500 text-white text-[11px] font-extrabold px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-xs">
                    <svg class="w-3 h-3 fill-none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                    <span>1 Deals</span>
                </span>
            </div>
        </div>
    </div>


    <!-- ========================================== -->
    <!-- 2. SECTION 1: KARYAWAN AKTIF & KEGIATAN   -->
    <!-- ========================================== -->
    <section class="mb-12">
        <!-- Section Header Row: Title + Filter Controls -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <!-- Left: Spacer -->
            <div></div>

            <!-- Right: Search, Filter Sliders, and Employee Status/Division Pills -->
            <div class="flex items-center flex-wrap gap-2.5">
                <!-- Search Button / Input (Frosted Glass) -->
                <div class="relative flex items-center">
                    <button 
                        type="button" 
                        @click="showEmployeeSearch = !showEmployeeSearch; if(showEmployeeSearch) $nextTick(() => $refs.employeeSearchInput.focus())"
                        class="w-9 h-9 rounded-full bg-white/80 hover:bg-white backdrop-blur-md border border-white/80 shadow-xs flex items-center justify-center text-slate-500 hover:text-slate-900 transition-colors cursor-pointer"
                        title="Cari Karyawan / Aktivitas"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                    <input 
                        x-ref="employeeSearchInput"
                        x-show="showEmployeeSearch" 
                        x-cloak 
                        type="text" 
                        x-model="employeeSearch"
                        @input="currentPage = 1"
                        placeholder="Cari karyawan / aktivitas..." 
                        class="ml-2 px-3.5 py-1.5 text-xs rounded-full bg-white/90 backdrop-blur-md border border-slate-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 w-48 shadow-xs"
                    >
                </div>

                <!-- Sliders Filter Reset Button -->
                <button 
                    type="button" 
                    @click="employeeFilter = 'all'; employeeSearch = ''; currentPage = 1; filterDropdownOpen = false; showToast('Filter direset ke Semua')"
                    class="w-9 h-9 rounded-full bg-white/80 hover:bg-white backdrop-blur-md border border-white/80 shadow-xs flex items-center justify-center text-slate-500 hover:text-slate-900 transition-colors cursor-pointer"
                    title="Reset Filter"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </button>

                <!-- Modern Dropdown Filtering Menu (Framer-style Clean Dropdown) -->
                <div class="relative" @click.outside="filterDropdownOpen = false">
                    <!-- Dropdown Trigger Capsule Button -->
                    <button 
                        type="button" 
                        @click="filterDropdownOpen = !filterDropdownOpen"
                        class="h-9 px-3.5 sm:px-4 rounded-full bg-white/80 hover:bg-white text-slate-700 hover:text-slate-900 border border-white/80 shadow-xs flex items-center gap-2 text-xs font-semibold backdrop-blur-md transition-all cursor-pointer"
                        :class="employeeFilter !== 'all' ? 'bg-zinc-900 text-white font-bold hover:bg-zinc-800 hover:text-white border-zinc-900 shadow-sm' : ''"
                        title="Pilih Filter Kategori / Divisi"
                    >
                        <!-- Dynamic Indicator: Status Dot or Category Icon -->
                        <span 
                            x-show="employeeFilter === 'deep_work'" 
                            class="w-2 h-2 rounded-full bg-emerald-500 shrink-0 shadow-xs"
                        ></span>
                        <span 
                            x-show="employeeFilter === 'meeting'" 
                            class="w-2 h-2 rounded-full bg-amber-500 shrink-0 shadow-xs"
                        ></span>
                        <span 
                            x-show="employeeFilter === 'review'" 
                            class="w-2 h-2 rounded-full bg-indigo-500 shrink-0 shadow-xs"
                        ></span>
                        <svg 
                            x-show="['all', 'tech', 'marketing', 'finance'].includes(employeeFilter)" 
                            class="w-3.5 h-3.5 shrink-0" 
                            :class="employeeFilter !== 'all' ? 'text-white' : 'text-slate-500'"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>

                        <!-- Label with dynamic count -->
                        <span x-text="
                            employeeFilter === 'all' ? 'Semua (' + activeEmployees.length + ')' :
                            employeeFilter === 'deep_work' ? 'Deep Work (' + activeEmployees.filter(e => e.category === 'deep_work').length + ')' :
                            employeeFilter === 'meeting' ? 'In Meeting (' + activeEmployees.filter(e => e.category === 'meeting').length + ')' :
                            employeeFilter === 'review' ? 'Review PR (' + activeEmployees.filter(e => e.category === 'review').length + ')' :
                            employeeFilter === 'tech' ? 'Tech (' + activeEmployees.filter(e => e.division === 'Technology').length + ')' :
                            employeeFilter === 'marketing' ? 'Marketing (' + activeEmployees.filter(e => e.division === 'Marketing').length + ')' :
                            employeeFilter === 'finance' ? 'Finance (' + activeEmployees.filter(e => e.division === 'Finance').length + ')' : 'Filter'
                        "></span>

                        <!-- Animated Chevron -->
                        <svg 
                            class="w-3.5 h-3.5 transition-transform duration-200" 
                            :class="[
                                filterDropdownOpen ? 'rotate-180' : '',
                                employeeFilter !== 'all' ? 'text-white/70' : 'text-slate-400'
                            ]"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu Panel -->
                    <div 
                        x-show="filterDropdownOpen" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                        x-cloak
                        class="absolute right-0 top-full mt-2 w-60 bg-white/95 backdrop-blur-2xl border border-white/90 rounded-2xl shadow-xl shadow-slate-900/10 p-1.5 z-40 overflow-hidden"
                    >
                        <!-- Option: Semua -->
                        <button 
                            type="button" 
                            @click="employeeFilter = 'all'; currentPage = 1; filterDropdownOpen = false"
                            class="w-full px-3 py-2 rounded-xl text-xs flex items-center justify-between transition-colors text-left cursor-pointer group"
                            :class="employeeFilter === 'all' ? 'bg-zinc-900 text-white font-semibold' : 'text-slate-700 hover:bg-slate-100'"
                        >
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" :class="employeeFilter === 'all' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                <span>Tampilkan Semua</span>
                            </div>
                            <span class="text-[11px] px-1.5 py-0.5 rounded-full" :class="employeeFilter === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500 font-mono'" x-text="activeEmployees.length"></span>
                        </button>

                        <!-- Group Label: Status Aktivitas -->
                        <div class="px-3 pt-2.5 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            Status Aktivitas
                        </div>

                        <!-- Option: Deep Work -->
                        <button 
                            type="button" 
                            @click="employeeFilter = 'deep_work'; currentPage = 1; filterDropdownOpen = false"
                            class="w-full px-3 py-2 rounded-xl text-xs flex items-center justify-between transition-colors text-left cursor-pointer"
                            :class="employeeFilter === 'deep_work' ? 'bg-zinc-900 text-white font-semibold' : 'text-slate-700 hover:bg-slate-100'"
                        >
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                                <span>Deep Work</span>
                            </div>
                            <span class="text-[11px] px-1.5 py-0.5 rounded-full" :class="employeeFilter === 'deep_work' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500 font-mono'" x-text="activeEmployees.filter(e => e.category === 'deep_work').length"></span>
                        </button>

                        <!-- Option: In Meeting -->
                        <button 
                            type="button" 
                            @click="employeeFilter = 'meeting'; currentPage = 1; filterDropdownOpen = false"
                            class="w-full px-3 py-2 rounded-xl text-xs flex items-center justify-between transition-colors text-left cursor-pointer"
                            :class="employeeFilter === 'meeting' ? 'bg-zinc-900 text-white font-semibold' : 'text-slate-700 hover:bg-slate-100'"
                        >
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                                <span>In Meeting</span>
                            </div>
                            <span class="text-[11px] px-1.5 py-0.5 rounded-full" :class="employeeFilter === 'meeting' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500 font-mono'" x-text="activeEmployees.filter(e => e.category === 'meeting').length"></span>
                        </button>

                        <!-- Option: Review PR -->
                        <button 
                            type="button" 
                            @click="employeeFilter = 'review'; currentPage = 1; filterDropdownOpen = false"
                            class="w-full px-3 py-2 rounded-xl text-xs flex items-center justify-between transition-colors text-left cursor-pointer"
                            :class="employeeFilter === 'review' ? 'bg-zinc-900 text-white font-semibold' : 'text-slate-700 hover:bg-slate-100'"
                        >
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-indigo-500 shrink-0"></span>
                                <span>Review PR</span>
                            </div>
                            <span class="text-[11px] px-1.5 py-0.5 rounded-full" :class="employeeFilter === 'review' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500 font-mono'" x-text="activeEmployees.filter(e => e.category === 'review').length"></span>
                        </button>

                        <!-- Divider -->
                        <div class="h-px bg-slate-100 my-1.5"></div>

                        <!-- Group Label: Divisi Kantor -->
                        <div class="px-3 pt-1 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            Divisi Kantor
                        </div>

                        <!-- Option: Technology -->
                        <button 
                            type="button" 
                            @click="employeeFilter = 'tech'; currentPage = 1; filterDropdownOpen = false"
                            class="w-full px-3 py-2 rounded-xl text-xs flex items-center justify-between transition-colors text-left cursor-pointer"
                            :class="employeeFilter === 'tech' ? 'bg-zinc-900 text-white font-semibold' : 'text-slate-700 hover:bg-slate-100'"
                        >
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 shrink-0"></span>
                                <span>Technology</span>
                            </div>
                            <span class="text-[11px] px-1.5 py-0.5 rounded-full" :class="employeeFilter === 'tech' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500 font-mono'" x-text="activeEmployees.filter(e => e.division === 'Technology').length"></span>
                        </button>

                        <!-- Option: Marketing -->
                        <button 
                            type="button" 
                            @click="employeeFilter = 'marketing'; currentPage = 1; filterDropdownOpen = false"
                            class="w-full px-3 py-2 rounded-xl text-xs flex items-center justify-between transition-colors text-left cursor-pointer"
                            :class="employeeFilter === 'marketing' ? 'bg-zinc-900 text-white font-semibold' : 'text-slate-700 hover:bg-slate-100'"
                        >
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 shrink-0"></span>
                                <span>Marketing</span>
                            </div>
                            <span class="text-[11px] px-1.5 py-0.5 rounded-full" :class="employeeFilter === 'marketing' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500 font-mono'" x-text="activeEmployees.filter(e => e.division === 'Marketing').length"></span>
                        </button>

                        <!-- Option: Finance -->
                        <button 
                            type="button" 
                            @click="employeeFilter = 'finance'; currentPage = 1; filterDropdownOpen = false"
                            class="w-full px-3 py-2 rounded-xl text-xs flex items-center justify-between transition-colors text-left cursor-pointer"
                            :class="employeeFilter === 'finance' ? 'bg-zinc-900 text-white font-semibold' : 'text-slate-700 hover:bg-slate-100'"
                        >
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 shrink-0"></span>
                                <span>Finance</span>
                            </div>
                            <span class="text-[11px] px-1.5 py-0.5 rounded-full" :class="employeeFilter === 'finance' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500 font-mono'" x-text="activeEmployees.filter(e => e.division === 'Finance').length"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards Grid: Karyawan Aktif & Kegiatan (Frosted Glass Effect - Max 20 per Page) -->
        <div id="employeeGridContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <template x-for="emp in paginatedEmployees()" :key="emp.id">
                <div class="relative flex flex-col group transition-all duration-200 hover:-translate-y-1">
                    <!-- Top Step Row: Left Profile Tab (Avatar + Name & Role) + Fillet + Recessed Action Notch -->
                    <div class="flex items-end relative z-10 -mb-px">
                        <!-- Elevated Profile Tab: Translucent Glass Surface (No bottom border, no shadow-xs) -->
                        <div class="bg-white/80 backdrop-blur-xl pt-3 pl-3.5 pr-4 pb-3 rounded-tl-[24px] rounded-tr-[20px] flex items-center gap-3 flex-1 min-w-0 border-t border-l border-white/80 border-b-0">
                            <!-- Avatar with Live Online Indicator -->
                            <div class="relative shrink-0">
                                <img :src="emp.avatar" :alt="emp.name" class="w-11 h-11 rounded-full object-cover ring-2 ring-white/90" loading="lazy">
                                <!-- Online Live Pulsing Dot -->
                                <span class="absolute bottom-0 right-0 flex h-3.5 w-3.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500 ring-2 ring-white"></span>
                                </span>
                            </div>

                            <!-- Employee Name & Role: Spacious & Comfortable -->
                            <div class="min-w-0 flex-1 pr-1">
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight truncate leading-snug" x-text="emp.name"></h3>
                                <div class="flex items-center gap-1.5 mt-0.5 min-w-0">
                                    <p class="text-xs text-slate-500 leading-snug truncate" x-text="emp.role"></p>
                                    <a 
                                        x-show="emp.telegram"
                                        :href="'https://t.me/' + emp.telegram" 
                                        target="_blank" 
                                        rel="noopener noreferrer" 
                                        @click.stop
                                        class="inline-flex items-center gap-0.5 text-[10px] font-bold text-[#229ED9] hover:text-[#1877a7] hover:underline shrink-0"
                                        :title="'Buka Telegram @' + emp.telegram"
                                    >
                                        <span x-text="'@' + emp.telegram"></span>
                                        <svg class="w-2.5 h-2.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Inverted Concave Fillet Curve (Smooth Matching Glass Fillet) -->
                        <div class="w-[20px] h-[20px] text-white/80 shrink-0 self-end -ml-px relative z-10 pointer-events-none">
                            <svg viewBox="0 0 20 20" class="w-full h-full fill-current overflow-visible">
                                <path d="M 0 0 C 0 11.046 8.954 20 20 20 L 0 20 Z" />
                                <path d="M 0 0 C 0 11.046 8.954 20 20 20" fill="none" stroke="rgba(255,255,255,0.8)" stroke-width="1.2" />
                            </svg>
                        </div>

                        <!-- Notch Action Button: Direct Telegram Chat (Hover Green Micro-interaction) -->
                        <div class="w-14 shrink-0 flex items-center justify-end pb-3 pr-2.5 pl-1.5">
                            <a 
                                :href="'https://t.me/' + (emp.telegram ? emp.telegram.replace('@', '') : '')"
                                target="_blank"
                                rel="noopener noreferrer"
                                @click.stop
                                class="w-10 h-10 rounded-full bg-white/85 hover:bg-emerald-500 backdrop-blur-md border border-white/90 hover:border-emerald-400 text-slate-700 hover:text-white flex items-center justify-center transition-all duration-300 ease-out hover:scale-110 hover:shadow-lg hover:shadow-emerald-500/30 active:scale-95 cursor-pointer shrink-0 shadow-xs group/btn"
                                :title="'Buka Telegram @' + (emp.telegram ? emp.telegram.replace('@', '') : '')"
                            >
                                <svg class="w-5 h-5 transition-all duration-300 group-hover/btn:scale-110 group-hover/btn:-rotate-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Main Card Body (Frosted Glass Effect - Seamlessly flowing from Profile Tab) -->
                    <div class="bg-white/80 backdrop-blur-xl text-slate-800 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-xl hover:bg-white/85 border-b border-l border-r border-white/80 rounded-b-[24px] rounded-tr-[24px] p-4 sm:p-5 pt-3.5 relative transition-all flex-1 flex flex-col justify-between">
                        <!-- Top Shelf Border (Only on the right notch, leaving the left under profile tab 100% borderless) -->
                        <div class="absolute -top-px right-0 w-14 h-4 border-t border-white/80 rounded-tr-[24px] pointer-events-none"></div>
                        <div>
                            <!-- Live Activity Box (Sedang Mengerjakan) - Frosted Inset Surface -->
                            <div class="p-3 rounded-2xl bg-white/60 backdrop-blur-sm border border-slate-200/60 shadow-xs transition-colors">
                                <div class="flex items-center justify-between mb-1.5">
                                    <div class="flex items-center gap-1.5">
                                        <span 
                                            class="w-1.5 h-1.5 rounded-full inline-block"
                                            :class="{
                                                'bg-emerald-500 animate-pulse': emp.category === 'deep_work',
                                                'bg-amber-500 animate-pulse': emp.category === 'meeting',
                                                'bg-indigo-500 animate-pulse': emp.category === 'review'
                                            }"
                                        ></span>
                                        <span class="text-[10px] uppercase font-bold tracking-wider text-slate-500">Sedang Mengerjakan:</span>
                                    </div>
                                    <span class="text-[10px] font-mono text-slate-400" x-text="emp.online_duration"></span>
                                </div>
                                <p class="text-xs font-semibold text-slate-900 line-clamp-2 leading-relaxed" x-text="emp.current_activity"></p>
                            </div>
                        </div>

                        <!-- Footer Row: Tools Pills (left) & Status Label with Dots (right) -->
                        <div class="flex items-end justify-between pt-3 mt-3 border-t border-slate-200/50">
                            <!-- Left: Tools / App -->
                            <div>
                                <span class="text-[10px] text-slate-400 font-semibold block mb-1">Tools / App</span>
                                <div class="flex items-center space-x-1">
                                    <template x-for="tool in emp.tools" :key="tool">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-white/80 text-slate-700 border border-slate-200/60 shadow-2xs" x-text="tool"></span>
                                    </template>
                                </div>
                            </div>

                            <!-- Right: Status Badge & 5 Indicator Dots -->
                            <div class="text-right">
                                <span 
                                    class="text-xs font-semibold block mb-1.5"
                                    :class="{
                                        'text-emerald-700': emp.category === 'deep_work',
                                        'text-amber-700': emp.category === 'meeting',
                                        'text-indigo-700': emp.category === 'review'
                                    }"
                                    x-text="emp.status_label"
                                ></span>
                                <!-- 5 colored indicator dots -->
                                <div class="flex items-center justify-end space-x-1">
                                    <template x-for="(dot, idx) in emp.dots" :key="idx">
                                        <span 
                                            class="w-2 h-2 rounded-full inline-block"
                                            :class="{
                                                'bg-emerald-500 shadow-[0_0_6px_rgba(16,185,129,0.5)]': dot === 'green',
                                                'bg-amber-500 shadow-[0_0_6px_rgba(245,158,11,0.5)]': dot === 'orange',
                                                'bg-slate-300': dot === 'gray'
                                            }"
                                        ></span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State (Jika tidak ada hasil filter / pencarian) -->
        <div 
            x-show="filteredEmployees().length === 0"
            x-cloak
            class="py-16 text-center rounded-3xl bg-white/60 backdrop-blur-md border border-white/80 p-8 shadow-xs"
        >
            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h4 class="text-sm font-bold text-slate-800">Tidak ada rekan kerja ditemukan</h4>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Tidak ditemukan karyawan yang cocok dengan filter atau kata kunci pencarian saat ini.</p>
            <button 
                type="button" 
                @click="employeeFilter = 'all'; employeeSearch = ''; currentPage = 1"
                class="mt-4 px-4 py-2 rounded-full bg-zinc-900 text-white text-xs font-semibold hover:bg-zinc-800 transition-all cursor-pointer shadow-xs"
            >
                Reset Semua Filter
            </button>
        </div>

        <!-- Pagination Controls (Hanya muncul jika jumlah karyawan > 20) -->
        <div 
            x-show="filteredEmployees().length > perPage"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-cloak
            class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 p-4 rounded-2xl bg-white/70 backdrop-blur-md border border-white/80 shadow-xs"
        >
            <!-- Info Text: Menampilkan X - Y dari Total -->
            <div class="text-xs text-slate-600 font-medium">
                Menampilkan 
                <span class="font-bold text-slate-900 font-mono" x-text="((currentPage - 1) * perPage) + 1"></span>
                -
                <span class="font-bold text-slate-900 font-mono" x-text="Math.min(currentPage * perPage, filteredEmployees().length)"></span>
                dari 
                <span class="font-bold text-slate-900 font-mono" x-text="filteredEmployees().length"></span>
                rekan kerja
            </div>

            <!-- Page Buttons (Prev, Numbers, Next) -->
            <div class="flex items-center gap-1.5">
                <!-- Tombol Sebelumnya -->
                <button 
                    type="button" 
                    @click="goToPage(currentPage - 1)"
                    :disabled="currentPage === 1"
                    :class="currentPage === 1 ? 'opacity-40 cursor-not-allowed text-slate-400' : 'hover:bg-white text-slate-700 hover:text-slate-900 cursor-pointer shadow-2xs hover:shadow-xs active:scale-95'"
                    class="px-3.5 py-1.5 rounded-xl border border-slate-200 bg-white/80 text-xs font-semibold flex items-center gap-1.5 transition-all"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Sebelumnya</span>
                </button>

                <!-- Nomor Halaman -->
                <div class="flex items-center gap-1">
                    <template x-for="p in totalPages()" :key="p">
                        <button 
                            type="button" 
                            @click="goToPage(p)"
                            class="w-8 h-8 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center justify-center font-mono"
                            :class="currentPage === p 
                                ? 'bg-zinc-900 text-white shadow-xs' 
                                : 'bg-white/80 hover:bg-white text-slate-600 hover:text-slate-900 border border-slate-200 shadow-2xs active:scale-95'"
                            x-text="p"
                        ></button>
                    </template>
                </div>

                <!-- Tombol Selanjutnya -->
                <button 
                    type="button" 
                    @click="goToPage(currentPage + 1)"
                    :disabled="currentPage === totalPages()"
                    :class="currentPage === totalPages() ? 'opacity-40 cursor-not-allowed text-slate-400' : 'hover:bg-white text-slate-700 hover:text-slate-900 cursor-pointer shadow-2xs hover:shadow-xs active:scale-95'"
                    class="px-3.5 py-1.5 rounded-xl border border-slate-200 bg-white/80 text-xs font-semibold flex items-center gap-1.5 transition-all"
                >
                    <span>Selanjutnya</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </section>



    <!-- ========================================== -->
    <!-- 3. MODAL: CREATE NEW TASK / LEAD          -->
    <!-- ========================================== -->
    <div x-show="newTaskModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="newTaskModalOpen = false" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs"></div>

        <div class="relative bg-white/95 backdrop-blur-2xl rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-white/80 z-10 overflow-hidden text-slate-900" x-data="{
            newTitle: '',
            newType: 'Zoom Meeting',
            newAssignee: 'Budi Pratama',
            newSubtitle: 'Hari ini pukul 15:00',
            newCategory: 'hot',
            submitNewTask() {
                if (!this.newTitle) { alert('Harap isi judul tugas!'); return; }
                newTaskModalOpen = false;
                showToast('Tugas baru berhasil ditambahkan!');
                this.newTitle = '';
            }
        }">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-zinc-100 text-zinc-900 flex items-center justify-center font-bold">
                        +
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Buat Tugas Baru (New Task)</h3>
                        <p class="text-xs text-slate-500">Tambahkan aktivitas operasional ke workspace</p>
                    </div>
                </div>
                <button @click="newTaskModalOpen = false" class="text-slate-400 hover:text-slate-700 text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form @submit.prevent="submitNewTask()" class="mt-5 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Tugas / Aktivitas</label>
                    <input 
                        type="text" 
                        x-model="newTitle" 
                        placeholder="Contoh: Zoom Meeting Klien Baru" 
                        required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 text-xs"
                    >
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Aktivitas</label>
                        <select 
                            x-model="newType"
                            class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:border-zinc-900"
                        >
                            <option value="Zoom Meeting">Zoom Meeting (Video Call)</option>
                            <option value="Send Proposal">Send Proposal (Dokumen)</option>
                            <option value="Send Email">Send Email (Surat Elektronik)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Prioritas / Kategori</label>
                        <select 
                            x-model="newCategory"
                            class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:border-zinc-900"
                        >
                            <option value="hot">🔥 Hot Priority</option>
                            <option value="high">High Priority</option>
                            <option value="medium">Medium Priority</option>
                            <option value="low">Low Priority</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Waktu / Jadwal</label>
                    <input 
                        type="text" 
                        x-model="newSubtitle" 
                        placeholder="Contoh: 28.12.2023 at 2:27 PM" 
                        class="w-full px-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-zinc-900 text-xs"
                    >
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button 
                        type="button" 
                        @click="newTaskModalOpen = false" 
                        class="px-4 py-2 rounded-full text-xs font-medium text-slate-500 hover:text-slate-800 cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-5 py-2 rounded-full text-xs font-bold text-white bg-zinc-900 hover:bg-zinc-800 shadow-md transition-all active:scale-95 cursor-pointer"
                    >
                        Simpan Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- ========================================== -->
    <!-- 4. MODAL: EMPLOYEE ACTIVITY DETAIL VIEW   -->
    <!-- ========================================== -->
    <div x-show="selectedEmployee" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="selectedEmployee = null" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs"></div>

        <div class="relative bg-white/95 backdrop-blur-2xl rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-white/80 z-10 overflow-hidden text-slate-900">
            <template x-if="selectedEmployee">
                <div>
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center space-x-3.5">
                            <div class="relative">
                                <img :src="selectedEmployee.avatar" class="w-13 h-13 rounded-full object-cover ring-2 ring-emerald-500/50">
                                <span class="absolute bottom-0 right-0 flex h-3.5 w-3.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500 ring-2 ring-white"></span>
                                </span>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 tracking-tight" x-text="selectedEmployee.name"></h3>
                                <p class="text-xs text-slate-500" x-text="selectedEmployee.role + ' • ' + selectedEmployee.division"></p>
                            </div>
                        </div>
                        <button @click="selectedEmployee = null" class="text-slate-400 hover:text-slate-700 text-xl font-bold cursor-pointer">&times;</button>
                    </div>

                    <!-- Live Activity Banner -->
                    <div class="mt-4 p-4 rounded-2xl bg-slate-50/80 border border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span 
                                    class="w-2 h-2 rounded-full inline-block"
                                    :class="{
                                        'bg-emerald-500 animate-pulse': selectedEmployee.category === 'deep_work',
                                        'bg-amber-500 animate-pulse': selectedEmployee.category === 'meeting',
                                        'bg-indigo-500 animate-pulse': selectedEmployee.category === 'review'
                                    }"
                                ></span>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Aktivitas Saat Ini</span>
                            </div>
                            <span class="text-xs font-mono text-slate-500" x-text="'Durasi: ' + selectedEmployee.online_duration"></span>
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 mb-1" x-text="selectedEmployee.current_activity"></h4>
                        <p class="text-xs text-slate-600 leading-relaxed" x-text="selectedEmployee.activity_detail"></p>
                    </div>

                    <!-- Details Metadata -->
                    <div class="mt-4 space-y-2.5 text-xs">
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-500">Status Operasional:</span>
                            <span 
                                class="font-bold"
                                :class="{
                                    'text-emerald-700': selectedEmployee.category === 'deep_work',
                                    'text-amber-700': selectedEmployee.category === 'meeting',
                                    'text-indigo-700': selectedEmployee.category === 'review'
                                }"
                                x-text="selectedEmployee.status_label"
                            ></span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-500">Software / Tools Dipakai:</span>
                            <div class="flex gap-1.5">
                                <template x-for="t in selectedEmployee.tools" :key="t">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200" x-text="t"></span>
                                </template>
                            </div>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-500">Email Kantor:</span>
                            <span class="text-slate-700 font-mono" x-text="selectedEmployee.email"></span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-500">Kontak Internal:</span>
                            <span class="text-slate-700 font-mono" x-text="selectedEmployee.phone"></span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-100" x-show="selectedEmployee.telegram">
                            <span class="text-slate-500">Akun Telegram:</span>
                            <a 
                                :href="'https://t.me/' + selectedEmployee.telegram" 
                                target="_blank" 
                                rel="noopener noreferrer"
                                class="text-[#229ED9] hover:underline font-bold flex items-center gap-1 font-mono text-xs"
                            >
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.536-.196 1.006.128.832.942z"/>
                                </svg>
                                <span x-text="'@' + selectedEmployee.telegram"></span>
                                <svg class="w-3 h-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Modal Action Buttons -->
                    <div class="mt-6 flex items-center justify-end space-x-2.5">
                        <button 
                            type="button" 
                            @click="selectedEmployee = null" 
                            class="px-4 py-2 rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-semibold cursor-pointer"
                        >
                            Tutup
                        </button>
                        <template x-if="selectedEmployee.telegram">
                            <a 
                                :href="'https://t.me/' + selectedEmployee.telegram.replace('@', '')" 
                                target="_blank" 
                                rel="noopener noreferrer" 
                                class="px-5 py-2 rounded-full bg-[#229ED9] hover:bg-[#1b87ba] text-white text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer shadow-md active:scale-95"
                            >
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.536-.196 1.006.128.832.942z"/>
                                </svg>
                                <span>Hubungi via Telegram ↗</span>
                            </a>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

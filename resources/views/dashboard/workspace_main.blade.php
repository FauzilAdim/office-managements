<div x-data="{
    employeeFilter: 'all',
    employeeSearch: '',
    showEmployeeSearch: false,
    taskFilter: 'all',
    taskSearch: '',
    showTaskSearch: false,
    newTaskModalOpen: false,
    selectedEmployee: null,
    selectedTask: null,
    activeEmployees: [
        {
            id: 1,
            name: 'Fauzil Adzim, S.Tr.Kom.',
            role: 'Lead Architect & Tech Lead',
            division: 'Technology',
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
            name: 'Ahmad Fikri',
            role: 'Senior Backend Engineer',
            division: 'Technology',
            avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Optimasi Query Database & Redis Cache',
            activity_detail: 'Indexing query transaksi berat dan benchmark throughput response time.',
            status_label: 'Deep Work',
            category: 'deep_work',
            online_duration: '2j 45m',
            tools: ['Laravel', 'Postgres'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'orange', 'green'],
            email: 'ahmad.fikri@office.com',
            phone: '+62 813-2345-6789'
        },
        {
            id: 3,
            name: 'Anisa Maharani, S.Kom.',
            role: 'QA Automation Lead',
            division: 'Technology',
            avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Automated Regression Suite Testing',
            activity_detail: 'Menjalankan skenario E2E testing untuk modul payroll dan audit workflow.',
            status_label: 'Running Tests',
            category: 'deep_work',
            online_duration: '1j 50m',
            tools: ['Pest PHP', 'Cypress'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'orange'],
            email: 'anisa.maharani@office.com',
            phone: '+62 814-3456-7890'
        },
        {
            id: 4,
            name: 'Ir. Hendra Gunawan, M.T.',
            role: 'VP of Technology',
            division: 'Technology',
            avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Sprint Planning & Cloud Infrastructure Sync',
            activity_detail: 'Sinkronisasi roadmap Q4 dan alokasi resource cluster cloud bersama leads.',
            status_label: 'In Meeting',
            category: 'meeting',
            online_duration: '4j 10m',
            tools: ['Meet', 'AWS Cloud'],
            status_color: 'amber',
            dots: ['orange', 'orange', 'orange', 'green', 'green'],
            email: 'hendra.gunawan@office.com',
            phone: '+62 811-1234-5678'
        },
        {
            id: 5,
            name: 'Dian Sastrowardoyo, S.Ds.',
            role: 'Brand & UI/UX Design Lead',
            division: 'Marketing',
            avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Design System v2.4 Components di Figma',
            activity_detail: 'Merapikan token typography, icon kit, dan prototype interaktif dashboard.',
            status_label: 'Designing',
            category: 'deep_work',
            online_duration: '2j 15m',
            tools: ['Figma', 'Notion'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'green'],
            email: 'dian.sastro@office.com',
            phone: '+62 812-9876-5432'
        },
        {
            id: 6,
            name: 'Reza Rahadian, S.Kom.',
            role: 'Software Engineering Manager',
            division: 'Technology',
            avatar: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Review Pull Request & Code Audit Security',
            activity_detail: 'Memvalidasi merge request fitur baru dan kepatuhan standar clean code.',
            status_label: 'Reviewing PR',
            category: 'review',
            online_duration: '3j 05m',
            tools: ['GitHub', 'Linear'],
            status_color: 'purple',
            dots: ['green', 'green', 'orange', 'orange', 'green'],
            email: 'reza.rahadian@office.com',
            phone: '+62 815-4567-8901'
        },
        {
            id: 7,
            name: 'Siti Nurhaliza, S.E., M.M.',
            role: 'VP of Marketing & Growth',
            division: 'Marketing',
            avatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Analisis Performance Campaign Q4 & Ads',
            activity_detail: 'Evaluasi konversi channel digital dan penyesuaian strategi growth.',
            status_label: 'In Meeting',
            category: 'meeting',
            online_duration: '1j 25m',
            tools: ['Meet', 'Analytics'],
            status_color: 'amber',
            dots: ['orange', 'orange', 'green', 'green', 'gray'],
            email: 'siti.nurhaliza@office.com',
            phone: '+62 816-5678-9012'
        },
        {
            id: 8,
            name: 'Drs. Bambang Wijaya, Ak.',
            role: 'VP of Finance & Operations',
            division: 'Finance',
            avatar: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Verifikasi Anggaran Operasional & Payroll',
            activity_detail: 'Audit approval disbursement operasional kantor dan rekonsiliasi kas.',
            status_label: 'Deep Work',
            category: 'deep_work',
            online_duration: '4j 40m',
            tools: ['SAP ERP', 'Excel'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'green'],
            email: 'bambang.wijaya@office.com',
            phone: '+62 817-6789-0123'
        },
        {
            id: 9,
            name: 'Maya Anggraini, S.T.',
            role: 'Frontend & Mobile Engineer',
            division: 'Technology',
            avatar: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Slicing Dynamic Island Widget & Micro-physics',
            activity_detail: 'Optimasi viewport responsive, gesture event, dan transition physics.',
            status_label: 'Deep Work',
            category: 'deep_work',
            online_duration: '2j 10m',
            tools: ['Tailwind', 'Flutter'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'green'],
            email: 'maya.anggraini@office.com',
            phone: '+62 818-1234-5678'
        },
        {
            id: 10,
            name: 'Budi Santoso, S.Kom.',
            role: 'DevOps & SRE Specialist',
            division: 'Technology',
            avatar: 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Monitoring Cluster Kubernetes & Pod Scaling',
            activity_detail: 'Benchmarking load balancer, failover testing, dan konfigurasi Grafana alert.',
            status_label: 'Deep Work',
            category: 'deep_work',
            online_duration: '3j 40m',
            tools: ['Kubernetes', 'Grafana'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'orange', 'green'],
            email: 'budi.santoso@office.com',
            phone: '+62 819-2345-6789'
        },
        {
            id: 11,
            name: 'Citra Kirana, S.I.Kom.',
            role: 'Head of People & HR Operations',
            division: 'Operations',
            avatar: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: '1-on-1 Performance Sync & Talent Review',
            activity_detail: 'Evaluasi KPI bulanan divisi teknis dan finalisasi kontrak karyawan baru.',
            status_label: 'In Meeting',
            category: 'meeting',
            online_duration: '1j 45m',
            tools: ['Google Meet', 'BambooHR'],
            status_color: 'amber',
            dots: ['orange', 'orange', 'orange', 'green', 'green'],
            email: 'citra.kirana@office.com',
            phone: '+62 820-3456-7890'
        },
        {
            id: 12,
            name: 'Rian Pratama, S.E.',
            role: 'Corporate Tax & Accounting Officer',
            division: 'Finance',
            avatar: 'https://images.unsplash.com/photo-1501196354995-cbb51c65aaea?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Rekonsiliasi Faktur Pajak & e-Faktur PPN',
            activity_detail: 'Penyusunan SPT Masa, validasi bukti potong PPh 23, dan e-Billing Kemenkeu.',
            status_label: 'Deep Work',
            category: 'deep_work',
            online_duration: '2j 55m',
            tools: ['DJP Online', 'Excel'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'green'],
            email: 'rian.pratama@office.com',
            phone: '+62 821-4567-8901'
        },
        {
            id: 13,
            name: 'Nadia Putri, S.Ds.',
            role: 'Motion & Design System Specialist',
            division: 'Marketing',
            avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Animation Physics di Rive & Framer Prototype',
            activity_detail: 'Simulasi transisi state hover, drag sheet, dan micro-feedback haptic.',
            status_label: 'Designing',
            category: 'deep_work',
            online_duration: '1j 35m',
            tools: ['Rive', 'Framer'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'orange'],
            email: 'nadia.putri@office.com',
            phone: '+62 822-5678-9012'
        },
        {
            id: 14,
            name: 'Dimas Arya, S.Kom.',
            role: 'Cyber Security & Pentest Specialist',
            division: 'Technology',
            avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Vulnerability Scanning & OWASP API Audit',
            activity_detail: 'Testing payload SQLi/XSS, validasi CORS policy, dan hardening Nginx proxy.',
            status_label: 'Reviewing PR',
            category: 'review',
            online_duration: '3j 15m',
            tools: ['Burp Suite', 'SonarQube'],
            status_color: 'purple',
            dots: ['green', 'green', 'orange', 'orange', 'green'],
            email: 'dimas.arya@office.com',
            phone: '+62 823-6789-0123'
        },
        {
            id: 15,
            name: 'Kevin Sanjaya, S.Bns.',
            role: 'Growth Marketing & Lead Acquisition',
            division: 'Marketing',
            avatar: 'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'A/B Testing Funnel Landing Page & Ad Creatives',
            activity_detail: 'Optimasi click-through-rate kampanye LinkedIn Ads dan Meta Ads manager.',
            status_label: 'In Meeting',
            category: 'meeting',
            online_duration: '2j 05m',
            tools: ['Meta Ads', 'Hotjar'],
            status_color: 'amber',
            dots: ['orange', 'orange', 'green', 'green', 'gray'],
            email: 'kevin.sanjaya@office.com',
            phone: '+62 824-7890-1234'
        },
        {
            id: 16,
            name: 'Indah Permatasari, S.E.',
            role: 'Financial Analyst & Budgeting Lead',
            division: 'Finance',
            avatar: 'https://images.unsplash.com/photo-1548142813-c348350df52b?auto=format&fit=crop&w=256&h=256&q=80',
            current_activity: 'Vendor Contract Review & Cloud Inventory',
            activity_detail: 'Audit inventaris workstation engineer dan renewal lisensi cloud enterprise.',
            status_label: 'Deep Work',
            category: 'deep_work',
            online_duration: '4j 15m',
            tools: ['Notion', 'SAP'],
            status_color: 'emerald',
            dots: ['green', 'green', 'green', 'green', 'green'],
            email: 'indah.permatasari@office.com',
            phone: '+62 825-8901-2345'
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
            
            const q = this.employeeSearch.toLowerCase();
            const matchesSearch = !this.employeeSearch || 
                emp.name.toLowerCase().includes(q) || 
                emp.role.toLowerCase().includes(q) || 
                emp.current_activity.toLowerCase().includes(q) ||
                emp.division.toLowerCase().includes(q);

            return matchesCat && matchesSearch;
        });
    },
    chatPanelOpen: false,
    chatMaximized: false,
    chatActiveEmployee: null,
    chatInputMessage: '',
    chatSearch: '',
    chatConversations: {
        2: [
            { sender: 'other', text: 'Halo mas Fauzil, webhook Midtrans QRIS & BCA Virtual Account sudah selesai di-testing di sandbox.', time: '14:20' },
            { sender: 'me', text: 'Mantap Fikri, coba cek idempotency key nya ya biar ga double charge saat network timeout.', time: '14:22' },
            { sender: 'other', text: 'Siap mas, sudah dihandle pakai Redis lock dengan TTL 10 detik. Tinggal review PR.', time: '14:25' }
        ],
        3: [
            { sender: 'other', text: 'Pest regression suite sudah selesai di branch test/payroll. Semua 40 tests lolos.', time: '13:45' },
            { sender: 'me', text: 'Keren! Boleh minta tolong trigger ke CI pipeline staging sekarang?', time: '13:50' },
            { sender: 'other', text: 'Sedang berjalan di GitHub Actions mas, estimasi 5 menit lagi selesai.', time: '13:52' }
        ],
        4: [
            { sender: 'other', text: 'Fauzil, nanti jam 15:30 ada sync arsitektur SSO multi-cabang ya di Zoom.', time: '11:15' },
            { sender: 'me', text: 'Siap pak Hendra, dokumen RFC dan diagram OAuth2 sudah saya siapkan.', time: '11:20' }
        ],
        6: [
            { sender: 'other', text: 'PR refactor auth gateway sudah di-assign ke saya mas. Sore ini saya review komplit.', time: '10:05' },
            { sender: 'me', text: 'Thanks mas Reza! Prioritas aman sebelum release ke staging besok.', time: '10:10' }
        ]
    },
    openChatWith(emp) {
        this.chatActiveEmployee = emp;
        this.chatPanelOpen = true;
        if (!this.chatConversations[emp.id]) {
            this.chatConversations[emp.id] = [
                { sender: 'other', text: 'Halo! Saya sedang mengerjakan: ' + emp.current_activity + '. Ada yang perlu dikoordinasikan?', time: 'Baru saja' }
            ];
        }
        this.$nextTick(() => {
            this.scrollToChatBottom();
            const input = document.getElementById('chatInputField');
            if (input) input.focus();
        });
    },
    sendChatMessage() {
        if (!this.chatInputMessage.trim() || !this.chatActiveEmployee) return;
        const text = this.chatInputMessage.trim();
        const empId = this.chatActiveEmployee.id;
        if (!this.chatConversations[empId]) {
            this.chatConversations[empId] = [];
        }
        const now = new Date();
        const timeStr = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
        this.chatConversations[empId].push({
            sender: 'me',
            text: text,
            time: timeStr
        });
        this.chatInputMessage = '';
        this.$nextTick(() => {
            this.scrollToChatBottom();
        });
        
        setTimeout(() => {
            if (this.chatConversations[empId]) {
                const replies = [
                    'Siap mas, segera saya tindak lanjuti!',
                    'Noted mas, terima kasih arahannya. Segera saya update statusnya.',
                    'Baik mas, nanti saya kabari lagi begitu ada progres.'
                ];
                const randomReply = replies[Math.floor(Math.random() * replies.length)];
                const replyNow = new Date();
                const replyTime = String(replyNow.getHours()).padStart(2, '0') + ':' + String(replyNow.getMinutes()).padStart(2, '0');
                this.chatConversations[empId].push({
                    sender: 'other',
                    text: randomReply,
                    time: replyTime
                });
                this.$nextTick(() => {
                    this.scrollToChatBottom();
                });
            }
        }, 1100);
    },
    scrollToChatBottom() {
        const container = document.getElementById('chatMessagesContainer');
        if (container) {
            container.scrollTop = container.scrollHeight;
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
                        placeholder="Cari karyawan / aktivitas..." 
                        class="ml-2 px-3.5 py-1.5 text-xs rounded-full bg-white/90 backdrop-blur-md border border-slate-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 w-48 shadow-xs"
                    >
                </div>

                <!-- Sliders Filter Reset Button -->
                <button 
                    type="button" 
                    @click="employeeFilter = 'all'; employeeSearch = ''; showToast('Filter direset ke Semua')"
                    class="w-9 h-9 rounded-full bg-white/80 hover:bg-white backdrop-blur-md border border-white/80 shadow-xs flex items-center justify-center text-slate-500 hover:text-slate-900 transition-colors cursor-pointer"
                    title="Reset Filter"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </button>

                <!-- Semua (Counter Dinamis) -->
                <button 
                    type="button" 
                    @click="employeeFilter = 'all'"
                    :class="employeeFilter === 'all' ? 'bg-zinc-900 text-white font-semibold shadow-sm' : 'bg-white/75 hover:bg-white text-slate-600 hover:text-slate-900 border border-white/80 backdrop-blur-md shadow-xs'"
                    class="px-4 py-1.5 rounded-full text-xs font-medium transition-all cursor-pointer flex items-center gap-1"
                >
                    <span>Semua</span>
                    <span class="text-[11px] opacity-80" x-text="'(' + activeEmployees.length + ')'"></span>
                </button>

                <!-- Deep Work (Bekerja) -->
                <button 
                    type="button" 
                    @click="employeeFilter = 'deep_work'"
                    :class="employeeFilter === 'deep_work' ? 'bg-zinc-900 text-white font-semibold shadow-sm' : 'bg-white/75 hover:bg-white text-slate-600 hover:text-slate-900 border border-white/80 backdrop-blur-md shadow-xs'"
                    class="px-4 py-1.5 rounded-full text-xs font-medium flex items-center gap-1.5 transition-all cursor-pointer"
                >
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Deep Work</span>
                </button>

                <!-- In Meeting -->
                <button 
                    type="button" 
                    @click="employeeFilter = 'meeting'"
                    :class="employeeFilter === 'meeting' ? 'bg-zinc-900 text-white font-semibold shadow-sm' : 'bg-white/75 hover:bg-white text-slate-600 hover:text-slate-900 border border-white/80 backdrop-blur-md shadow-xs'"
                    class="px-4 py-1.5 rounded-full text-xs font-medium flex items-center gap-1.5 transition-all cursor-pointer"
                >
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span>In Meeting</span>
                </button>

                <!-- Reviewing PR -->
                <button 
                    type="button" 
                    @click="employeeFilter = 'review'"
                    :class="employeeFilter === 'review' ? 'bg-zinc-900 text-white font-semibold shadow-sm' : 'bg-white/75 hover:bg-white text-slate-600 hover:text-slate-900 border border-white/80 backdrop-blur-md shadow-xs'"
                    class="px-4 py-1.5 rounded-full text-xs font-medium flex items-center gap-1.5 transition-all cursor-pointer"
                >
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    <span>Review PR</span>
                </button>

                <!-- Tech -->
                <button 
                    type="button" 
                    @click="employeeFilter = 'tech'"
                    :class="employeeFilter === 'tech' ? 'bg-zinc-900 text-white font-semibold shadow-sm' : 'bg-white/75 hover:bg-white text-slate-600 hover:text-slate-900 border border-white/80 backdrop-blur-md shadow-xs'"
                    class="px-4 py-1.5 rounded-full text-xs font-medium transition-all cursor-pointer"
                >
                    Tech
                </button>

                <!-- Marketing -->
                <button 
                    type="button" 
                    @click="employeeFilter = 'marketing'"
                    :class="employeeFilter === 'marketing' ? 'bg-zinc-900 text-white font-semibold shadow-sm' : 'bg-white/75 hover:bg-white text-slate-600 hover:text-slate-900 border border-white/80 backdrop-blur-md shadow-xs'"
                    class="px-4 py-1.5 rounded-full text-xs font-medium transition-all cursor-pointer"
                >
                    Marketing
                </button>

                <!-- Finance -->
                <button 
                    type="button" 
                    @click="employeeFilter = 'finance'"
                    :class="employeeFilter === 'finance' ? 'bg-zinc-900 text-white font-semibold shadow-sm' : 'bg-white/75 hover:bg-white text-slate-600 hover:text-slate-900 border border-white/80 backdrop-blur-md shadow-xs'"
                    class="px-4 py-1.5 rounded-full text-xs font-medium transition-all cursor-pointer"
                >
                    Finance
                </button>
            </div>
        </div>

        <!-- 8 Stepped Tab Cards Grid: Karyawan Aktif & Kegiatan (Frosted Glass Effect) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <template x-for="emp in filteredEmployees()" :key="emp.id">
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
                                <p class="text-xs text-slate-500 mt-0.5 leading-snug truncate" x-text="emp.role"></p>
                            </div>
                        </div>

                        <!-- Inverted Concave Fillet Curve (Smooth Matching Glass Fillet) -->
                        <div class="w-[20px] h-[20px] text-white/80 shrink-0 self-end -ml-px relative z-10 pointer-events-none">
                            <svg viewBox="0 0 20 20" class="w-full h-full fill-current overflow-visible">
                                <path d="M 0 0 C 0 11.046 8.954 20 20 20 L 0 20 Z" />
                                <path d="M 0 0 C 0 11.046 8.954 20 20 20" fill="none" stroke="rgba(255,255,255,0.8)" stroke-width="1.2" />
                            </svg>
                        </div>

                        <!-- Notch Action Button: Chat / Hubungi Karyawan (Hover Green Micro-interaction) -->
                        <div class="w-14 shrink-0 flex items-center justify-end pb-3 pr-2.5 pl-1.5">
                            <button 
                                type="button" 
                                @click="openChatWith(emp)"
                                class="w-10 h-10 rounded-full bg-white/85 hover:bg-emerald-500 backdrop-blur-md border border-white/90 hover:border-emerald-400 text-slate-700 hover:text-white flex items-center justify-center transition-all duration-300 ease-out hover:scale-110 hover:shadow-lg hover:shadow-emerald-500/30 active:scale-95 cursor-pointer shrink-0 shadow-xs group/btn"
                                title="Chat / Hubungi Karyawan"
                            >
                                <svg class="w-5 h-5 transition-all duration-300 group-hover/btn:scale-110 group-hover/btn:-rotate-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </button>
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
                        <button 
                            type="button" 
                            @click="openChatWith(selectedEmployee); selectedEmployee = null" 
                            class="px-5 py-2 rounded-full bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-bold shadow-md cursor-pointer transition-all active:scale-95"
                        >
                            Hubungi Langsung
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- FLOATING MODERN CHAT PANEL (Bottom Right - Expandable & Collapsible) -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- FLOATING MODERN CHAT PANEL (Bottom Right - Expandable & Collapsible) -->
    <!-- ============================================================== -->
    <div 
        :class="chatMaximized 
            ? 'fixed inset-0 z-50 p-2 sm:p-5 md:p-8 flex items-center justify-center bg-slate-900/40 backdrop-blur-md pointer-events-auto' 
            : 'fixed bottom-6 right-6 z-50 flex flex-col items-end pointer-events-auto'"
    >
        <!-- 1. EXPANDED CHAT PANEL WINDOW -->
        <div 
            x-show="chatPanelOpen" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-6 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-6 scale-95"
            x-cloak
            :class="chatMaximized 
                ? 'w-full h-full max-w-6xl max-h-none mb-0 rounded-3xl shadow-[0_25px_60px_rgba(0,0,0,0.30)]' 
                : 'w-[360px] sm:w-[400px] h-[540px] max-h-[85vh] rounded-3xl mb-3 shadow-[0_20px_50px_rgba(0,0,0,0.18)]'"
            class="bg-white/95 backdrop-blur-2xl border border-white/80 flex flex-col overflow-hidden transition-all duration-300"
        >
            <!-- Panel Header -->
            <div class="px-4 py-3 bg-white/90 border-b border-slate-200/70 flex items-center justify-between shrink-0">
                <!-- Left Info (Employee info OR Inbox Title) -->
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <template x-if="chatActiveEmployee && !chatMaximized">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <button 
                                type="button" 
                                @click="chatActiveEmployee = null" 
                                class="p-1 -ml-1 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer"
                                title="Kembali ke Daftar Pesan"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <div class="relative shrink-0">
                                <img :src="chatActiveEmployee.avatar" :alt="chatActiveEmployee.name" class="w-9 h-9 rounded-full object-cover ring-2 ring-emerald-500/30">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-white absolute bottom-0 right-0"></span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-xs sm:text-base font-bold text-slate-900 truncate leading-tight" x-text="chatActiveEmployee.name"></h4>
                                <p class="text-[10px] text-emerald-600 font-medium truncate flex items-center gap-1 mt-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                                    <span x-text="chatActiveEmployee.role"></span>
                                </p>
                            </div>
                        </div>
                    </template>
                    <template x-if="chatActiveEmployee && chatMaximized">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-full bg-zinc-900 text-white flex items-center justify-center shrink-0 shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 leading-tight">Workspace Team Chat</h4>
                                <p class="text-[10px] text-slate-500 font-medium leading-tight mt-0.5">Direct Communication Hub</p>
                            </div>
                        </div>
                    </template>
                    <template x-if="!chatActiveEmployee">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-zinc-900 text-white flex items-center justify-center shrink-0 shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-tight">Team Messages</h4>
                                <p class="text-[10px] text-slate-500 font-medium leading-tight mt-0.5">8 Karyawan Terhubung</p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Right Controls: Maximize / Restore & Collapse Down -->
                <div class="flex items-center gap-1.5 shrink-0">
                    <!-- Maximize / Restore Toggle Button (Replaces X button) -->
                    <button 
                        type="button" 
                        @click="chatMaximized = !chatMaximized" 
                        class="w-7 h-7 rounded-full text-slate-500 hover:text-slate-900 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
                        :title="chatMaximized ? 'Restore / Perkecil' : 'Maximize / Layar Penuh'"
                    >
                        <template x-if="!chatMaximized">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                            </svg>
                        </template>
                        <template x-if="chatMaximized">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14h6m0 0v6m0-6L3 21m17-7h-6m0 0v6m0-6l7 7M4 10h6m0 0V4m0 6L3 3m17 7h-6m0 0V4m0 6l7-7" />
                            </svg>
                        </template>
                    </button>

                    <!-- Down Arrow (Collapse / Hide Panel to floating pill) -->
                    <button 
                        type="button" 
                        @click="chatPanelOpen = false; chatMaximized = false" 
                        class="w-7 h-7 rounded-full text-slate-500 hover:text-slate-900 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
                        title="Collapse / Sembunyikan"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Panel Body (Flex Dual-Pane when Maximized, Single-Pane when Popup) -->
            <div class="flex-1 flex min-h-0 overflow-hidden">
                <!-- Left Sidebar: Employee List (Always visible when chatMaximized; OR visible when !chatMaximized && !chatActiveEmployee) -->
                <div 
                    x-show="chatMaximized || (!chatMaximized && !chatActiveEmployee)"
                    :class="chatMaximized ? 'w-72 sm:w-80 border-r border-slate-200/70 flex flex-col bg-white/70 shrink-0' : 'flex-1 flex flex-col min-h-0 bg-slate-50/50'"
                >
                    <!-- Search Box -->
                    <div class="p-3 bg-white border-b border-slate-200/70 shrink-0">
                        <div class="relative">
                            <input 
                                type="text" 
                                x-model="chatSearch" 
                                placeholder="Cari rekan kerja..." 
                                class="w-full bg-slate-100/90 rounded-xl pl-8 pr-3 py-1.5 text-xs text-slate-800 placeholder-slate-400 border border-slate-200 focus:bg-white focus:outline-none focus:border-zinc-800"
                            >
                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Employee Conversations List -->
                    <div class="flex-1 overflow-y-auto divide-y divide-slate-100">
                        <template x-for="emp in activeEmployees.filter(e => e.name.toLowerCase().includes(chatSearch.toLowerCase()) || e.role.toLowerCase().includes(chatSearch.toLowerCase()))" :key="emp.id">
                            <div 
                                @click="openChatWith(emp)" 
                                class="p-3 hover:bg-white flex items-center gap-3 cursor-pointer transition-colors group"
                                :class="chatMaximized && chatActiveEmployee && chatActiveEmployee.id === emp.id ? 'bg-white shadow-xs border-l-4 border-l-emerald-600' : ''"
                            >
                                <div class="relative shrink-0">
                                    <img :src="emp.avatar" :alt="emp.name" class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-2xs">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-white absolute bottom-0 right-0"></span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between mb-0.5">
                                        <h5 class="text-xs font-bold text-slate-900 truncate group-hover:text-emerald-700 transition-colors" x-text="emp.name"></h5>
                                        <span class="text-[10px] text-slate-400" x-text="emp.online_duration"></span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 truncate" x-text="chatConversations[emp.id] ? chatConversations[emp.id][chatConversations[emp.id].length - 1].text : emp.current_activity"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Right Pane: Active Chat Conversation -->
                <div 
                    x-show="chatActiveEmployee || (chatMaximized && !chatActiveEmployee)"
                    class="flex-1 flex flex-col min-h-0 bg-slate-50/50"
                >
                    <template x-if="chatActiveEmployee">
                        <div class="flex-1 flex flex-col min-h-0">
                            <!-- Context Pill: Current Task / Live Activity -->
                            <div class="px-4 py-2.5 bg-slate-100/80 border-b border-slate-200/60 flex items-center justify-between gap-2 shrink-0">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="relative shrink-0" x-show="chatMaximized">
                                        <img :src="chatActiveEmployee.avatar" :alt="chatActiveEmployee.name" class="w-8 h-8 rounded-full object-cover ring-2 ring-emerald-500/30">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 ring-2 ring-white absolute bottom-0 right-0"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-bold text-slate-900 truncate leading-tight" x-show="chatMaximized" x-text="chatActiveEmployee.name"></h4>
                                        <div class="flex items-center gap-1.5 min-w-0 mt-0.5">
                                            <span class="w-2 h-2 rounded-full shrink-0" :class="{
                                                'bg-emerald-500': chatActiveEmployee.category === 'deep_work',
                                                'bg-amber-500': chatActiveEmployee.category === 'meeting',
                                                'bg-indigo-500': chatActiveEmployee.category === 'review'
                                            }"></span>
                                            <span class="text-[11px] font-semibold text-slate-600 truncate" x-text="'Sedang Mengerjakan: ' + chatActiveEmployee.current_activity"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="text-[10px] font-mono text-slate-400" x-text="chatActiveEmployee.online_duration"></span>
                                    <span class="text-[10px] font-medium px-2 py-0.5 rounded-full bg-white text-slate-600 border border-slate-200/80 shadow-2xs" x-text="chatActiveEmployee.status_label"></span>
                                </div>
                            </div>

                            <!-- Chat Messages Scroll Area -->
                            <div id="chatMessagesContainer" class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-3.5">
                                <template x-for="(msg, idx) in (chatConversations[chatActiveEmployee.id] || [])" :key="idx">
                                    <div class="flex flex-col" :class="msg.sender === 'me' ? 'items-end' : 'items-start'">
                                        <div 
                                            class="max-w-[85%] sm:max-w-[70%] px-4 py-2.5 rounded-2xl text-xs sm:text-sm leading-relaxed shadow-2xs"
                                            :class="msg.sender === 'me' 
                                                ? 'bg-zinc-900 text-white rounded-br-xs' 
                                                : 'bg-white text-slate-800 border border-slate-200/80 rounded-bl-xs'"
                                            x-text="msg.text"
                                        ></div>
                                        <span class="text-[9px] text-slate-400 mt-1 px-1" x-text="msg.time"></span>
                                    </div>
                                </template>
                            </div>

                            <!-- Chat Input Bar -->
                            <div class="p-3 bg-white border-t border-slate-200/70 flex items-center gap-2.5 shrink-0">
                                <input 
                                    id="chatInputField"
                                    type="text" 
                                    x-model="chatInputMessage" 
                                    @keydown.enter.prevent="sendChatMessage()"
                                    :placeholder="'Ketik pesan untuk ' + chatActiveEmployee.name.split(' ')[0] + '...'"
                                    class="flex-1 bg-slate-100/80 hover:bg-slate-100 focus:bg-white border border-slate-200 focus:border-emerald-500 rounded-full px-4 py-2 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all"
                                >
                                <button 
                                    type="button" 
                                    @click="sendChatMessage()" 
                                    class="w-9 h-9 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center shrink-0 shadow-xs cursor-pointer active:scale-95 transition-all"
                                    title="Kirim Pesan"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    <template x-if="!chatActiveEmployee && chatMaximized">
                        <div class="flex-1 flex flex-col items-center justify-center p-8 text-center text-slate-400">
                            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <h4 class="text-sm font-bold text-slate-700">Pilih Rekan Kerja</h4>
                            <p class="text-xs text-slate-500 mt-1 max-w-xs">Klik salah satu karyawan di daftar sebelah kiri untuk memulai obrolan langsung.</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- 2. COLLAPSED FLOATING BADGE / CAPSULE BUTTON -->
        <div 
            x-show="!chatMaximized"
            @click="chatPanelOpen = !chatPanelOpen"
            class="bg-white/90 hover:bg-white backdrop-blur-2xl border border-white/80 shadow-xl shadow-slate-300/40 rounded-full py-2 px-3.5 flex items-center gap-2.5 cursor-pointer transition-all hover:scale-105 active:scale-95 group select-none"
            title="Buka Panel Chat"
        >
            <!-- Icon Chat with Pulsing Live Dot -->
            <div class="w-8 h-8 rounded-full bg-zinc-900 text-white flex items-center justify-center shrink-0 shadow-xs relative">
                <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-white absolute -top-0.5 -right-0.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                </span>
            </div>

            <!-- Capsule Label -->
            <div class="flex items-center gap-2">
                <template x-if="chatActiveEmployee && !chatPanelOpen">
                    <div class="flex items-center gap-1.5 max-w-[150px]">
                        <span class="text-xs font-bold text-slate-900 truncate" x-text="'Chat: ' + chatActiveEmployee.name.split(' ')[0]"></span>
                    </div>
                </template>
                <template x-if="!chatActiveEmployee || chatPanelOpen">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-bold text-slate-900 tracking-tight">Direct Messages</span>
                        <span class="px-1.5 py-0.2 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">8</span>
                    </div>
                </template>
            </div>

            <!-- Expand / Collapse Chevron Indicator -->
            <svg 
                class="w-4 h-4 text-slate-400 group-hover:text-slate-700 transition-transform duration-200" 
                :class="chatPanelOpen ? 'rotate-180' : ''"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
        </div>
    </div>

</div>

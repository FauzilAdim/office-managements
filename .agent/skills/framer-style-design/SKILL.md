---
name: framer-style-design
description: Panduan desain UI bergaya Framer/produk SaaS modern (Linear, Notion, Stripe Dashboard). Gunakan skill ini setiap kali membuat atau mengedit tampilan (dashboard, halaman login, card, tabel, form) pada project Laravel + Tailwind CSS ini, supaya hasilnya tidak generic/template default Bootstrap.
---

# Framer-Style Design System

Kamu adalah frontend designer yang membangun UI bergaya produk SaaS modern (referensi: Framer.com, Linear, Notion, Stripe Dashboard, Vercel Dashboard). JANGAN pernah menghasilkan tampilan default Tailwind polos (card putih kotak siku, shadow generic, warna biru bawaan). Ikuti aturan berikut secara konsisten di semua halaman.

## 1. Warna
- Jangan pakai warna default Tailwind (`blue-500`, `gray-200`, dst) apa adanya. Definisikan palet kustom di `tailwind.config.js`:
  - Background utama: nyaris putih/off-white di light mode (`#FAFAFA` / `#F7F7F8`), dark slate sangat gelap untuk dark mode (`#0A0A0B` / `#101012`)
  - Warna aksen tunggal (pilih satu): indigo (`#6366F1`), atau violet (`#7C3AED`), atau emerald (`#10B981`) — pakai HANYA untuk elemen penting (tombol utama, badge status aktif, grafik utama)
  - Teks: bukan hitam pekat (`#000`), gunakan near-black (`#18181B`) untuk heading dan abu-abu (`#71717A`) untuk secondary text
  - Border: sangat tipis dan soft (`#E4E4E7` light / `#27272A` dark), jangan border tebal
- Status warna tugas dibedakan lewat warna soft-background + teks warna solid, bukan badge warna terang mencolok:
  - Belum dikerjakan: bg `#F4F4F5` teks `#71717A`
  - Sedang dikerjakan: bg `#EFF6FF` teks `#2563EB`
  - Review: bg `#FEF3C7` teks `#B45309`
  - Selesai: bg `#ECFDF5` teks `#059669`
  - Telat/Overdue: bg `#FEF2F2` teks `#DC2626`

## 2. Tipografi
- Gunakan font `Inter` atau `Geist` (import via Google Fonts atau bundled), jangan default sans-serif browser
- Heading tebal (`font-semibold` / `font-bold`) dengan ukuran jelas bertingkat: H1 `text-2xl md:text-3xl`, H2 `text-xl`, body `text-sm`
- Letter-spacing sedikit rapat untuk heading besar (`tracking-tight`)
- Line-height lega untuk body text (`leading-relaxed`)

## 3. Layout & Spacing
- Gunakan spacing generous, jangan padat. Padding card minimal `p-6`, gap antar elemen minimal `gap-4` sampai `gap-6`
- Sidebar dashboard: lebar tetap `w-64`, background sedikit berbeda dari main content (bukan warna sama persis) untuk depth
- Grid dashboard cards: gunakan `grid-cols-1 md:grid-cols-2 lg:grid-cols-4` dengan gap konsisten, jangan tabel HTML polos untuk ringkasan/statistik
- Max-width content area di dashboard besar: `max-w-7xl mx-auto`

## 4. Card & Container
- Border-radius besar dan konsisten: `rounded-xl` atau `rounded-2xl` (jangan `rounded` kecil atau `rounded-none`)
- Shadow sangat halus, bukan shadow gelap default: `shadow-sm` dengan border tipis (`border border-gray-200`), kombinasi border+shadow-sm lebih baik daripada shadow-lg saja
- Hover state pada card interaktif: transisi halus `transition-all duration-200`, sedikit elevasi (`hover:shadow-md hover:-translate-y-0.5`) — jangan efek hover yang kasar/instant

## 5. Data & Chart
- Statistik angka besar ditampilkan sebagai "stat card": angka besar (`text-3xl font-bold`) + label kecil di atas/bawah + indikator tren (panah naik/turun dengan warna hijau/merah) + mini sparkline/progress bar jika relevan
- Progress bar tugas: rounded-full, height tipis (`h-2`), warna sesuai status, dengan animasi transisi width
- Gunakan library chart ringan (Chart.js via CDN atau ApexCharts) untuk pie/donut chart status tugas per divisi — jangan tabel angka mentah saja
- Avatar karyawan: selalu bulat (`rounded-full`), fallback inisial nama dengan background warna soft jika tidak ada foto

## 6. Navigasi & Interaksi
- Active nav item: background soft aksen warna + teks warna aksen + border-left kecil sebagai indikator, bukan cuma bold text
- Semua tombol interaktif punya transisi (`transition-colors duration-150`)
- Empty state (misal "belum ada tugas") harus didesain dengan ilustrasi/icon sederhana + teks ringan, jangan blank kosong

## 7. Dark Mode
- Wajib support dark mode via Tailwind `dark:` variant, karena dashboard sering dipakai lama — bukan opsional

## 8. Yang HARUS dihindari
- Warna primary bawaan Tailwind (`bg-blue-600` polos untuk semua tombol)
- Card dengan `shadow-lg` tebal dan border tebal sekaligus
- Font default tanpa import khusus
- Tabel HTML mentah tanpa styling untuk data ringkasan/dashboard
- Spacing sempit (`p-2`, `gap-1`) di layout utama
- Semua warna status pakai warna solid terang (merah/hijau/kuning menyala penuh)

Setiap kali membuat komponen baru, cek ulang terhadap 8 poin di atas sebelum menganggap tugas selesai.
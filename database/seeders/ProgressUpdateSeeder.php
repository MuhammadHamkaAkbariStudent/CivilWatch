<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgressUpdate;
use App\Models\Report;

class ProgressUpdateSeeder extends Seeder
{
    public function run(): void
    {
        // Report 1: Jalan Berlubang (resolved) — 3 progress notes
        $report1 = Report::where('title', 'Jalan Berlubang di Depan SDN 1')->first();
        $updates1 = [
            [
                'note'       => 'Laporan telah diverifikasi dan diteruskan ke Dinas Pekerjaan Umum setempat untuk penanganan lebih lanjut.',
                'created_at' => now()->subDays(14),
                'updated_at' => now()->subDays(14),
            ],
            [
                'note'       => 'Tim lapangan telah turun ke lokasi untuk survei kondisi kerusakan dan estimasi material yang dibutuhkan.',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'note'       => 'Perbaikan jalan telah selesai dilaksanakan. Lubang telah ditambal menggunakan aspal hotmix. Terima kasih atas laporan warga.',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
        ];
        foreach ($updates1 as $update) {
            ProgressUpdate::create(array_merge(['report_id' => $report1->id], $update));
        }

        // Report 2: Pohon Tumbang (resolved) — 2 progress notes
        $report2 = Report::where('title', 'Pohon Tumbang Menutup Akses Jalan')->first();
        $updates2 = [
            [
                'note'       => 'Laporan diterima. Tim Dinas Kebersihan dan Pertamanan dijadwalkan tiba di lokasi dalam 1x24 jam.',
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ],
            [
                'note'       => 'Pohon tumbang telah berhasil dipotong dan disingkirkan dari badan jalan. Akses kendaraan sudah kembali normal.',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
        ];
        foreach ($updates2 as $update) {
            ProgressUpdate::create(array_merge(['report_id' => $report2->id], $update));
        }

        // Report 3: Lampu Jalan Mati (in_progress) — 2 progress notes
        $report3 = Report::where('title', 'Lampu Jalan Mati Sepanjang 200 Meter')->first();
        $updates3 = [
            [
                'note'       => 'Laporan valid dan telah dikoordinasikan dengan PLN dan Dinas Perhubungan untuk pemeriksaan instalasi listrik.',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'note'       => 'Teknisi sedang melakukan pemeriksaan kabel dan panel listrik di lokasi. Estimasi perbaikan 3-5 hari kerja.',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
        ];
        foreach ($updates3 as $update) {
            ProgressUpdate::create(array_merge(['report_id' => $report3->id], $update));
        }

        // Report 4: Drainase Tersumbat (in_progress) — 1 progress note
        $report4 = Report::where('title', 'Saluran Drainase Tersumbat dan Meluap')->first();
        ProgressUpdate::create([
            'report_id'  => $report4->id,
            'note'       => 'Laporan telah diverifikasi. Jadwal pembersihan saluran drainase telah dimasukkan ke antrian kerja Dinas PUPR minggu ini.',
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(3),
        ]);
    }
}
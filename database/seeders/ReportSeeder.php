<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\User;
use App\Models\District;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $citizens = User::where('role', 'citizen')->pluck('id')->toArray();
        $districts = District::pluck('id')->toArray();

        $reports = [
            // Status: resolved — reported 20 days ago, finished 5 days ago
            [
                'title' => 'Jalan Berlubang di Depan SDN 1',
                'description' => 'Terdapat lubang besar di tengah jalan dengan diameter sekitar 50cm dan kedalaman 20cm. Sangat membahayakan pengendara motor yang melintas, terutama saat malam hari karena minim penerangan.',
                'status' => 'resolved',
                'image' => 'reports/dummy-jalan.jpg',
                'user_id' => $citizens[0],
                'district_id' => $districts[0],
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(5),
            ],
            // Status: resolved — reported 15 days ago, finished 7 days ago
            [
                'title' => 'Pohon Tumbang Menutup Akses Jalan',
                'description' => 'Sebuah pohon besar tumbang akibat angin kencang dan menutup setengah badan jalan. Kendaraan roda empat tidak dapat melintas dengan normal. Butuh penanganan segera.',
                'status' => 'resolved',
                'image' => 'reports/dummy-pohon.jpg',
                'user_id' => $citizens[1],
                'district_id' => $districts[1],
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(7),
            ],
            // Status: in_progress — reported 10 days ago, still being handled
            [
                'title' => 'Lampu Jalan Mati Sepanjang 200 Meter',
                'description' => 'Sebanyak 5 titik lampu jalan di Jalan Ahmad Yani tidak menyala sejak 2 minggu lalu. Kondisi ini membuat jalanan sangat gelap dan rawan tindak kejahatan pada malam hari.',
                'status' => 'in_progress',
                'image' => 'reports/dummy-lampu.jpg',
                'user_id' => $citizens[2],
                'district_id' => $districts[0],
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(2),
            ],
            // Status: in_progress — reported 7 days ago, still being handled
            [
                'title' => 'Saluran Drainase Tersumbat dan Meluap',
                'description' => 'Saluran air di tepi jalan tersumbat sampah sehingga air meluap ke badan jalan saat hujan. Kondisi ini menyebabkan banjir kecil dan mengikis permukaan aspal jalan.',
                'status' => 'in_progress',
                'image' => 'reports/dummy-drainase.jpg',
                'user_id' => $citizens[3],
                'district_id' => $districts[2],
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(3),
            ],
            // Status: pending — reported 3 days ago, awaiting admin verification (Sembunyi dari Web)
            [
                'title' => 'Rambu Lalu Lintas Rusak dan Miring',
                'description' => 'Rambu batas kecepatan di persimpangan jalan utama kondisinya rusak parah dan miring hampir roboh. Berpotensi membahayakan pengguna jalan jika sampai terjatuh.',
                'status' => 'pending',
                'image' => 'reports/dummy-rambu.jpg',
                'user_id' => $citizens[4],
                'district_id' => $districts[3],
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            // Status 'published' — Divalidasi Admin, sedang cari Upvote di Web
            [
                'title' => 'Trotoar Retak dan Tidak Rata',
                'description' => 'Permukaan trotoar di sepanjang Jalan Diponegoro mengalami keretakan parah dan tidak rata. Kondisi ini sangat berbahaya bagi pejalan kaki, lansia, dan pengguna kursi roda.',
                'status' => 'published',
                'image' => 'reports/dummy-trotoar.jpg',
                'user_id' => $citizens[0],
                'district_id' => $districts[1],
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            // Status 'published' — Divalidasi Admin, sedang cari Upvote di Web
            [
                'title' => 'Tumpukan Sampah Liar di Pinggir Sungai',
                'description' => 'Terdapat penumpukan sampah liar yang cukup besar di bantaran sungai dekat permukiman warga. Menimbulkan bau tidak sedap dan berpotensi mencemari aliran sungai.',
                'status' => 'published',
                'image' => 'reports/dummy-sampah.jpg',
                'user_id' => $citizens[1],
                'district_id' => $districts[4],
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            // Status: rejected — reported 12 days ago, rejected by admin (Spam/Hoaks)
            [
                'title' => 'Cat Marka Jalan Sudah Pudar',
                'description' => 'Marka jalan berupa garis putih di beberapa titik sudah sangat pudar dan hampir tidak terlihat. Membingungkan pengendara terutama saat kondisi hujan dan jalan basah.',
                'status' => 'rejected',
                'image' => 'reports/dummy-marka.jpg',
                'user_id' => $citizens[2],
                'district_id' => $districts[0],
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(11),
            ],
        ];

        foreach ($reports as $report) {
            Report::create($report);
        }
    }
}
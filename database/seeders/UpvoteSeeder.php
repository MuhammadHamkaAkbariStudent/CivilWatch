<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Report;

class UpvoteSeeder extends Seeder
{
    public function run(): void
    {
        $citizens = User::where('role', 'citizen')->get();

        // Only reports that are verified and visible on the Laporan Publik
        $reportJalan    = Report::where('title', 'Jalan Berlubang di Depan SDN 1')->first();
        $reportPohon    = Report::where('title', 'Pohon Tumbang Menutup Akses Jalan')->first();
        $reportLampu    = Report::where('title', 'Lampu Jalan Mati Sepanjang 200 Meter')->first();
        $reportDrainase = Report::where('title', 'Saluran Drainase Tersumbat dan Meluap')->first();

        // Jalan Berlubang — 4 upvotes
        $reportJalan->upvotes()->syncWithoutDetaching([
            $citizens[0]->id,
            $citizens[1]->id,
            $citizens[2]->id,
            $citizens[3]->id,
        ]);

        // Pohon Tumbang — 3 upvotes
        $reportPohon->upvotes()->syncWithoutDetaching([
            $citizens[0]->id,
            $citizens[2]->id,
            $citizens[4]->id,
        ]);

        // Lampu Jalan Mati — 5 upvotes (most urgent, still in_progress)
        $reportLampu->upvotes()->syncWithoutDetaching([
            $citizens[0]->id,
            $citizens[1]->id,
            $citizens[2]->id,
            $citizens[3]->id,
            $citizens[4]->id,
        ]);

        // Drainase Tersumbat — 2 upvotes
        $reportDrainase->upvotes()->syncWithoutDetaching([
            $citizens[1]->id,
            $citizens[3]->id,
        ]);
    }
}
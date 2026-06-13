<?php

use App\Models\User;
use App\Models\Report;
use App\Models\District;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

test('deleting a report deletes its image file from storage', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $district = District::create(['name' => 'Kecamatan Test File']);

    // Generate a dummy uploaded file
    $file = UploadedFile::fake()->image('report_test.jpg');
    $imagePath = $file->store('reports', 'public');

    // Verify file exists in fake storage
    Storage::disk('public')->assertExists($imagePath);

    // Create report
    $report = Report::create([
        'user_id' => $user->id,
        'district_id' => $district->id,
        'title' => 'Laporan Test Hapus File',
        'description' => 'Deskripsi laporan test hapus file.',
        'image' => $imagePath,
        'status' => 'pending',
    ]);

    // Delete report
    $report->delete();

    // Verify file is deleted from storage
    Storage::disk('public')->assertMissing($imagePath);
});

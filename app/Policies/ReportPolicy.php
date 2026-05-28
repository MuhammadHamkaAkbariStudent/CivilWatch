<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * Edit: hanya pemilik laporan DAN status masih pending.
     */
    public function update(User $user, Report $report): bool
    {
        return $user->id === $report->user_id
            && $report->isEditable();
    }

    /**
     * Hapus: hanya pemilik laporan DAN status masih pending.
     */
    public function delete(User $user, Report $report): bool
    {
        return $user->id === $report->user_id
            && $report->isEditable();
    }
}
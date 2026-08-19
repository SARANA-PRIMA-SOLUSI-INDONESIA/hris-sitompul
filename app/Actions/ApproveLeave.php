<?php

namespace App\Actions;

use App\Models\Leave;
use App\Models\User;

class ApproveLeave
{
    public static function run(Leave $leave, User $approver): Leave
    {
        if ($leave->status !== Leave::STATUS_MENUNGGU) {
            throw new \RuntimeException('Cuti tidak dalam status menunggu persetujuan.');
        }

        $leave->update([
            'status' => Leave::STATUS_DISETUJUI,
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);

        return $leave->fresh();
    }

    public static function reject(Leave $leave, User $approver, string $reason): Leave
    {
        if ($leave->status !== Leave::STATUS_MENUNGGU) {
            throw new \RuntimeException('Cuti tidak dalam status menunggu persetujuan.');
        }

        $leave->update([
            'status' => Leave::STATUS_DITOLAK,
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'alasan_penolakan' => $reason,
        ]);

        return $leave->fresh();
    }
}

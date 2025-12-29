<?php

namespace App\Services;

use App\Models\MemberEkstra;
use Illuminate\Validation\ValidationException;

class EkstraApprovalService
{
    public function approve(MemberEkstra $member): void
    {
        if ($member->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' => 'Pendaftaran ini sudah diproses.',
            ]);
        }

        $member->update([
            'status' => 'approved',
            'is_active' => true,
        ]);
    }

    public function reject(MemberEkstra $member): void
    {
        if ($member->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' => 'Pendaftaran ini sudah diproses.',
            ]);
        }

        $member->update([
            'status' => 'rejected',
            'is_active' => false,
        ]);
    }
}

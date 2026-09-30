<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Get list of real-time system notifications for the given user.
     */
    public function getNotifications(?User $user = null): Collection
    {
        $notifications = collect();
        $readIds = session('read_notifications', []);

        if (!$user) {
            return $notifications;
        }

        $isAdmin = $user->role === 'admin';

        // 1. Pending Loans (Menunggu Persetujuan)
        $pendingQuery = Peminjaman::with(['user', 'barang'])->where('status_pinjam', 'menunggu')->latest('id_pinjam');
        if (!$isAdmin) {
            $pendingQuery->where('id_user', $user->id_user);
        }
        foreach ($pendingQuery->take(10)->get() as $pinjam) {
            $notifId = 'pinjam_menunggu_' . $pinjam->id_pinjam;
            $notifications->push([
                'id' => $notifId,
                'type' => 'warning',
                'icon' => 'bi-clock-history',
                'icon_color' => 'text-warning',
                'title' => $isAdmin ? 'Pengajuan Peminjaman Baru' : 'Menunggu Persetujuan Admin',
                'message' => $isAdmin
                    ? ($pinjam->user->name ?? 'User') . ' mengajukan peminjaman aset ' . ($pinjam->barang->nama_barang ?? 'Barang') . '.'
                    : 'Permintaan peminjaman ' . ($pinjam->barang->nama_barang ?? 'Barang') . ' sedang diproses admin.',
                'timestamp' => $this->formatTime($pinjam->tgl_pinjam),
                'link' => $isAdmin ? route('approvals.index') : route('dashboard'),
                'read' => in_array($notifId, $readIds),
            ]);
        }

        // 2. Overdue Loans (Terlambat Pengembalian)
        $overdueQuery = Peminjaman::with(['user', 'barang'])
            ->where('status_pinjam', 'dipinjam')
            ->whereNotNull('tgl_kembali')
            ->where('tgl_kembali', '<', now());
        if (!$isAdmin) {
            $overdueQuery->where('id_user', $user->id_user);
        }
        foreach ($overdueQuery->take(10)->get() as $pinjam) {
            $notifId = 'pinjam_terlambat_' . $pinjam->id_pinjam;
            $notifications->push([
                'id' => $notifId,
                'type' => 'danger',
                'icon' => 'bi-exclamation-octagon-fill',
                'icon_color' => 'text-danger',
                'title' => 'Peminjaman Melewati Batas Waktu',
                'message' => $isAdmin
                    ? 'Aset ' . ($pinjam->barang->nama_barang ?? 'Barang') . ' yang dipinjam oleh ' . ($pinjam->user->name ?? 'User') . ' telah melewati batas tanggal pengembalian (' . optional($pinjam->tgl_kembali)->format('d M Y') . ').'
                    : 'Peminjaman ' . ($pinjam->barang->nama_barang ?? 'Barang') . ' Anda telah melewati batas waktu pengembalian. Harap segera kembalikan.',
                'timestamp' => $this->formatTime($pinjam->tgl_kembali),
                'link' => route('dashboard'),
                'read' => in_array($notifId, $readIds),
            ]);
        }

        // 3. Approved / Active Loans (Disetujui / Sedang Dipinjam)
        $activeQuery = Peminjaman::with(['user', 'barang'])
            ->where('status_pinjam', 'dipinjam')
            ->latest('id_pinjam');
        if (!$isAdmin) {
            $activeQuery->where('id_user', $user->id_user);
        }
        foreach ($activeQuery->take(5)->get() as $pinjam) {
            $notifId = 'pinjam_disetujui_' . $pinjam->id_pinjam;
            $notifications->push([
                'id' => $notifId,
                'type' => 'info',
                'icon' => 'bi-clipboard-check-fill',
                'icon_color' => 'text-primary',
                'title' => $isAdmin ? 'Aktivitas Peminjaman Aktif' : 'Peminjaman Disetujui',
                'message' => $isAdmin
                    ? ($pinjam->user->name ?? 'User') . ' sedang meminjam ' . ($pinjam->barang->nama_barang ?? 'Barang') . '.'
                    : 'Pengajuan peminjaman ' . ($pinjam->barang->nama_barang ?? 'Barang') . ' Anda telah disetujui.',
                'timestamp' => $this->formatTime($pinjam->tgl_pinjam),
                'link' => route('dashboard'),
                'read' => in_array($notifId, $readIds),
            ]);
        }

        // 4. Returned Items (Pengembalian Selesai)
        $returnedQuery = Peminjaman::with(['user', 'barang'])
            ->where('status_pinjam', 'dikembalikan')
            ->latest('id_pinjam');
        if (!$isAdmin) {
            $returnedQuery->where('id_user', $user->id_user);
        }
        foreach ($returnedQuery->take(5)->get() as $pinjam) {
            $notifId = 'pinjam_kembali_' . $pinjam->id_pinjam;
            $notifications->push([
                'id' => $notifId,
                'type' => 'success',
                'icon' => 'bi-check-circle-fill',
                'icon_color' => 'text-success',
                'title' => 'Pengembalian Selesai',
                'message' => 'Aset ' . ($pinjam->barang->nama_barang ?? 'Barang') . ' telah berhasil dikembalikan ke inventaris.',
                'timestamp' => $this->formatTime($pinjam->tgl_kembali),
                'link' => route('dashboard'),
                'read' => in_array($notifId, $readIds),
            ]);
        }

        // 5. Newly added assets (for all users)
        $recentBarangs = Barang::latest('id_barang')->take(5)->get();
        foreach ($recentBarangs as $barang) {
            $notifId = 'barang_baru_' . $barang->id_barang;
            $notifications->push([
                'id' => $notifId,
                'type' => 'primary',
                'icon' => 'bi-box-seam',
                'icon_color' => 'text-primary',
                'title' => 'Aset Baru Ditambahkan',
                'message' => $barang->nama_barang . ' (' . $barang->kode_barang . ') siap digunakan di ' . ($barang->lokasi->nama_lokasi ?? 'Lokasi') . '.',
                'timestamp' => $this->formatTime($barang->tgl_pembelian),
                'link' => route('assets.index'),
                'read' => in_array($notifId, $readIds),
            ]);
        }

        return $notifications->sortBy(fn($n) => $n['read'] ? 1 : 0)->values();
    }

    /**
     * Get count of unread notifications.
     */
    public function getUnreadCount(?User $user = null): int
    {
        return $this->getNotifications($user)->where('read', false)->count();
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(string $notificationId): void
    {
        $readIds = session('read_notifications', []);
        if (!in_array($notificationId, $readIds)) {
            $readIds[] = $notificationId;
            session(['read_notifications' => $readIds]);
        }
    }

    /**
     * Mark all notifications as read for current session.
     */
    public function markAllAsRead(?User $user = null): void
    {
        $allIds = $this->getNotifications($user)->pluck('id')->all();
        session(['read_notifications' => $allIds]);
    }

    private function formatTime($time): string
    {
        if (!$time) {
            return 'Baru saja';
        }
        try {
            return Carbon::parse($time)->diffForHumans();
        } catch (\Throwable $e) {
            return 'Baru saja';
        }
    }
}


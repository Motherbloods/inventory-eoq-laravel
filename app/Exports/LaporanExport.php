<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;

class LaporanExport implements FromView, WithTitle, ShouldAutoSize
{
    private string $safeTitle;

    public function __construct(
        private string $viewName,
        private array $data,
        string $title = 'Laporan'
    ) {
        $this->safeTitle = mb_substr($title, 0, 31);

        // Optional: Log untuk debugging (bisa dihapus setelah fix berhasil)
        if (mb_strlen($title) > 31) {
            \Log::warning('Excel sheet title truncated', [
                'original' => $title,
                'original_length' => mb_strlen($title),
                'truncated' => $this->safeTitle,
                'truncated_length' => mb_strlen($this->safeTitle),
            ]);
        }
    }

    public function view(): View
    {
        return view($this->viewName, $this->data);
    }

    public function title(): string
    {
        // Return title yang sudah divalidasi di constructor
        return $this->safeTitle;
    }
}
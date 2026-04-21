<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;

class LaporanExport implements FromView, WithTitle, ShouldAutoSize
{
    public function __construct(
        private string $viewName,
        private array $data,
        private string $title = 'Laporan'
    ) {
    }

    public function view(): View
    {
        return view($this->viewName, $this->data);
    }

    public function title(): string
    {
        return $this->title;
    }
}
<?php

namespace App\Exports;

use App\Models\Catatan;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CatatanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection(): Enumerable
    {
        return Catatan::where('user_id', auth()->id())
            ->where('is_archived', false)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return ['Judul', 'Isi', 'Tanggal Dibuat'];
    }

    public function map(mixed $catatan): array
    {
        return [
            $catatan->judul,
            $catatan->isi,
            $catatan->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet): ?array
    {
        // Header tebal dengan latar abu-abu (A sampai C)
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => 'E5E7EB'],
            ],
        ]);

        // Border untuk seluruh tabel
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:C{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => 'thin'],
            ],
        ]);

        // Kolom Isi: teks panjang otomatis wrap
        $sheet->getStyle("B2:B{$lastRow}")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('B')->setAutoSize(false);
        $sheet->getColumnDimension('B')->setWidth(60);

        return [];
    }
}
<?php

namespace App\Exports;

use App\Models\Users\Mahasiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Color;

class MahasiswaExport implements FromCollection, WithHeadings, WithStyles
{
    protected $data;

    public function __construct($filter = null) {
        if ($filter) {
            $this->data = Mahasiswa::getMahasiswaByFilter($filter);
        } else {
            $this->data = Mahasiswa::all();
        }
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection() {
        return $this->data;
    }

    public function headings(): array {
        return array_map('strtoupper', array_keys($this->data->first()->getAttributes()));;
    }

    public function styles($sheet) {
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => Color::COLOR_WHITE],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '3498db'], // Warna background header
            ],
        ];

        // Terapkan gaya ke setiap kolom header
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($headerStyle);
    }
}

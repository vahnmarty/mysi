<?php

namespace App\Imports;

use App\Models\School;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SchoolsImport implements ToModel, WithStartRow, SkipsOnFailure
{
    use SkipsFailures;
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (!isset($row[0])) {
            return null;
        }

        return new School([
            'cds_code' => $row[1],
            'county' => $row[2],
            'city' => $row[3],
            'district' => $row[4],
            'name' => $row[5],
            'status_flag' => $row[6] == 'Active',
            'funding_type' => $row[7],
            'educational_program_type' => $row[8],
            'entity_type' => $row[9],
            'education_level' => $row[10],
            'low_grade' => $row[11],
            'high_grade' => $row[12],
            'charter_flag' => $row[13],
            'magnet_flag' => $row[14],
            'public_flag' => $row[15],
            'catholic_flag' => $row[16] ?? '',
            'independent_flag' => $row[17] ?? '',
        ]);
    }

    public function startRow() : int
    {
        return 2;
    }

    // public function headingRow(): int
    // {
    //     return 1;
    // }
}

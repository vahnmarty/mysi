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
            'cds_code' => $row[0],
            'county' => $row[1],
            'city' => $row[2],
            'district' => $row[3],
            'name' => $row[4],
            'status_flag' => $row[5] == 'Active',
            'funding_type' => $row[6],
            'educational_program_type' => $row[7],
            'entity_type' => $row[8],
            'education_level' => $row[9],
            'low_grade' => $row[10],
            'high_grade' => $row[11],
            'charter_flag' => $row[12],
            'magnet_flag' => $row[13],
            'public_flag' => $row[14],
            'catholic_flag' => $row[15],
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

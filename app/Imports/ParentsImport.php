<?php

namespace App\Imports;

use App\Models\Parents;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ParentsImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            Parents::updateOrCreate([
                'id' => $row[0],
            ],[
                'account_id' => $row[1],
                'sf_contact_id' => $row[2],
                'sf_account_id' => $row[3],
                'record_type_id' => $row[4],
                'is_primary' => $row[5],
                'salutation' => $row[6],
                'first_name' => $row[7],
                'middle_name' => $row[8],
                'last_name' => $row[9],
                'suffix' => $row[10],
                'preferred_first_name' => $row[11],
                'personal_email' => $row[12],
                'mobile_phone' => $row[13],
            ]);
        }
    }

    public function startRow() : int
    {
        return 2;
    }
}

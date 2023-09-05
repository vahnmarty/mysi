<?php

namespace App\Imports;

use App\Models\Address;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class LiveAddressImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            Address::updateOrCreate([
                'id' => $row[0],
            ],[
                'account_id' => $row[1],
                'sf_account_id' => (string) $row[2],
                'sf_residence_id' => (string) $row[3],
                'record_type_id' => (string) $row[4],
                'address_type' => (string) $row[5],
                'address' => (string) $row[6],
                'city' => (string) $row[7],
                'state' => (string) $row[8],
                'zip_code' => (string) $row[9],
                'phone_number' => (string) $row[10],
                'created_at' => (string) $row[11],
                'updated_at' => (string) $row[12],
            ]);
        }
    }

    public function startRow() : int
    {
        return 2;
    }
}

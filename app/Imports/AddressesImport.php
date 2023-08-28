<?php

namespace App\Imports;

use App\Models\Address;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AddressesImport implements ToCollection, WithStartRow
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
                'address_type' => $row[2],
                'address' => $row[3],
                'city' => $row[4],
                'state' => $row[5],
                'zip_code' => $row[6],
                'phone_number' => (string) $row[7],
                'sf_account_id' => $row[8],
                'sf_residence_id' => $row[9]
            ]);
        }
    }

    public function startRow() : int
    {
        return 2;
    }
}

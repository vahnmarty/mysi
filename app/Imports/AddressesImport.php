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
                'address_type' => (string) $row[2],
                'address' => (string) $row[3],
                'city' => (string) $row[4],
                'state' => (string) $row[5],
                'zip_code' => (string) $row[6],
                'phone_number' => (string) (string) $row[7],
                'sf_account_id' => (string) $row[8],
                'sf_residence_id' => (string) $row[9]
            ]);
        }
    }

    public function startRow() : int
    {
        return 2;
    }
}

<?php

namespace App\Imports;

use App\Models\Account;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AccountsImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            Account::updateOrCreate([
                'id' => $row[0],
            ],[
                'account_name' => (string) $row[1],
                'phone' => (string) $row[2],
                'sf_account_id' => (string) $row[3],
                'record_type_id' => (string) $row[4],
            ]);
        }
    }

    public function startRow() : int
    {
        return 2;
    }
}

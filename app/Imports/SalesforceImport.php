<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SalesforceImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'accounts' => new AccountsImport(),
            // 'parents' => new ParentsImport(),
            // 'children' => new ChildrenImport(),
            // 'addresses' => new AddressesImport()
        ];
    }
}

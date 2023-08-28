<?php

namespace App\Imports;

use App\Models\Child;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ChildrenImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            if(!Child::where('id', $row[0])->exists())
            {
                $child = new Child;
                $child->fill([
                    'id' => $row[0],
                    'account_id' => $row[1],
                    'sf_contact_id' => $row[2],
                    'sf_account_id' => $row[3],
                    'record_type_id' => $row[4],
                    'first_name' => $row[5],
                    'middle_name' => $row[6],
                    'last_name' => $row[7],
                    'suffix' => $row[8],
                    'preferred_first_name' => $row[9],
                    'personal_email' => $row[10],
                    'mobile_phone' => $row[11],
                    'gender' => $row[12],
                    'current_grade' => $row[13],
                    'current_school' => $row[14],
                ]);

                $child->saveQuietly();

                $child->expected_graduation_year = $child->getExpectedGraduationYear();
                $child->expected_enrollment_year = $child->getExpectedEnrollmentYear();
                $child->saveQuietly();
            }
            

            
        }
    }

    public function startRow() : int
    {
        return 2;
    }
}

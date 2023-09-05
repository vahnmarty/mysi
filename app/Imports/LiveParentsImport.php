<?php

namespace App\Imports;

use App\Models\Parents;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class LiveParentsImport implements ToCollection, WithStartRow
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
                'sf_contact_id' => (string) $row[2],
                'sf_account_id' => (string) $row[3],
                'record_type_id' => (string) $row[4],
                'is_primary' =>  $row[5],
                'salutation' => (string) $row[6],
                'first_name' => (string) $row[7],
                'middle_name' => (string) $row[8],
                'last_name' => (string) $row[9],
                'suffix' => (string) $row[10],
                'preferred_first_name' => (string) $row[11],
                'personal_email' => (string) $row[12],
                'mobile_phone' => (string) $row[13],
                'si_alumni_flag' => (string) $row[14],
                'relationship_type' => (string) $row[15],
                'address_location' => (string) $row[16],
                'alternate_email' => (string) $row[17],
                'employment_status' => (string) $row[18],
                'employer' => (string) $row[19],
                'job_title' => (string) $row[20],
                'work_email' => (string) $row[21],
                'work_phone' => (string) $row[22],
                'work_phone_ext' => (string) $row[23],
                'schools_attended' => (string) $row[24],
                'living_situation' => (string) $row[25],
                'deceased_flag' =>  $row[26],
                'communication_preferences' => (string) $row[27],
                'graduation_year' => $row[28] ?? null,
                'created_at' => $row[29],
                'updated_at' => $row[30],
                'deleted_at' => $row[31]
            ]);
        }
    }

    public function startRow() : int
    {
        return 2;
    }
}

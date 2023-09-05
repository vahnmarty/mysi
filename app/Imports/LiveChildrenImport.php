<?php

namespace App\Imports;

use App\Models\Child;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class LiveChildrenImport implements ToCollection, WithStartRow
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
                    'relationship_type' => $row[12],
                    'living_situation' => $row[13],
                    'address_location' => $row[14],
                    'birthdate' => !$row[15] ? date('Y-m-d', strtotime($row[15])) : null,
                    'gender' => $row[16],
                    'race' => $row[17],
                    'ethnicity' => $row[18],
                    'current_grade' => $row[19],
                    'current_school' => $row[20],
                    'current_school_not_listed' => $row[21],
                    'religion' => $row[22], 
                    'religion_other' => $row[23],
                    'religious_community' => $row[24],
                    'religious_community_location' => $row[25],
                    'baptism_year' => $row[26],
                    'confirmation_year' => $row[27],
                    'si_email' => $row[28],
                    'si_email_password' => $row[29],
                    'powerschool_id' => $row[30],
                    't_shirt_size' => $row[31],
                    'performing_arts_flag' => $row[32],
                    'performing_arts_programs' => $row[33],
                    'performing_arts_other' => $row[34],
                    'medication_information' => $row[35],
                    'allergies_information' => $row[36],
                    'health_information' => $row[37],
                    'multi_racial_flag' => $row[38],
                    'expected_graduation_year' => $row[39],
                    'expected_enrollment_year' => $row[40],
                    'graduated_hs_flag' => $row[41],
                    'graduation_year' => $row[42],
                    'graduated_at_si' => $row[43],
                    'created_at' => $row[44],
                    'updated_at' => $row[45],
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

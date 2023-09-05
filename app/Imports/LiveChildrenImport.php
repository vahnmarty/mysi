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
                    'id' => (string) $row[0],
                    'account_id' => (string) $row[1],
                    'sf_contact_id' => (string) $row[2],
                    'sf_account_id' => (string) $row[3],
                    'record_type_id' => (string) $row[4],
                    'first_name' => (string) $row[5],
                    'middle_name' => (string) $row[6],
                    'last_name' => (string) $row[7],
                    'suffix' => (string) $row[8],
                    'preferred_first_name' => (string) $row[9],
                    'personal_email' => (string) $row[10],
                    'mobile_phone' => (string) $row[11],
                    'relationship_type' => (string) $row[12],
                    'living_situation' => (string) $row[13],
                    'address_location' => (string) $row[14],
                    'birthdate' =>  !$row[15] ? date('Y-m-d', strtotime($row[15])) : null,
                    'gender' => (string) $row[16],
                    'race' => (string) $row[17],
                    'ethnicity' => (string) $row[18],
                    'current_grade' => (string) $row[19],
                    'current_school' => (string) $row[20],
                    'current_school_not_listed' => (string) $row[21],
                    'religion' => (string) $row[22], 
                    'religion_other' => (string) $row[23],
                    'religious_community' => (string) $row[24],
                    'religious_community_location' => (string) $row[25],
                    'baptism_year' =>  $row[26],
                    'confirmation_year' =>  $row[27],
                    'si_email' => (string) $row[28],
                    'si_email_password' => (string) $row[29],
                    'powerschool_id' => (string) $row[30],
                    't_shirt_size' => (string) $row[31],
                    'performing_arts_flag' =>  $row[32],
                    'performing_arts_programs' => (string) $row[33],
                    'performing_arts_other' => (string) $row[34],
                    'medication_information' => (string) $row[35],
                    'allergies_information' => (string) $row[36],
                    'health_information' => (string) $row[37],
                    'multi_racial_flag' =>  $row[38],
                    'expected_graduation_year' => $row[39],
                    'expected_enrollment_year' => $row[40],
                    'graduated_hs_flag' => $row[41],
                    'graduation_year' =>  $row[42],
                    'graduated_at_si' =>  $row[43],
                    'created_at' => $row[44],
                    'updated_at' => $row[45],
                    'deleted_at' => $row[46],
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

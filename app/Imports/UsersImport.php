<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'name'     => $row['name'],
            'email'    => $row['email'],
            'type'    => $row['type'],
            'department'    => $row['department'],
            'college'    => $row['college'],
            'school_id'    => $row['school_id'],
            'course'    => $row['course'],
            'phone'    => $row['phone'],
            'password' => \Hash::make($row['password']),
        ]);
    }
}

<?php

namespace App\Imports;

use App\Models\Pincode;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PincodesImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // The header "PINCODE" will be converted to slug "pincode"
        $code = $row['pincode'] ?? null;


        if (!$code) {
            return null;
        }

        // We use updateOrCreate to prevent duplicate entry errors and just ensure it's there
        return Pincode::updateOrCreate(
            ['code' => $code],
            ['status' => 'active']
        );
    }

    public function rules(): array
    {
        return [
            // 'pincode' => 'required',
        ];
    }
}

<?php

namespace App\Imports;

use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TransactionImport implements ToModel, WithStartRow
{
    public function startrow(): int
    {
        return 10;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $admin_id = Auth::guard('admin')->user()->id;
        if (isset($row[0]) && isset($row[1]) && strtotime($row[1])) {
            return new Transactions([
                'date_time' => Carbon::parse($row[1])->format('Y-m-d H:i:s'),
                'description' => $row[2],
                'debit' => $row[3],
                'credit' => $row[4],
                'status' => $row[5],
                'channel' => $row[7],
                'admin_id' => $admin_id
            ]);
        }
    }
}

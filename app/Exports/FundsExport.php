<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FundsExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection(): Collection
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'ID',
            'UserName',
            'FullName',
            'Amount',
            'Txn Type',
            'Notes',
            'Txn Mode',
            'Timestamp',
        ];
    }
}

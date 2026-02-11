<?php

namespace App\Exports;

use App\Models\Incomes;
use App\Models\Expenses;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PocketExport implements FromCollection, WithHeadings
{
    protected $pocket_id;
    protected $type;
    protected $date;

    public function __construct($pocket_id, $type, $date)
    {
        $this->pocket_id = $pocket_id;
        $this->type = $type;
        $this->date = $date;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Amount',
            'Notes',
            'Date Created',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = null;

        switch ($this->type) {
            case 'INCOME':
                $query = Incomes::query();
                break;
            case 'EXPENSE':
                $query = Expenses::query();
                break;
            default:
                return collect([]);
        }

        return $query->where('pocket_id', $this->pocket_id)
                ->whereDate('created_at', $this->date)
                ->select('id', 'amount', 'notes', 'created_at')
                ->get();
    }
}

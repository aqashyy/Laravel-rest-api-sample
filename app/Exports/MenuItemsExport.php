<?php

namespace App\Exports;

use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MenuItemsExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct(public Collection $records)
    {


    }
    public function collection()
    {
        return $this->records;
    }
    public function map($menuitems): array
    {
        $payment = "unpaid";
        return [
            $menuitems->title,
            $menuitems->alias,
            $payment
        ];
    }

    public function headings(): array
    {
        return [
            'Title',
            'Alias',
            'Payment'
        ];
    }
}

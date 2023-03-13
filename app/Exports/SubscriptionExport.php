<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class SubscriptionExport implements FromCollection,WithHeadings,WithEvents,ShouldAutoSize
{   
    public function __construct(public Array $suscriptionIds)
    {}
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
         return $this->subscription();
    }
    public function subscription() {
        return User::join('module_users','module_users.user_id','=','users.id')
        ->join('modules','modules.id','=','module_users.module_id')
        ->leftJoin('countries','countries.id','=','users.country')
        ->whereIn('module_users.id',$this->suscriptionIds)
        ->get(['first_name','last_name','email','modules.title','countries.name','module_users.somme']);
    }
    public function headings(): array
    {
        return [
            'NOM',
            'PRÉNOM',
            'EMAIL',
            'SOUSCRIT',
            'PAYS',
            'PRIX'
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:N1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            },
        ];
    }
}

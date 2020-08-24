<?php

namespace App\Exports;

// use App\ExportData;
use Maatwebsite\Excel\Concerns\FromArray;
use App\Imports\ImportUsers;
use Exception;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Fill;

use Exportable;
// WithCustomStartCell
class ExportData implements FromArray, ShouldAutoSize, WithEvents
{

    private function getNameFromNumber($num)
    {
        $numeric = ($num - 1) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($num - 1) / 26);

        if ($num2 > 0) {
            return $this->getNameFromNumber($num2) . $letter;
        } else {
            return $letter;
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $rows = $event->sheet->getDelegate()->toArray();
                $event->sheet->getStyle('A1')->applyFromArray([

                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => [
                            'argb' => 'FFF9EF5B'
                        ]
                    ]
                ]);
                $colLength = count($this->domainDetailArray) + 3;
                $event->sheet->getStyle('A'.$colLength)->applyFromArray([

                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => [
                            'argb' => 'FFF9EF5B'
                        ]
                    ]
                ]);
                $event->sheet->getStyle('B1:H1')->applyFromArray([

                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => [
                            'argb' => 'FFF7AE5B'
                        ]
                    ]
                ]);
                // dd(count($this->domainDetailArray);
                // dd(count($this->domainDetailArray));
                
                $event->sheet->getStyle('B'.$colLength.':H'.$colLength)->applyFromArray([

                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => [
                            'argb' => 'FF85EC49'
                        ]
                    ]
                ]);

                // dd($shee);
                // $event->sheet->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

                // $event->sheet->getParent()->getDefaultStyle()->applyFromArray([
                //     'font' => [
                //         'name' => 'Arial',
                //         'size' => 42
                //     ]
                // ]);
                // for($key = 1; $key < ($this->NoofDays * 3) + 1; $key++) {
                //     $letter = $this->getNameFromNumber($key + 1);
                //     if($key > 0){
                //         for($i = 2; $i < $this->usersCount + 4; $i++)
                //         {
                //             if($key%3 == 1){
                //                 // dd($letter.$i);
                //                 $event->sheet->getStyle($letter.$i.':'.$letter.$i)->applyFromArray([

                //                     'fill' => [
                //                         'fillType' => Fill::FILL_SOLID,
                //                         'color' => [
                //                             'argb' => 'FFF9EF5B'
                //                         ]
                //                     ]
                //                 ]);
                //             }
                //             if($key%3 == 2){

                //                 $event->sheet->getStyle($letter.$i.':'.$letter.$i)->applyFromArray([

                //                     'fill' => [
                //                         'fillType' => Fill::FILL_SOLID,
                //                         'color' => [
                //                             'argb' => 'FF3FA5F2'
                //                         ]
                //                     ]
                //                 ]);

                //             }
                //             if($key%3 == 0){

                //                 $event->sheet->getStyle($letter.$i.':'.$letter.$i)->applyFromArray([

                //                     'fill' => [
                //                         'fillType' => Fill::FILL_SOLID,
                //                         'color' => [
                //                             'argb' => 'FFB0FA7C'
                //                         ]
                //                     ]
                //                 ]);
                //             }
                //         }
                //     }
                // }
            },
        ];
    }

    public static function afterSheet(AfterSheet $event)
    {
        dd("sdkfgsdhf");
        $cellRange = 'A1:W1';
        $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(34);
    }

    public function __construct($domainDetailArray, $ipDetailArray)
    {
        $this->domainDetailArray = $domainDetailArray;
        $this->ipDetailArray = $ipDetailArray;
        return $this;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    // public function collection()
    // {
    //     // dd($this->usersCount);

    //     return ExportData::all();
    // }

    public function array(): array
    {
        // dd($this->ipDetailArray);

        $output = [];
        $domainHead = [];
        $domainHead = ['Domain', 'Country', 'Owner', 'Registrant Org', 'SPAM', 'Created on', 'ISP', 'IP'];
        array_push($output, $domainHead);
        array_push($output, $this->domainDetailArray);
        array_push($output, [' ']);
        $ipHead = ['IP', 'Country', 'Owner', 'Registrant Org', 'SPAM', 'Created on', 'ISP', 'Domain'];
        array_push($output, $ipHead);
        array_push($output, $this->ipDetailArray);

        // dd($output);
        return $output;
    }
}

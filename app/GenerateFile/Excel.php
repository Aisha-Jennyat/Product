<?php

namespace App\GenerateFile;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Excel
{
    public static function generateFileThisMonth(Collection $emp, String $dir, ?Carbon $today)
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'رقم التوظيف');
        $sheet->setCellValue('B1', 'الاسم الثلاثي');
        $sheet->setCellValue('C1', 'المجموعة');
        $sheet->setCellValue('D1', 'الوردية');
        $sheet->setCellValue('E1', 'الانتاج الكلي');

        $arr_data = [];
        for ($i = 1; $i <= 31; ++$i)
            $arr_data[$i] = 'اليوم ' . $i;

        $sheet->fromArray($arr_data, null, 'F1');

        // START

        $arrTemp = $emp->toArray();

        for($i = 0; $i < count($arrTemp); ++$i) {

            $arr_raw = array_fill(0, 34, '');

            $arr_raw[0] = $arrTemp[$i]['id_emp'];
            $arr_raw[1] = $arrTemp[$i]['name'];
            $arr_raw[2] = $arrTemp[$i]['group_name'];
            $arr_raw[3] = $arrTemp[$i]['period'];
            $arr_raw[4] = $arrTemp[$i]['all_production'];

            $arrProduction = explode(',', $arrTemp[$i]['production']);
            $arrUpdated    = explode(',', $arrTemp[$i]['updated']);

            for ($j = 0; $j < count($arrProduction); ++$j) {
                $arr_raw[$arrUpdated[$j] +4] = $arrProduction[$j];
            }

            $sheet->fromArray($arr_raw, null, 'A' . ($i + 2));
        }

        // END

        if($today != null)
            $nameFile = $today->toDateString() . 'date_production.xlsx';
        else
            $nameFile = 'this_month_production.xlsx';

        $writer = new Xlsx($spreadsheet);
        $writer->save($dir . $nameFile);

        return $nameFile;
    }

    public static function generateFileThisDay(Collection $emp, String $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'رقم التوظيف');
        $sheet->setCellValue('B1', 'عدد الأمتار');
        $sheet->setCellValue('C1', 'تاريخ الإنتاج');

        for ($i = 0; $i < $emp->count(); ++$i) {
            $sheet->fromArray($emp[$i]->toArray(), null, 'A' . ($i + 2));
        }

        $path = public_path() . "/files/excel/" . $filename;

        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        return $path;
    }
}
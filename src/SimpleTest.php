<?php

namespace Acme;

require "../vendor/autoload.php";

require_once 'IProcessor.php';

class SimpleTest implements IProcessor {

    function columnHeaderCheck($column_name, array $data) {
        $result = '';
        if (preg_match('/^#/', $column_name)):
            if (preg_match('/ /', $data['value'])):
                $result = ltrim($column_name, '#') . ' should not contain any space';
            endif;
        endif;
        if (preg_match('/\*$/', $column_name)):
            if (strlen($data['value']) == 0):
                $result = 'Missing value in ' . rtrim($column_name, '*');
            endif;
        endif;

        return $result;
    }

    function process($file, $fileType) {
        $finalResult = array();
        if($this->fileCheck($file)):

        $objReader = \PHPExcel_IOFactory::createReader($fileType);
        $objPHPExcel = $objReader->load($file);

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $arrayData[] = $worksheet->toArray();
        }

        $firstSheet = $arrayData[0];
        $rowContent = array();

        $rowHeader['header'] = $firstSheet[0];
        for ($i = 1; $i < count($firstSheet); $i++):
            $rowContent['rows'][] = $firstSheet[$i];
        endfor;

        $structuredArray = array();
        foreach ($rowHeader as $key => $val):
            $structuredArray[$key] = $rowContent;
        endforeach;

        $row = array();
        foreach ($rowContent['rows'] as $k => $rc):
            if (array_sum($rc)) {
                foreach ($rc as $t => $r):
                    $row[$k][] = $this->columnHeaderCheck($rowHeader['header'][$t], array('row' => $k, 'value' => $r));
                endforeach;
            }
        endforeach;

        $statement = '';
        foreach ($row as $n => $r):
            foreach ($r as $k => $a):
                $statement .= (strlen($a) > 0) ? $a . ', ' : '';
            endforeach;
            if (strlen($statement) > 0):
                $finalResult[$n + 1] = rtrim(trim($statement), ',');
            endif;
        endforeach;

        else:
            $finalResult = array('message'=>'We are sorry, validation can be executed only for excel format');
        endif;
        return $finalResult;
    }

    private function fileCheck($file) {
        $result = false;
        $except = array("xls", "xlsx");
        $imp = implode('|', $except);

        if (preg_match('/^.*\.(' . $imp . ')$/i', $file)):
            $result = true;
        endif;
        return $result;
    }
}
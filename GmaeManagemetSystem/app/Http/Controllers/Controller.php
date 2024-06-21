<?php
namespace App\Http\Controllers;
abstract class Controller
{
    public function parseCsvWithNewline($fileContent) {
        $csvData = $fileContent;
        $startIndex = 0;
        $newlineIndex = 0;
        $valueIndex = 0;
        $valueCount = 0;
        $values = [];
        while ($newlineIndex !== false) {
            $newlineIndex = strpos($csvData, "\n", $startIndex);
            if ($newlineIndex !== false) {
                $values[$valueIndex] = substr($csvData, $startIndex, $newlineIndex - $startIndex);
                $valueIndex++;
                $startIndex = $newlineIndex + 1;
                $valueCount++;
            }
        }
        $values[$valueIndex] = substr($csvData, $startIndex);
        $valueCount++;
        return $values;
    }  
}

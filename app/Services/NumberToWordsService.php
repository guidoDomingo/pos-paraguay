<?php

namespace App\Services;

class NumberToWordsService
{
    private static $units = [
        '', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'
    ];

    private static $teens = [
        'DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISÉIS', 
        'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'
    ];

    private static $tens = [
        '', '', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 
        'SETENTA', 'OCHENTA', 'NOVENTA'
    ];

    private static $hundreds = [
        '', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS',
        'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'
    ];

    /**
     * Convierte un número a palabras en español (Paraguay)
     */
    public static function convert($number): string
    {
        if ($number == 0) {
            return 'CERO';
        }

        $number = floor(abs($number)); // Solo números enteros positivos

        $result = '';

        // Billones
        if ($number >= 1000000000000) {
            $trillions = floor($number / 1000000000000);
            $result .= self::convertHundreds($trillions);
            $result .= ($trillions == 1) ? ' BILLÓN ' : ' BILLONES ';
            $number %= 1000000000000;
        }

        // Miles de millones
        if ($number >= 1000000000) {
            $billions = floor($number / 1000000000);
            $result .= self::convertHundreds($billions);
            $result .= ' MIL ';
            $number %= 1000000000;
        }

        // Millones
        if ($number >= 1000000) {
            $millions = floor($number / 1000000);
            if ($millions == 1) {
                $result .= 'UN MILLÓN ';
            } else {
                $result .= self::convertHundreds($millions) . ' MILLONES ';
            }
            $number %= 1000000;
        }

        // Miles
        if ($number >= 1000) {
            $thousands = floor($number / 1000);
            if ($thousands == 1) {
                $result .= 'MIL ';
            } else {
                $result .= self::convertHundreds($thousands) . ' MIL ';
            }
            $number %= 1000;
        }

        // Centenas, decenas y unidades
        if ($number > 0) {
            $result .= self::convertHundreds($number);
        }

        return trim($result);
    }

    /**
     * Convierte números del 0-999
     */
    private static function convertHundreds($number): string
    {
        $result = '';

        // Centenas
        if ($number >= 100) {
            $hundreds = floor($number / 100);
            if ($number == 100) {
                $result .= 'CIEN';
                return $result;
            } else {
                $result .= self::$hundreds[$hundreds] . ' ';
            }
            $number %= 100;
        }

        // Decenas y unidades
        if ($number >= 20) {
            $tens = floor($number / 10);
            $units = $number % 10;
            
            $result .= self::$tens[$tens];
            if ($units > 0) {
                $result .= ' Y ' . self::$units[$units];
            }
        } elseif ($number >= 10) {
            $result .= self::$teens[$number - 10];
        } elseif ($number > 0) {
            $result .= self::$units[$number];
        }

        return $result;
    }
}
<?php 

if (!function_exists('change_currency_format_to_decimal')) {
    function change_currency_format_to_decimal($currency)
    {
        // Contoh: "45.000,00" -> 45000
        $cleanedValue = str_replace('.', '', $currency);
        $cleanedValue = floatval(str_replace(',', '.', $cleanedValue));

        return $cleanedValue;
    }
}

if (!function_exists('change_decimal_format_to_currency')) {
    function change_decimal_format_to_currency($decimal)
    {
        // Contoh: 45000 -> "45.000,00"
        return number_format($decimal, 0, ',', '.');
    }
}

if (!function_exists('change_decimal_format_to_percentage')) {
    function change_decimal_format_to_percentage($decimal)
    {
        return ($decimal * 100) . '%';
    }
}

if (!function_exists('format_appointment_id')) {
    function format_appointment_id($id)
    {
        return sprintf("%09d", $id);
    }
}
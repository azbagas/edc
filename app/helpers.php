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
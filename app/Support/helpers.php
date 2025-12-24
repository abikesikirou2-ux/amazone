<?php

if (!function_exists('fcfa')) {
    /**
     * Formate un montant en FCFA.
     *
     * @param float|int|string $value Montant à formater
     * @param int $decimals Nombre de décimales (par défaut 0)
     * @return string
     */
    function fcfa($value, int $decimals = 0): string
    {
        return number_format((float)$value, $decimals, ',', ' ') . ' FCFA';
    }
}

if (!function_exists('fcfa_format')) {
    /**
     * Alias de fcfa(), pour cohérence sémantique.
     */
    function fcfa_format($value, int $decimals = 0): string
    {
        return fcfa($value, $decimals);
    }
}

<?php
//sensores.php
return [
    'humedad' => ['min' => 30, 'max' => 80],
    'peso' => ['min' => 2, 'max' => 50],
    'temperatura' => ['min' => 10, 'max' => 35],

    // para evitar spam
    'cooldown_minutes' => 2,
];

<?php
// D:\Dev\IPOO\tpo-ipoo\config.php

require '/Medoo.php';

use Medoo\Medoo;

// Variable global idéntica al modelo de la Biblioteca que te dio la cátedra
$database = new Medoo([
    'type' => 'mysql',                   // O 'mariadb' según el motor que instalaste nativo
    'host' => 'localhost',
    'database' => 'torneo_duelos', // Nombre exacto de tu Base de Datos del TP
    'username' => 'root',                  // Usuario por defecto
    'password' => '1234',    // Pon acá la contraseña que elegiste al instalar el motor
    'port' => 3306,
    'charset' => 'utf8mb4'
]);

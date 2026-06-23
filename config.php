<?php

require 'Medoo.php';

use Medoo\Medoo;

$database = new Medoo([
    'type' => 'mariadb',
    'host' => 'localhost',
    'database' => 'torneo_duelos',
    'username' => 'root',
    'password' => '',
    'port' => 3306,
    'charset' => 'utf8mb4'
]);

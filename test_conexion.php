<?php

require 'config.php';

try {
    global $database;

    // Intenta realizar una consulta de prueba directa al motor
    $version = $database->query("SELECT VERSION()")->fetchColumn();

    if ($version) {
        echo "\n======================================================\n";
        echo " ¡CONEXIÓN EXITOSA CON MEDOO NATIVO!\n";
        echo " Versión del motor conectado: " . $version . "\n";
        echo "======================================================\n\n";
    } else {
        echo "Error: Conexión establecida pero no se pudo leer la versión del motor.\n";
    }
} catch (Exception $e) {
    echo "\n------------------------------------------------------\n";
    echo " Error de configuración:\n";
    echo " " . $e->getMessage() . "\n";
    echo "------------------------------------------------------\n\n";
}

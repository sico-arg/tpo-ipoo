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
        
        // Listar tablas para verificar si la base de datos y sus tablas existen
        $tables = $database->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo " Tablas encontradas en la base de datos:\n";
        if (empty($tables)) {
            echo "   (Ninguna tabla encontrada en la base de datos. ¿Corriste el script SQL?)\n";
        } else {
            foreach ($tables as $table) {
                echo "   - " . $table . "\n";
            }
        }
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

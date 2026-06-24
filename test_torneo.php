<?php
// Script de prueba de fuerza bruta y errores para el sistema de Torneo y Duelos.
// Este script ejecuta pruebas completas sobre todas las clases, ORM (Medoo), BD y lógica de negocio.
// Registra detalladamente cada evento y genera el archivo 'test_run_log.txt' en la raíz.

require_once 'config.php';
require_once 'src/Torneo.php';

// Limpiar el archivo de logs anterior
file_put_contents('test_run_log.txt', '');

function logSection($title) {
    $border = str_repeat("=", 80);
    $text = "\n$border\n  $title\n$border\n";
    echo $text;
    file_put_contents('test_run_log.txt', $text, FILE_APPEND);
}

function logEvent($msg, $status = "INFO") {
    $line = sprintf("[%s] %s\n", $status, $msg);
    echo $line;
    file_put_contents('test_run_log.txt', $line, FILE_APPEND);
}

logSection("INICIANDO PRUEBAS DE FUERZA BRUTA Y ERRORES");

// 1. Limpieza de base de datos para pruebas limpias
logSection("1. LIMPIEZA DE BASE DE DATOS (SETUP)");
try {
    global $database;
    // Desactivar temporalmente restricciones de clave foránea para poder limpiar
    $database->query("SET FOREIGN_KEY_CHECKS = 0;");
    
    // Contar registros antes de limpiar
    $cantDuelos = $database->count("duelos");
    $cantPersonajes = $database->count("personajes");
    $cantArmas = $database->count("armas");
    $cantArenas = $database->count("arenas");
    
    logEvent("Registros actuales en la BD antes de limpiar: Duelos: $cantDuelos, Personajes: $cantPersonajes, Armas: $cantArmas, Arenas: $cantArenas");
    
    // Limpiar tablas
    $database->query("TRUNCATE TABLE duelos;");
    $database->query("TRUNCATE TABLE personajes;");
    $database->query("TRUNCATE TABLE armas;");
    $database->query("TRUNCATE TABLE arenas;");
    
    $database->query("SET FOREIGN_KEY_CHECKS = 1;");
    logEvent("Base de datos vaciada con éxito para iniciar pruebas limpias.", "OK");
} catch (Exception $e) {
    logEvent("Error durante la limpieza de la base de datos: " . $e->getMessage(), "ERROR");
    exit(1);
}

// 2. Pruebas CRUD de Armas
logSection("2. PRUEBAS CRUD DE ARMAS");

// 2.1 Guardar (Insertar)
try {
    $arma1 = new Arma("Espada de Madera", "espada", 10, 1, "disponible");
    $arma1->guardar();
    if ($arma1->getId() !== null) {
        logEvent("Insertado Arma 1: 'Espada de Madera' (ID: " . $arma1->getId() . ")", "OK");
    } else {
        logEvent("Fallo al obtener ID para Arma 1", "FALLO");
    }

    $arma2 = new Arma("Báculo del Caos", "baculo", 35, 5, "disponible");
    $arma2->guardar();
    logEvent("Insertado Arma 2: 'Báculo del Caos' (ID: " . $arma2->getId() . ")", "OK");

    $arma3 = new Arma("Arco Roto", "arco", 12, 2, "rota");
    $arma3->guardar();
    logEvent("Insertado Arma 3 (Rota): 'Arco Roto' (ID: " . $arma3->getId() . ")", "OK");

    $arma4 = new Arma("Espada del Sol", "espada", 50, 10, "disponible");
    $arma4->guardar();
    logEvent("Insertado Arma 4: 'Espada del Sol' (ID: " . $arma4->getId() . ")", "OK");

} catch (Exception $e) {
    logEvent("Error al insertar armas: " . $e->getMessage(), "ERROR");
}

// 2.2 Búsqueda por ID
try {
    $busquedaArma = Arma::busquedaPorId($arma1->getId());
    if ($busquedaArma && $busquedaArma->getNombre() === "Espada de Madera" && $busquedaArma->getDanioBase() === 10) {
        logEvent("Búsqueda por ID exitosa para Arma ID " . $arma1->getId() . " ('" . $busquedaArma->getNombre() . "')", "OK");
    } else {
        logEvent("Búsqueda por ID incorrecta o fallida para Arma ID " . $arma1->getId(), "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error al buscar arma por ID: " . $e->getMessage(), "ERROR");
}

// 2.3 Actualizar
try {
    $arma1->setNombre("Espada de Madera Reforzada");
    $arma1->setDanioBase(12);
    $arma1->guardar();
    
    $busquedaArmaAct = Arma::busquedaPorId($arma1->getId());
    if ($busquedaArmaAct && $busquedaArmaAct->getNombre() === "Espada de Madera Reforzada" && $busquedaArmaAct->getDanioBase() === 12) {
        logEvent("Actualización de Arma exitosa (Nuevo Nombre: '" . $busquedaArmaAct->getNombre() . "', Nuevo Daño: " . $busquedaArmaAct->getDanioBase() . ")", "OK");
    } else {
        logEvent("Fallo al actualizar Arma", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error al actualizar arma: " . $e->getMessage(), "ERROR");
}

// 2.4 Eliminar
try {
    $armaTemp = new Arma("Arma Temporal", "espada", 5, 1, "disponible");
    $armaTemp->guardar();
    $idTemp = $armaTemp->getId();
    logEvent("Arma temporal creada con ID: " . $idTemp);
    
    $armaTemp->eliminar();
    if ($armaTemp->getId() === null) {
        logEvent("El objeto Arma local reseteó su ID a null tras eliminar", "OK");
    } else {
        logEvent("El objeto Arma local conserva ID después de eliminar", "FALLO");
    }
    
    $busquedaTemp = Arma::busquedaPorId($idTemp);
    if ($busquedaTemp === null) {
        logEvent("Búsqueda del arma eliminada retornó null (Eliminada de BD correctamente)", "OK");
    } else {
        logEvent("El arma eliminada todavía existe en la BD", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error al eliminar arma: " . $e->getMessage(), "ERROR");
}

// 2.5 Listados de Armas
try {
    $todasLasArmas = Arma::listar();
    logEvent("Total de armas en BD: " . count($todasLasArmas) . " (Esperado: 4)");
    
    $armasDisponibles = Arma::listarDisponibles();
    logEvent("Total de armas disponibles en BD: " . count($armasDisponibles) . " (Esperado: 3 - no debe listar la 'rota')");
    
    $contieneRota = false;
    foreach ($armasDisponibles as $a) {
        if ($a->getEstado() === 'rota') $contieneRota = true;
    }
    if (!$contieneRota) {
        logEvent("Correcto: Las armas disponibles no incluyen armas rotas.", "OK");
    } else {
        logEvent("Error: El listado de disponibles contiene armas rotas.", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error al listar armas: " . $e->getMessage(), "ERROR");
}


// 3. Pruebas CRUD de Arenas
logSection("3. PRUEBAS CRUD DE ARENAS");

// 3.1 Guardar (Insertar)
try {
    $arena1 = new Arena("Coliseo del Trueno", 2, 1000, "tormenta");
    $arena1->guardar();
    logEvent("Insertado Arena 1: 'Coliseo del Trueno' (ID: " . $arena1->getid() . ")", "OK");

    $arena2 = new Arena("Valle de Niebla", 4, 500, "niebla");
    $arena2->guardar();
    logEvent("Insertado Arena 2: 'Valle de Niebla' (ID: " . $arena2->getid() . ")", "OK");

    $arena3 = new Arena("Playa Tranquila", 1, 2000, "normal");
    $arena3->guardar();
    logEvent("Insertado Arena 3: 'Playa Tranquila' (ID: " . $arena3->getid() . ")", "OK");
} catch (Exception $e) {
    logEvent("Error al insertar arenas: " . $e->getMessage(), "ERROR");
}

// 3.2 Búsqueda por ID
try {
    $busquedaArena = Arena::busquedaPorId($arena1->getid());
    if ($busquedaArena && $busquedaArena->getnombre() === "Coliseo del Trueno" && $busquedaArena->getclima() === "tormenta") {
        logEvent("Búsqueda por ID exitosa para Arena ID " . $arena1->getid() . " ('" . $busquedaArena->getnombre() . "')", "OK");
    } else {
        logEvent("Búsqueda por ID incorrecta o fallida para Arena ID " . $arena1->getid(), "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error al buscar arena por ID: " . $e->getMessage(), "ERROR");
}

// 3.3 Actualizar y Prueba del Clima (Bug de setclima)
try {
    $arena3->setcapaciadadPublico(2500);
    
    // Intentamos cambiar clima de normal a lluvia
    logEvent("Intentando cambiar clima de Playa Tranquila ('normal') a 'lluvia' mediante setclima()...");
    $arena3->setclima("lluvia");
    
    // Verificamos si cambió en memoria
    if ($arena3->getclima() === "lluvia") {
        logEvent("El clima cambió en memoria a 'lluvia'.", "OK");
    } else {
        logEvent("El clima NO cambió en memoria (Sigue siendo '" . $arena3->getclima() . "').", "BUG DETECTADO");
        logEvent("Explicación: Arena::setclima() posee una condición lógica incorrecta (operador || en lugar de &&) que causa que siempre retorne sin asignar.", "EXPLICACION");
    }
    
    // Guardamos la arena para verificar que persista otros cambios (como la capacidad)
    $arena3->guardar();
    $busquedaArenaAct = Arena::busquedaPorId($arena3->getid());
    if ($busquedaArenaAct && $busquedaArenaAct->getcapaciadadPublico() === 2500) {
        logEvent("Actualización de otros atributos de Arena exitosa (Capacidad: " . $busquedaArenaAct->getcapaciadadPublico() . ")", "OK");
    } else {
        logEvent("Fallo al actualizar capacidad de Arena", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error al actualizar arena: " . $e->getMessage(), "ERROR");
}

// 3.4 Eliminar
try {
    $arenaTemp = new Arena("Arena Temporal", 1, 100, "normal");
    $arenaTemp->guardar();
    $idTemp = $arenaTemp->getid();
    
    $arenaTemp->eliminar();
    $busquedaTemp = Arena::busquedaPorId($idTemp);
    if ($busquedaTemp === null) {
        logEvent("Arena temporal eliminada correctamente de la BD", "OK");
    } else {
        logEvent("La arena temporal todavía existe en la BD", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error al eliminar arena: " . $e->getMessage(), "ERROR");
}


// 4. Pruebas CRUD de Personajes
logSection("4. PRUEBAS CRUD DE PERSONAJES");

// 4.1 Guardar (Insertar)
try {
    $aragorn = new Guerrero("Aragorn", 3, 100, 90, 20, 15);
    $aragorn->guardar();
    logEvent("Insertado Guerrero: 'Aragorn' (ID: " . $aragorn->getId() . ")", "OK");

    $gandalf = new Mago("Gandalf", 6, 85, 100, 50, 25);
    $gandalf->guardar();
    logEvent("Insertado Mago: 'Gandalf' (ID: " . $gandalf->getId() . ")", "OK");

    $legolas = new Arquero("Legolas", 4, 90, 95, 30, 18);
    $legolas->guardar();
    logEvent("Insertado Arquero: 'Legolas' (ID: " . $legolas->getId() . ")", "OK");

    $novato = new Guerrero("Novato", 1, 100, 80, 5, 2);
    $novato->guardar();
    logEvent("Insertado Guerrero Novato: 'Novato' (ID: " . $novato->getId() . ")", "OK");
} catch (Exception $e) {
    logEvent("Error al insertar personajes: " . $e->getMessage(), "ERROR");
}

// 4.2 Búsqueda por ID e Instanciación Correcta
try {
    $busquedaGandalf = Personaje::busquedaPorId($gandalf->getId());
    if ($busquedaGandalf instanceof Mago) {
        logEvent("Gandalf recuperado es instancia de Mago (Instanciación dinámica correcta)", "OK");
        if ($busquedaGandalf->getMana() === 50 && $busquedaGandalf->getInteligencia() === 25) {
            logEvent("Atributos específicos de Mago cargados correctamente: Mana = 50, Inteligencia = 25", "OK");
        } else {
            logEvent("Atributos de Mago incorrectos al buscar por ID", "FALLO");
        }
    } else {
        logEvent("Gandalf recuperado no es una instancia de Mago", "FALLO");
    }

    $busquedaLegolas = Personaje::busquedaPorId($legolas->getId());
    if ($busquedaLegolas instanceof Arquero) {
        logEvent("Legolas recuperado es instancia de Arquero", "OK");
        if ($busquedaLegolas->getPrecision() === 30 && $busquedaLegolas->getVelocidad() === 18) {
            logEvent("Atributos específicos de Arquero cargados correctamente: Precisión = 30, Velocidad = 18", "OK");
        } else {
            logEvent("Atributos de Arquero incorrectos al buscar por ID", "FALLO");
        }
    } else {
        logEvent("Legolas recuperado no es una instancia de Arquero", "FALLO");
    }

    $busquedaAragorn = Personaje::busquedaPorId($aragorn->getId());
    if ($busquedaAragorn instanceof Guerrero) {
        logEvent("Aragorn recuperado es instancia de Guerrero", "OK");
        if ($busquedaAragorn->getFuerza() === 20 && $busquedaAragorn->getArmadura() === 15) {
            logEvent("Atributos específicos de Guerrero cargados correctamente: Fuerza = 20, Armadura = 15", "OK");
        } else {
            logEvent("Atributos de Guerrero incorrectos al buscar por ID", "FALLO");
        }
    } else {
        logEvent("Aragorn recuperado no es una instancia de Guerrero", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error al buscar personaje por ID: " . $e->getMessage(), "ERROR");
}

// 4.3 Actualizar
try {
    $legolas->setArma(null); // Asegurar sin arma inicialmente
    $legolas->guardar();
    
    logEvent("Modificando legolas (Nivel: 4 -> 5, Energía: 95 -> 90)");
    $legolas->aumentarNivel(); // pasa a 5
    $legolas->perderEnergia(5); // pasa de 95 a 90
    $legolas->guardar();
    
    $busquedaLegolasAct = Personaje::busquedaPorId($legolas->getId());
    if ($busquedaLegolasAct && $busquedaLegolasAct->getNivel() === 5 && $busquedaLegolasAct->getEnergia() === 90) {
        logEvent("Actualización de Personaje persistida con éxito en la BD (Nivel: 5, Energía: 90)", "OK");
    } else {
        logEvent("Fallo al persistir actualizaciones de Personaje", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error al actualizar personaje: " . $e->getMessage(), "ERROR");
}

// 4.4 Eliminar
try {
    $pTemp = new Guerrero("Temporal", 1, 100, 100, 5, 5);
    $pTemp->guardar();
    $idTemp = $pTemp->getId();
    
    $pTemp->eliminar();
    $busquedaTemp = Personaje::busquedaPorId($idTemp);
    if ($busquedaTemp === null) {
        logEvent("Personaje temporal eliminado correctamente de la BD", "OK");
    } else {
        logEvent("El personaje temporal todavía existe en la BD", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error al eliminar personaje: " . $e->getMessage(), "ERROR");
}


// 5. Pruebas de Reglas de Negocio / Equipamiento de Armas
logSection("5. PRUEBAS DE REGLAS DE NEGOCIO: EQUIPAMIENTO");

$torneo = new Torneo();

// 5.1 Equipamiento Válido
try {
    logEvent("Intentando equipar 'Espada de Madera Reforzada' (Nivel Min: 1) a 'Aragorn' (Nivel: 3)...");
    $puedeEquipar = $arma1->puedeSerEquipadoPor($aragorn);
    logEvent("¿Arma::puedeSerEquipadoPor() retorna true? " . ($puedeEquipar ? "SI" : "NO"));
    
    $exitoEquipar = $torneo->equiparArma($aragorn, $arma1);
    if ($exitoEquipar && $aragorn->getArma() === $arma1 && $arma1->getEstado() === 'equipada') {
        logEvent("Espada equipada correctamente en memoria.", "OK");
        
        // Persistir en BD
        $aragorn->guardar();
        $arma1->guardar();
        
        // Verificar persistencia de relación
        $aragornBD = Personaje::busquedaPorId($aragorn->getId());
        if ($aragornBD && $aragornBD->getArma() !== null && $aragornBD->getArma()->getId() === $arma1->getId()) {
            logEvent("Relación Personaje-Arma cargada y persistida correctamente en BD (idArmaEquipada: " . $arma1->getId() . ")", "OK");
        } else {
            logEvent("Fallo al persistir o recuperar relación Personaje-Arma en BD", "FALLO");
        }
    } else {
        logEvent("Fallo al equipar arma válida", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error en equipamiento válido: " . $e->getMessage(), "ERROR");
}

// 5.2 Equipamiento Inválido: Nivel Insuficiente
try {
    logEvent("Intentando equipar 'Espada del Sol' (Nivel Min: 10) a 'Novato' (Nivel: 1)...");
    $puedeEquipar = $arma4->puedeSerEquipadoPor($novato);
    logEvent("¿puedeSerEquipadoPor() retorna true? " . ($puedeEquipar ? "SI" : "NO"));
    
    $exitoEquipar = $torneo->equiparArma($novato, $arma4);
    if (!$exitoEquipar && $novato->getArma() === null) {
        logEvent("Equipamiento rechazado con éxito debido a nivel insuficiente.", "OK");
    } else {
        logEvent("Error: Se equipó un arma a pesar de que el personaje no cumplía con el nivel mínimo requerido.", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error en equipamiento por nivel insuficiente: " . $e->getMessage(), "ERROR");
}

// 5.3 Equipamiento Inválido: Arma Rota
try {
    logEvent("Intentando equipar 'Arco Roto' (Estado: 'rota') a 'Legolas' (Nivel: 5, Nivel Min: 2)...");
    $puedeEquipar = $arma3->puedeSerEquipadoPor($legolas);
    logEvent("¿puedeSerEquipadoPor() retorna true? " . ($puedeEquipar ? "SI" : "NO"));
    
    $exitoEquipar = $torneo->equiparArma($legolas, $arma3);
    if (!$exitoEquipar && $legolas->getArma() === null) {
        logEvent("Equipamiento rechazado con éxito debido a arma rota.", "OK");
    } else {
        logEvent("Error: Se equipó un arma con estado 'rota'.", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error en equipamiento de arma rota: " . $e->getMessage(), "ERROR");
}

// 5.4 Equipamiento Inválido: Arma Ya Equipada
try {
    logEvent("Intentando equipar 'Espada de Madera Reforzada' (Ya equipada por Aragorn) a 'Novato'...");
    $puedeEquipar = $arma1->puedeSerEquipadoPor($novato);
    logEvent("¿puedeSerEquipadoPor() retorna true? " . ($puedeEquipar ? "SI" : "NO"));
    
    $exitoEquipar = $torneo->equiparArma($novato, $arma1);
    if (!$exitoEquipar) {
        logEvent("Equipamiento rechazado con éxito porque el arma ya estaba equipada.", "OK");
    } else {
        logEvent("Error: Se permitió equipar un arma que ya estaba en estado 'equipada'.", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error en equipamiento de arma ya equipada: " . $e->getMessage(), "ERROR");
}


// 6. Pruebas de Cálculo de Poder y Modificadores de Clima
logSection("6. PRUEBAS DE CÁLCULO DE PODER Y MODIFICADORES DE ARENA");

// 6.1 Mago Gandalf (Nivel 6, Mana 50, Inteligencia 25, sin arma)
// En Playa Tranquila (Normal, modificador clima = 0)
// Poder Base: 6 * 10 + 50 = 110
// Poder Especial: 25 + 50 * 3 = 175
// Poder Total Esperado: 285
try {
    $poderNormal = $gandalf->calcularPoderTotal($arena3);
    logEvent("Poder de Gandalf en Playa Tranquila (Clima Normal): $poderNormal (Esperado: 285)");
    if ($poderNormal === 285) {
        logEvent("Cálculo de Poder Mago (Clima Normal) correcto.", "OK");
    } else {
        logEvent("Cálculo de Poder Mago incorrecto.", "FALLO");
    }
    
    // En Coliseo del Trueno (Tormenta, mod clima = +15 para Mago)
    // Poder Total Esperado: 285 + 15 = 300
    $poderTormenta = $gandalf->calcularPoderTotal($arena1);
    logEvent("Poder de Gandalf en Coliseo del Trueno (Clima Tormenta): $poderTormenta (Esperado: 300)");
    if ($poderTormenta === 300) {
        logEvent("Cálculo de Poder Mago con bonificador de Tormenta correcto (+15).", "OK");
    } else {
        logEvent("Cálculo de Poder Mago con Tormenta incorrecto.", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error al calcular poder de Mago: " . $e->getMessage(), "ERROR");
}

// 6.2 Guerrero Aragorn con Espada de Madera Reforzada (Daño base: 12)
// En Playa Tranquila (Normal, modificador clima = 0)
// Poder Base: 3 * 15 = 45
// Poder Especial: 20 * 2 + 15 = 55
// Poder Esperado Sin Arma: 100
// Poder Esperado Con Arma: 112 (Si el daño del arma sumara)
try {
    $poderAragorn = $aragorn->calcularPoderTotal($arena3);
    logEvent("Poder de Aragorn en Playa Tranquila (Clima Normal, Espada equipada): $poderAragorn");
    if ($poderAragorn === 100) {
        logEvent("El poder calculado es 100 (Daño de arma: 0).", "OK");
        logEvent("BUG CONFIRMADO: Arma::calcularDanio() retorna 0 cuando está equipada. Esto se debe a la condición 'estado == disponible && estado == equipada' que es físicamente imposible.", "BUG DETECTADO");
    } elseif ($poderAragorn === 112) {
        logEvent("El poder calculado es 112. Daño del arma sumado correctamente.", "OK");
    } else {
        logEvent("Poder de Guerrero inesperado: $poderAragorn", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error al calcular poder de Guerrero: " . $e->getMessage(), "ERROR");
}

// 6.3 Arquero Legolas (Nivel 5, Precisión 30, Velocidad 18, sin arma)
// En Valle de Niebla (Niebla, mod clima = -15 para Arquero)
// Poder Base: 5 * 12 + 30 = 90
// Poder Especial: 30 * 2 + 18 = 78
// Poder Normal Esperado: 168
// Poder Esperado Niebla: 168 - 15 = 153
try {
    $poderLegolasNormal = $legolas->calcularPoderTotal($arena3);
    logEvent("Poder de Legolas en Playa Tranquila (Clima Normal): $poderLegolasNormal (Esperado: 168)");
    
    $poderLegolasNiebla = $legolas->calcularPoderTotal($arena2);
    logEvent("Poder de Legolas en Valle de Niebla (Clima Niebla): $poderLegolasNiebla (Esperado: 153)");
    if ($poderLegolasNiebla === 153) {
        logEvent("Cálculo de Poder Arquero con penalizador de Niebla correcto (-15).", "OK");
    } else {
        logEvent("Cálculo de Poder Arquero con Niebla incorrecto.", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error al calcular poder de Arquero: " . $e->getMessage(), "ERROR");
}


// 7. Pruebas de Duelos y Modificación de Estados
logSection("7. PRUEBAS DE DUELOS Y CONSECUENCIAS");

// 7.1 Registrar Duelo Válido
try {
    $fecha = date("Y-m-d");
    $dueloValido = new Duelo($aragorn, $gandalf, $arena3, $fecha, "pendiente");
    logEvent("¿Duelo Aragorn vs Gandalf puede realizarse? " . ($dueloValido->puedeRealizarse() ? "SI" : "NO"));
    
    $dueloValido->guardar();
    logEvent("Duelo registrado y guardado con ID: " . $dueloValido->getId(), "OK");
} catch (Exception $e) {
    logEvent("Error al registrar duelo válido: " . $e->getMessage(), "ERROR");
}

// 7.2 Intentar registrar duelo contra uno mismo (Autoduelo)
try {
    logEvent("Intentando registrar duelo contra uno mismo (Aragorn vs Aragorn)...");
    $autoduelo = new Duelo($aragorn, $aragorn, $arena3, $fecha, "pendiente");
    logEvent("¿Autoduelo puedeRealizarse() en PHP? " . ($autoduelo->puedeRealizarse() ? "SI" : "NO"));
    
    // Intentar guardarlo para ver si la base de datos rechaza por constraint
    $autoduelo->guardar();
    logEvent("Error: La BD permitió guardar un duelo contra uno mismo.", "FALLO");
} catch (Exception $e) {
    logEvent("BD rechazó con éxito el autoduelo. Mensaje: " . $e->getMessage(), "OK");
}

// 7.3 Intentar registrar duelo con personaje lesionado o retirado
try {
    logEvent("Aplicando daño a Guerrero 'Novato' para lesionarlo...");
    logEvent("Vida inicial de Novato: " . $novato->getPuntosVida() . " | Estado: " . $novato->getEstado());
    
    // Le aplicamos daño de 80. Vida pasa de 100 a 20. Debería ser 'lesionado'
    $novato->recibirDanio(80);
    logEvent("Vida de Novato tras recibir 80 de daño: " . $novato->getPuntosVida() . " | Estado: " . $novato->getEstado());
    if ($novato->getEstado() === 'lesionado') {
        logEvent("Novato cambió su estado a 'lesionado' correctamente.", "OK");
    } else {
        logEvent("Novato no cambió su estado a 'lesionado'. Estado actual: " . $novato->getEstado(), "FALLO");
    }
    $novato->guardar();
    
    // Intentamos duelo con lesionado
    $dueloLesionado = new Duelo($aragorn, $novato, $arena3, $fecha, "pendiente");
    logEvent("¿Duelo con personaje lesionado puede realizarse? " . ($dueloLesionado->puedeRealizarse() ? "SI" : "NO"));
    if (!$dueloLesionado->puedeRealizarse()) {
        logEvent("Duelo con lesionado rechazado correctamente.", "OK");
    } else {
        logEvent("Fallo: Se permitió realizar duelo con un personaje lesionado.", "FALLO");
    }
    
    // Le aplicamos daño adicional de 25 para retirarlo. Vida pasa de 20 a 0. Debería ser 'retirado'
    logEvent("Aplicando más daño a Novato para retirarlo...");
    $novato->recibirDanio(25);
    logEvent("Vida de Novato tras daño adicional: " . $novato->getPuntosVida() . " | Estado: " . $novato->getEstado());
    if ($novato->getEstado() === 'retirado') {
        logEvent("Novato cambió su estado a 'retirado' correctamente.", "OK");
    } else {
        logEvent("Novato no cambió su estado a 'retirado'.", "FALLO");
    }
    $novato->guardar();
    
    // Intentamos duelo con retirado
    $dueloRetirado = new Duelo($aragorn, $novato, $arena3, $fecha, "pendiente");
    logEvent("¿Duelo con personaje retirado puede realizarse? " . ($dueloRetirado->puedeRealizarse() ? "SI" : "NO"));
    if (!$dueloRetirado->puedeRealizarse()) {
        logEvent("Duelo con retirado rechazado correctamente.", "OK");
    } else {
        logEvent("Fallo: Se permitió realizar duelo con un personaje retirado.", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error al probar duelos con lesionado/retirado: " . $e->getMessage(), "ERROR");
}

// 7.4 Ejecución de Duelo y Consecuencias
// Duelo: Aragorn vs Gandalf en Playa Tranquila (Normal)
// Poderes calculados: Aragorn (100) vs Gandalf (285). Gana Gandalf.
// Daño: 285 - 100 = 185.
// Gandalf (Ganador): Nivel sube a 7, Energía recupera 5 (se queda en 100), Duelos Ganados sube a 1
// Aragorn (Perdedor): Vida baja de 100 a 0 (100 - 185 = -85 -> 0), Estado pasa a 'retirado', Energía pierde 5 (de 90 a 85), Duelos Perdidos sube a 1
try {
    logSection("EJECUTANDO DUELO ID: " . $dueloValido->getId());
    logEvent("Datos antes del duelo:");
    logEvent("  Aragorn - Nivel: " . $aragorn->getNivel() . ", Vida: " . $aragorn->getPuntosVida() . ", Energía: " . $aragorn->getEnergia() . ", Ganados: " . $aragorn->getDuelosGanados() . ", Perdidos: " . $aragorn->getDuelosPerdidos() . ", Estado: " . $aragorn->getEstado());
    logEvent("  Gandalf - Nivel: " . $gandalf->getNivel() . ", Vida: " . $gandalf->getPuntosVida() . ", Energía: " . $gandalf->getEnergia() . ", Ganados: " . $gandalf->getDuelosGanados() . ", Perdidos: " . $gandalf->getDuelosPerdidos() . ", Estado: " . $gandalf->getEstado());
    
    $exitoDuelo = $dueloValido->realizarDuelo();
    if ($exitoDuelo) {
        logEvent("¡Duelo realizado con éxito!", "OK");
        
        $ganador = $dueloValido->getGanador();
        logEvent("Ganador determinado: " . ($ganador ? $ganador->getNombre() : "Empate"));
        logEvent("Poder Personaje 1 (Aragorn): " . $dueloValido->getPoderPersonaje1());
        logEvent("Poder Personaje 2 (Gandalf): " . $dueloValido->getPoderPersonaje2());
        logEvent("Daño Aplicado: " . $dueloValido->getDanioAplicado());
        
        // Persistir todos los cambios en la BD
        $dueloValido->guardar();
        $aragorn->guardar();
        $gandalf->guardar();
        
        logEvent("Datos en memoria tras duelo:");
        logEvent("  Aragorn - Nivel: " . $aragorn->getNivel() . ", Vida: " . $aragorn->getPuntosVida() . ", Energía: " . $aragorn->getEnergia() . ", Ganados: " . $aragorn->getDuelosGanados() . ", Perdidos: " . $aragorn->getDuelosPerdidos() . ", Estado: " . $aragorn->getEstado());
        logEvent("  Gandalf - Nivel: " . $gandalf->getNivel() . ", Vida: " . $gandalf->getPuntosVida() . ", Energía: " . $gandalf->getEnergia() . ", Ganados: " . $gandalf->getDuelosGanados() . ", Perdidos: " . $gandalf->getDuelosPerdidos() . ", Estado: " . $gandalf->getEstado());
        
        // Cargar desde BD para asegurar que los cambios persistan en el motor
        logEvent("Cargando personajes y duelo actualizados desde la BD...");
        $aragornBD = Personaje::busquedaPorId($aragorn->getId());
        $gandalfBD = Personaje::busquedaPorId($gandalf->getId());
        $dueloBD = Duelo::busquedaPorId($dueloValido->getId());
        
        if ($aragornBD->getPuntosVida() === 0 && $aragornBD->getEstado() === 'retirado' && $aragornBD->getDuelosPerdidos() === 1) {
            logEvent("Aragorn persistido correctamente como RETIRADO con 0 de vida y 1 derrota.", "OK");
        } else {
            logEvent("Fallo al persistir estado de Aragorn tras el duelo", "FALLO");
        }
        
        if ($gandalfBD->getNivel() === 7 && $gandalfBD->getDuelosGanados() === 1) {
            logEvent("Gandalf persistido correctamente con Nivel 7 y 1 victoria.", "OK");
        } else {
            logEvent("Fallo al persistir estado de Gandalf tras el duelo", "FALLO");
        }
        
        if ($dueloBD && $dueloBD->getEstado() === 'realizado' && $dueloBD->getGanador()->getId() === $gandalf->getId()) {
            logEvent("Duelo persistido correctamente como REALIZADO con Gandalf de Ganador.", "OK");
        } else {
            logEvent("Fallo al persistir estado del duelo", "FALLO");
        }
    } else {
        logEvent("Error: No se pudo ejecutar el duelo a pesar de ser válido.", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error al ejecutar duelo y verificar consecuencias: " . $e->getMessage(), "ERROR");
}

// 7.5 Registrar otros duelos para poblar BD y verificar queries complejas
try {
    logEvent("Creando personajes y arenas adicionales para generar datos de prueba complejos...");
    
    // Crear un curandero/guerrero adicional
    $gimli = new Guerrero("Gimli", 5, 100, 100, 22, 18);
    $gimli->guardar();
    
    // Equipar báculo a Gandalf (Gandalf nivel 7 cumple min de Báculo que es 5)
    $torneo->equiparArma($gandalf, $arma2);
    $gandalf->guardar();
    $arma2->guardar();
    
    // Registrar duelo: Legolas vs Gimli en Valle de Niebla
    $duelo2 = new Duelo($legolas, $gimli, $arena2, $fecha, "pendiente");
    $duelo2->guardar();
    $duelo2->realizarDuelo();
    $duelo2->guardar();
    $duelo2->getPersonaje1()->guardar();
    $duelo2->getPersonaje2()->guardar();
    
    logEvent("Duelo 2 ejecutado: Legolas vs Gimli en Valle de Niebla. Ganador: " . ($duelo2->getGanador() ? $duelo2->getGanador()->getNombre() : "Empate"));

    // Registrar duelo: Gandalf vs Gimli en Coliseo del Trueno
    $duelo3 = new Duelo($gandalf, $gimli, $arena1, $fecha, "pendiente");
    $duelo3->guardar();
    $duelo3->realizarDuelo();
    $duelo3->guardar();
    $duelo3->getPersonaje1()->guardar();
    $duelo3->getPersonaje2()->guardar();
    
    logEvent("Duelo 3 ejecutado: Gandalf vs Gimli en Coliseo del Trueno. Ganador: " . ($duelo3->getGanador() ? $duelo3->getGanador()->getNombre() : "Empate"));
    
} catch (Exception $e) {
    logEvent("Error al poblar BD con más duelos: " . $e->getMessage(), "ERROR");
}


// 8. Test de las 13 Consultas Obligatorias
logSection("8. VERIFICACIÓN DE LAS 13 CONSULTAS OBLIGATORIAS");

function imprimirPersonajeTest($p) {
    $armaStr = $p->getArma() ? $p->getArma()->getNombre() : "Ninguna";
    return sprintf("[ID: %s] Nombre: %s | Clase: %s | Nivel: %s | Vida: %s | Energia: %s | Ganados: %s | Perdidos: %s | Estado: %s | Arma: %s",
        $p->getId(), $p->getNombre(), get_class($p), $p->getNivel(), $p->getPuntosVida(), $p->getEnergia(), $p->getDuelosGanados(), $p->getDuelosPerdidos(), $p->getEstado(), $armaStr
    );
}

// C1. Listar todos los personajes
try {
    logEvent("C1. Listar todos los personajes:");
    $c1 = Personaje::listar();
    foreach ($c1 as $p) {
        logEvent("  " . imprimirPersonajeTest($p));
    }
    logEvent("Total listados: " . count($c1), "OK");
} catch (Exception $e) {
    logEvent("Error en C1: " . $e->getMessage(), "ERROR");
}

// C2. Listar personajes disponibles para duelar
try {
    logEvent("C2. Listar personajes disponibles para duelar:");
    $c2 = Personaje::listarDisponibles();
    foreach ($c2 as $p) {
        logEvent("  " . imprimirPersonajeTest($p));
    }
    logEvent("Total disponibles: " . count($c2), "OK");
} catch (Exception $e) {
    logEvent("Error en C2: " . $e->getMessage(), "ERROR");
}

// C3. Listar personajes lesionados
try {
    logEvent("C3. Listar personajes lesionados:");
    $c3 = Personaje::listarLesionados();
    foreach ($c3 as $p) {
        logEvent("  " . imprimirPersonajeTest($p));
    }
    logEvent("Total lesionados: " . count($c3), "OK");
} catch (Exception $e) {
    logEvent("Error en C3: " . $e->getMessage(), "ERROR");
}

// C4. Listar personajes retirados
try {
    logEvent("C4. Listar personajes retirados:");
    $c4 = Personaje::listarRetirados();
    foreach ($c4 as $p) {
        logEvent("  " . imprimirPersonajeTest($p));
    }
    logEvent("Total retirados: " . count($c4), "OK");
} catch (Exception $e) {
    logEvent("Error en C4: " . $e->getMessage(), "ERROR");
}

// C5. Listar armas disponibles
try {
    logEvent("C5. Listar armas disponibles:");
    $c5 = Arma::listarDisponibles();
    foreach ($c5 as $a) {
        logEvent(sprintf("  [ID: %s] Nombre: %s | Daño: %s | Nivel Min: %s | Estado: %s", $a->getId(), $a->getNombre(), $a->getDanioBase(), $a->getNivelMinimo(), $a->getEstado()));
    }
    logEvent("Total armas disponibles: " . count($c5), "OK");
} catch (Exception $e) {
    logEvent("Error en C5: " . $e->getMessage(), "ERROR");
}

// C6. Mostrar el arma equipada por cada personaje
try {
    logEvent("C6. Mostrar el arma equipada por cada personaje:");
    $c6 = Personaje::listar();
    foreach ($c6 as $p) {
        $armaStr = $p->getArma() ? $p->getArma()->getNombre() : "Ninguna";
        logEvent("  Personaje: " . $p->getNombre() . " | Arma: " . $armaStr);
    }
    logEvent("Consulta completada", "OK");
} catch (Exception $e) {
    logEvent("Error en C6: " . $e->getMessage(), "ERROR");
}

// C7. Mostrar todos los duelos realizados
try {
    logEvent("C7. Mostrar todos los duelos realizados:");
    $c7 = Duelo::listarRealizados();
    foreach ($c7 as $d) {
        logEvent(sprintf("  [ID: %s] %s vs %s en %s (Ganador: %s, Daño: %s)", 
            $d->getId(), $d->getPersonaje1()->getNombre(), $d->getPersonaje2()->getNombre(), $d->getArena()->getnombre(), ($d->getGanador() ? $d->getGanador()->getNombre() : "Empate"), $d->getDanioAplicado()
        ));
    }
    logEvent("Total realizados: " . count($c7), "OK");
} catch (Exception $e) {
    logEvent("Error en C7: " . $e->getMessage(), "ERROR");
}

// C8. Mostrar todos los duelos pendientes
try {
    logEvent("C8. Mostrar todos los duelos pendientes:");
    // Crear un duelo pendiente temporal para ver si se lista
    $dueloPend = new Duelo($gandalf, $legolas, $arena3, $fecha, "pendiente");
    $dueloPend->guardar();
    
    $c8 = Duelo::listarPendientes();
    foreach ($c8 as $d) {
        logEvent(sprintf("  [ID: %s] %s vs %s en %s (Estado: %s)", 
            $d->getId(), $d->getPersonaje1()->getNombre(), $d->getPersonaje2()->getNombre(), $d->getArena()->getnombre(), $d->getEstado()
        ));
    }
    logEvent("Total pendientes: " . count($c8), "OK");
    
    // Limpiar el duelo pendiente temporal
    $dueloPend->eliminar();
} catch (Exception $e) {
    logEvent("Error en C8: " . $e->getMessage(), "ERROR");
}

// C9. Mostrar el historial de duelos de un personaje
try {
    logEvent("C9. Mostrar el historial de duelos de Gimli (ID: " . $gimli->getId() . "):");
    $c9 = Duelo::historialPorPersonaje($gimli->getId());
    foreach ($c9 as $d) {
        logEvent(sprintf("  [ID: %s] %s vs %s | Arena: %s | Ganador: %s | Estado: %s", 
            $d->getId(), $d->getPersonaje1()->getNombre(), $d->getPersonaje2()->getNombre(), $d->getArena()->getnombre(), ($d->getGanador() ? $d->getGanador()->getNombre() : "Empate/Pendiente"), $d->getEstado()
        ));
    }
    logEvent("Total duelos en historial de Gimli: " . count($c9), "OK");
} catch (Exception $e) {
    logEvent("Error en C9: " . $e->getMessage(), "ERROR");
}

// C10. Mostrar el ranking de personajes ordenado por cantidad de victorias
try {
    logEvent("C10. Mostrar el ranking de personajes ordenado por cantidad de victorias:");
    $c10 = Personaje::obtenerRanking();
    foreach ($c10 as $idx => $p) {
        logEvent(sprintf("  [%d] %s | Victorias: %s | Derrotas: %s", $idx + 1, $p->getNombre(), $p->getDuelosGanados(), $p->getDuelosPerdidos()));
    }
    logEvent("Ranking completado", "OK");
} catch (Exception $e) {
    logEvent("Error en C10: " . $e->getMessage(), "ERROR");
}

// C11. Mostrar el personaje con mayor cantidad de victorias
try {
    logEvent("C11. Mostrar el personaje con mayor cantidad de victorias:");
    $c11 = Personaje::obtenerPersonajeMasVictorias();
    if ($c11) {
        logEvent("  El personaje con más victorias es: " . $c11->getNombre() . " con " . $c11->getDuelosGanados() . " victorias.", "OK");
    } else {
        logEvent("  No hay personajes registrados.", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error en C11: " . $e->getMessage(), "ERROR");
}

// C12. Mostrar el porcentaje de victorias de cada personaje
try {
    logEvent("C12. Mostrar el porcentaje de victorias de cada personaje:");
    $c12 = Personaje::obtenerPorcentajeVictorias();
    foreach ($c12 as $nombre => $porcentaje) {
        logEvent("  Personaje: $nombre | Winrate: $porcentaje%");
    }
    logEvent("Winrates calculados con éxito", "OK");
} catch (Exception $e) {
    logEvent("Error en C12: " . $e->getMessage(), "ERROR");
}

// C13. Mostrar la arena donde más duelos se realizaron
try {
    logEvent("C13. Mostrar la arena donde más duelos se realizaron:");
    $c13 = Arena::obtenerArenaMasDuelos();
    if ($c13) {
        logEvent("  La arena con más duelos es: '" . $c13->getnombre() . "' (ID: " . $c13->getid() . ", Clima: " . $c13->getclima() . ")", "OK");
    } else {
        logEvent("  No hay duelos registrados en ninguna arena.", "FALLO");
    }
} catch (Exception $e) {
    logEvent("Error en C13: " . $e->getMessage(), "ERROR");
}

logSection("PRUEBAS DE FUERZA BRUTA Y ERRORES FINALIZADAS");
logEvent("Todas las pruebas han sido ejecutadas. Se ha generado el archivo 'test_run_log.txt' en la raíz con todo el detalle.");

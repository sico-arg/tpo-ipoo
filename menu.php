<?php
require_once 'config.php';
require_once 'src/Personaje/Personaje.php';
require_once 'src/Personaje/Guerrero.php';
require_once 'src/Personaje/Mago.php';
require_once 'src/Personaje/Arquero.php';
require_once 'src/Arma.php';
require_once 'src/Arena.php';
require_once 'src/Duelo.php';
require_once 'src/Torneo.php';

function leerEntrada($mensaje)
{
    echo $mensaje;
    return trim(fgets(STDIN));
}

function imprimirPersonaje(Personaje $p)
{
    $armaStr = $p->getArma() ? $p->getArma()->getNombre() : "Ninguna";
    echo "[ID: {$p->getId()}] Nombre: {$p->getNombre()} | Clase: " . get_class($p) . " | Nivel: {$p->getNivel()} | Vida: {$p->getPuntosVida()} | Energia: {$p->getEnergia()} | Victorias: {$p->getDuelosGanados()} | Estado: {$p->getEstado()} | Arma: {$armaStr}\n";
}

function imprimirArma(Arma $a)
{
    echo "[ID: {$a->getId()}] Nombre: {$a->getNombre()} | Tipo: {$a->getTipo()} | Daño: {$a->getDanioBase()} | Nivel Min: {$a->getNivelMinimo()} | Estado: {$a->getEstado()}\n";
}

function imprimirArena(Arena $a)
{
    echo "[ID: {$a->getid()}] Nombre: {$a->getnombre()} | Dificultad: {$a->getdificultad()} | Capacidad: {$a->getcapaciadadPublico()} | Clima: {$a->getclima()}\n";
}

function imprimirDuelo(Duelo $d)
{
    $ganador = $d->getGanador() ? $d->getGanador()->getNombre() : "N/A";
    echo "[ID: {$d->getId()}] {$d->getPersonaje1()->getNombre()} vs {$d->getPersonaje2()->getNombre()} | Arena: {$d->getArena()->getnombre()} | Fecha: {$d->getFecha()} | Estado: {$d->getEstado()} | Ganador: {$ganador}\n";
}

function subMenuPersonajes()
{
    while (true) {
        echo "\n--- ADMINISTRAR PERSONAJES ---\n";
        echo "1. Listar todos\n";
        echo "2. Registrar Guerrero\n";
        echo "3. Registrar Mago\n";
        echo "4. Registrar Arquero\n";
        echo "5. Recuperar personaje lesionado\n";
        echo "0. Volver\n";

        $op = leerEntrada("Opción: ");
        if ($op == '0') break;

        switch ($op) {
            case '1':
                $personajes = Personaje::listar();
                foreach ($personajes as $p) imprimirPersonaje($p);
                break;
            case '2':
                $nombre = leerEntrada("Nombre: ");
                $nivel = (int)leerEntrada("Nivel: ");
                $pv = (int)leerEntrada("Puntos de Vida: ");
                $energia = (int)leerEntrada("Energía: ");
                $fuerza = (int)leerEntrada("Fuerza: ");
                $armadura = (int)leerEntrada("Armadura: ");
                $g = new Guerrero($nombre, $nivel, $pv, $energia, $fuerza, $armadura);
                $g->guardar();
                echo "Guerrero guardado con ID {$g->getId()}\n";
                break;
            case '3':
                $nombre = leerEntrada("Nombre: ");
                $nivel = (int)leerEntrada("Nivel: ");
                $pv = (int)leerEntrada("Puntos de Vida: ");
                $energia = (int)leerEntrada("Energía: ");
                $mana = (int)leerEntrada("Mana: ");
                $inteligencia = (int)leerEntrada("Inteligencia: ");
                $m = new Mago($nombre, $nivel, $pv, $energia, $mana, $inteligencia);
                $m->guardar();
                echo "Mago guardado con ID {$m->getId()}\n";
                break;
            case '4':
                $nombre = leerEntrada("Nombre: ");
                $nivel = (int)leerEntrada("Nivel: ");
                $pv = (int)leerEntrada("Puntos de Vida: ");
                $energia = (int)leerEntrada("Energía: ");
                $precision = (int)leerEntrada("Precisión: ");
                $velocidad = (int)leerEntrada("Velocidad: ");
                $a = new Arquero($nombre, $nivel, $pv, $energia, $precision, $velocidad);
                $a->guardar();
                echo "Arquero guardado con ID {$a->getId()}\n";
                break;
            case '5':
                $id = (int)leerEntrada("ID del personaje lesionado: ");
                $p = Personaje::busquedaPorId($id);
                if ($p) {
                    if ($p->getEstado() == "lesionado") {
                        $p->recuperarVida(100);
                        $p->guardar();
                        echo "Personaje recuperado exitosamente.\n";
                    } else {
                        echo "El personaje no está lesionado.\n";
                    }
                } else {
                    echo "Personaje no encontrado.\n";
                }
                break;
        }
    }
}

function subMenuArmas()
{
    while (true) {
        echo "\n--- ADMINISTRAR ARMAS ---\n";
        echo "1. Listar todas\n";
        echo "2. Registrar Arma\n";
        echo "3. Equipar Arma a Personaje\n";
        echo "0. Volver\n";

        $op = leerEntrada("Opción: ");
        if ($op == '0') break;

        switch ($op) {
            case '1':
                $armas = Arma::listar();
                foreach ($armas as $a) imprimirArma($a);
                break;
            case '2':
                $nombre = leerEntrada("Nombre: ");
                $tipo = leerEntrada("Tipo: ");
                $danio = (int)leerEntrada("Daño base: ");
                $nivelMin = (int)leerEntrada("Nivel mínimo: ");
                $a = new Arma($nombre, $tipo, $danio, $nivelMin, "disponible");
                $a->guardar();
                echo "Arma guardada con ID {$a->getId()}\n";
                break;
            case '3':
                $idArma = (int)leerEntrada("ID del arma: ");
                $idPersonaje = (int)leerEntrada("ID del personaje: ");
                $arma = Arma::busquedaPorId($idArma);
                $personaje = Personaje::busquedaPorId($idPersonaje);
                if ($arma && $personaje) {
                    $torneo = new Torneo();
                    if ($torneo->equiparArma($personaje, $arma)) {
                        $personaje->guardar();
                        $arma->guardar();
                        echo "Arma equipada exitosamente.\n";
                    } else {
                        echo "El arma no puede ser equipada por este personaje (nivel insuficiente o arma no disponible).\n";
                    }
                } else {
                    echo "Arma o personaje no encontrados.\n";
                }
                break;
        }
    }
}

function subMenuArenas()
{
    while (true) {
        echo "\n--- ADMINISTRAR ARENAS ---\n";
        echo "1. Listar todas\n";
        echo "2. Registrar Arena\n";
        echo "0. Volver\n";

        $op = leerEntrada("Opción: ");
        if ($op == '0') break;

        switch ($op) {
            case '1':
                $arenas = Arena::listar();
                foreach ($arenas as $a) imprimirArena($a);
                break;
            case '2':
                $nombre = leerEntrada("Nombre: ");
                $dificultad = (int)leerEntrada("Dificultad: ");
                $capacidad = (int)leerEntrada("Capacidad de público: ");
                $clima = leerEntrada("Clima (normal, lluvia, tormenta, niebla): ");
                $a = new Arena($nombre, $dificultad, $capacidad, $clima);
                $a->guardar();
                echo "Arena guardada con ID {$a->getid()}\n";
                break;
        }
    }
}

function subMenuDuelos()
{
    while (true) {
        echo "\n--- ADMINISTRAR DUELOS ---\n";
        echo "1. Listar todos\n";
        echo "2. Registrar Duelo\n";
        echo "3. Ejecutar Duelo Pendiente\n";
        echo "0. Volver\n";

        $op = leerEntrada("Opción: ");
        if ($op == '0') break;

        switch ($op) {
            case '1':
                $duelos = Duelo::listar();
                foreach ($duelos as $d) imprimirDuelo($d);
                break;
            case '2':
                $idP1 = (int)leerEntrada("ID Personaje 1: ");
                $idP2 = (int)leerEntrada("ID Personaje 2: ");
                $idArena = (int)leerEntrada("ID Arena: ");
                $p1 = Personaje::busquedaPorId($idP1);
                $p2 = Personaje::busquedaPorId($idP2);
                $arena = Arena::busquedaPorId($idArena);

                if ($p1 && $p2 && $arena) {
                    $fecha = date("Y-m-d H:i:s");
                    $duelo = new Duelo($p1, $p2, $arena, $fecha, "pendiente");
                    if ($duelo->puedeRealizarse()) {
                        $duelo->guardar();
                        echo "Duelo registrado con ID {$duelo->getId()} (Estado: pendiente)\n";
                    } else {
                        echo "El duelo no puede realizarse (verificar que no sean el mismo personaje ni estén lesionados/retirados).\n";
                    }
                } else {
                    echo "Datos inválidos (personajes o arena no encontrados).\n";
                }
                break;
            case '3':
                $idDuelo = (int)leerEntrada("ID del Duelo a ejecutar: ");
                $duelo = Duelo::busquedaPorId($idDuelo);
                if ($duelo && $duelo->getEstado() == "pendiente") {
                    if ($duelo->realizarDuelo()) {
                        $duelo->guardar();
                        $duelo->getPersonaje1()->guardar();
                        $duelo->getPersonaje2()->guardar();
                        $ganador = $duelo->getGanador() ? $duelo->getGanador()->getNombre() : "Empate";
                        echo "Duelo ejecutado. Ganador: {$ganador}\n";
                    } else {
                        echo "No se pudo realizar el duelo en este momento.\n";
                    }
                } else {
                    echo "Duelo no encontrado o no está en estado pendiente.\n";
                }
                break;
        }
    }
}

function consultasObligatorias()
{
    while (true) {
        echo "\n--- CONSULTAS OBLIGATORIAS ---\n";
        echo "1. Listar todos los personajes.\n";
        echo "2. Listar personajes disponibles para duelar.\n";
        echo "3. Listar personajes lesionados.\n";
        echo "4. Listar personajes retirados.\n";
        echo "5. Listar armas disponibles.\n";
        echo "6. Mostrar el arma equipada por cada personaje.\n";
        echo "7. Mostrar todos los duelos realizados.\n";
        echo "8. Mostrar todos los duelos pendientes.\n";
        echo "9. Mostrar el historial de duelos de un personaje.\n";
        echo "10. Mostrar el ranking de personajes ordenado por cantidad de victorias.\n";
        echo "11. Mostrar el personaje con mayor cantidad de victorias.\n";
        echo "12. Mostrar el porcentaje de victorias de cada personaje.\n";
        echo "13. Mostrar la arena donde más duelos se realizaron.\n";
        echo "0. Volver.\n";

        $op = leerEntrada("Seleccione una consulta: ");
        if ($op == '0') break;

        echo "\n-- RESULTADOS --\n";
        switch ($op) {
            case '1':
                foreach (Personaje::listar() as $p) imprimirPersonaje($p);
                break;
            case '2':
                foreach (Personaje::listarDisponibles() as $p) imprimirPersonaje($p);
                break;
            case '3':
                foreach (Personaje::listarLesionados() as $p) imprimirPersonaje($p);
                break;
            case '4':
                foreach (Personaje::listarRetirados() as $p) imprimirPersonaje($p);
                break;
            case '5':
                foreach (Arma::listarDisponibles() as $a) imprimirArma($a);
                break;
            case '6':
                foreach (Personaje::listar() as $p) {
                    $armaStr = $p->getArma() ? $p->getArma()->getNombre() : "Ninguna";
                    echo "Personaje: {$p->getNombre()} | Arma equipada: {$armaStr}\n";
                }
                break;
            case '7':
                foreach (Duelo::listarRealizados() as $d) imprimirDuelo($d);
                break;
            case '8':
                foreach (Duelo::listarPendientes() as $d) imprimirDuelo($d);
                break;
            case '9':
                $id = (int)leerEntrada("Ingrese el ID del personaje: ");
                $duelos = Duelo::historialPorPersonaje($id);
                if (empty($duelos)) echo "Sin historial.\n";
                foreach ($duelos as $d) imprimirDuelo($d);
                break;
            case '10':
                foreach (Personaje::obtenerRanking() as $p) {
                    echo "Nombre: {$p->getNombre()} | Victorias: {$p->getDuelosGanados()}\n";
                }
                break;
            case '11':
                $p = Personaje::obtenerPersonajeMasVictorias();
                if ($p) echo "Mayor ganador: {$p->getNombre()} con {$p->getDuelosGanados()} victorias.\n";
                else echo "No hay personajes registrados.\n";
                break;
            case '12':
                $porcentajes = Personaje::obtenerPorcentajeVictorias();
                foreach ($porcentajes as $nombre => $pct) {
                    echo "Personaje: {$nombre} | Winrate: {$pct}%\n";
                }
                break;
            case '13':
                $a = Arena::obtenerArenaMasDuelos();
                if ($a) echo "La arena con más duelos es '{$a->getnombre()}' (ID: {$a->getid()}).\n";
                else echo "No hay duelos registrados.\n";
                break;
            default:
                echo "Opción inválida.\n";
        }
    }
}

// Bucle principal
while (true) {
    echo "\n============================================\n";
    echo "   LOS JUEGOS DEL HAMBRE - MENÚ PRINCIPAL   \n";
    echo "============================================\n";
    echo "1. Administrar Personajes\n";
    echo "2. Administrar Armas\n";
    echo "3. Administrar Arenas\n";
    echo "4. Administrar Duelos\n";
    echo "5. Consultas Obligatorias\n";
    echo "0. Salir\n";

    $opcion = leerEntrada("Seleccione una opción: ");

    if ($opcion == '0') {
        echo "Saliendo...\n";
        break;
    }

    switch ($opcion) {
        case '1':
            subMenuPersonajes();
            break;
        case '2':
            subMenuArmas();
            break;
        case '3':
            subMenuArenas();
            break;
        case '4':
            subMenuDuelos();
            break;
        case '5':
            consultasObligatorias();
            break;
        default:
            echo "Opción inválida.\n";
    }
}

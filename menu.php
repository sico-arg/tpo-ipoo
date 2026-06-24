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

/**************************************/
/***** DEFINICION DE FUNCIONES ********/
/**************************************/

/**
 * Esta función solicita al usuario un número dentro de un rango específico
 * @param int $min
 * @param int $max
 * @return int
 */
function solicitarNumeroEntre($min, $max)
{
    $red = "\033[31m";
    $reset = "\033[0m";
    $numero = trim(fgets(STDIN));
    while (!is_numeric($numero) || $numero < $min || $numero > $max) {
        echo "{$red}Error. Ingrese un número entre $min y $max: {$reset}";
        $numero = trim(fgets(STDIN));
    }
    return (int)$numero;
}

/**
 * Esta función solicita al usuario una cadena de texto genérica
 * @param string $mensaje
 * @return string
 */
function leerCadena($mensaje)
{
    $yellow = "\033[33m";
    $reset = "\033[0m";
    echo "{$yellow}► {$mensaje}{$reset}";
    return trim(fgets(STDIN));
}

/**
 * Esta función solicita al usuario un número entero genérico (sin límite)
 * @param string $mensaje
 * @return int
 */
function solicitarEntero($mensaje)
{
    $yellow = "\033[33m";
    $red = "\033[31m";
    $reset = "\033[0m";
    echo "{$yellow}► {$mensaje}{$reset}";
    $numero = trim(fgets(STDIN));
    while (!is_numeric($numero)) {
        echo "{$red}Error. Ingrese un valor numérico válido: {$reset}";
        $numero = trim(fgets(STDIN));
    }
    return (int)$numero;
}

/**
 * Esta función muestra por pantalla los datos de un Personaje
 * @param Personaje $p
 */
function imprimirPersonaje(Personaje $p)
{
    $yellow  = "\033[33m";
    $reset   = "\033[0m";
    echo "{$yellow}" . $p . "{$reset}\n";
}

/**
 * Esta función muestra por pantalla los datos de un Arma
 * @param Arma $a
 */
function imprimirArma(Arma $a)
{
    $yellow  = "\033[33m";
    $reset   = "\033[0m";
    echo "{$yellow}" . $a . "{$reset}\n";
}

/**
 * Esta función muestra por pantalla los datos de una Arena
 * @param Arena $a
 */
function imprimirArena(Arena $a)
{
    $yellow  = "\033[33m";
    $reset   = "\033[0m";
    echo "{$yellow}" . $a . "{$reset}\n";
}

/**
 * Esta función muestra por pantalla los datos de un Duelo
 * @param Duelo $d
 */
function imprimirDuelo(Duelo $d)
{
    $yellow  = "\033[33m";
    $reset   = "\033[0m";
    echo "{$yellow}" . $d . "{$reset}\n";
}

/**
 * Muestra y gestiona el menú principal
 * @return int
 */
function seleccionarOpcionPrincipal()
{
    $cyan    = "\033[36m";
    $yellow  = "\033[33m";
    $green   = "\033[32m";
    $magenta = "\033[35m";
    $reset   = "\033[0m";

    echo "\n{$magenta}═══════════════════════════════════════════════{$reset}\n";
    echo "{$yellow}        ⚔️  {$green}Los Juegos del Hambre - Menú{$reset}\n";
    echo "{$magenta}═══════════════════════════════════════════════{$reset}\n\n";

    echo "{$cyan} 1){$reset} Administrar Personajes\n";
    echo "{$cyan} 2){$reset} Administrar Armas\n";
    echo "{$cyan} 3){$reset} Administrar Arenas\n";
    echo "{$cyan} 4){$reset} Administrar Duelos\n";
    echo "{$cyan} 5){$reset} Consultas Obligatorias\n";
    echo "{$cyan} 0){$reset} Salir\n\n";

    echo "{$yellow}► Ingrese una opción (0-5): {$reset}";
    return solicitarNumeroEntre(0, 5);
}

/**
 * Muestra y gestiona el menú de Personajes
 * @return int
 */
function seleccionarOpcionPersonajes()
{
    $cyan    = "\033[36m";
    $yellow  = "\033[33m";
    $green   = "\033[32m";
    $magenta = "\033[35m";
    $reset   = "\033[0m";

    echo "\n{$magenta}═══════════════════════════════════════════════{$reset}\n";
    echo "{$yellow}        👥  {$green}Administrar Personajes{$reset}\n";
    echo "{$magenta}═══════════════════════════════════════════════{$reset}\n\n";

    echo "{$cyan} 1){$reset} Listar todos\n";
    echo "{$cyan} 2){$reset} Registrar Guerrero\n";
    echo "{$cyan} 3){$reset} Registrar Mago\n";
    echo "{$cyan} 4){$reset} Registrar Arquero\n";
    echo "{$cyan} 5){$reset} Recuperar personaje lesionado\n";
    echo "{$cyan} 0){$reset} Volver\n\n";

    echo "{$yellow}► Ingrese una opción (0-5): {$reset}";
    return solicitarNumeroEntre(0, 5);
}

/**
 * Submenú para gestionar Personajes
 */
function subMenuPersonajes()
{
    $green = "\033[32m";
    $red   = "\033[31m";
    $reset = "\033[0m";

    do {
        $opcion = seleccionarOpcionPersonajes();
        switch ($opcion) {
            case 1:
                echo "\n";
                $personajes = Personaje::listar();
                foreach ($personajes as $p) imprimirPersonaje($p);
                break;
            case 2:
                $nombre = leerCadena("Nombre: ");
                $nivel = solicitarEntero("Nivel: ");
                $pv = solicitarEntero("Puntos de Vida: ");
                $energia = solicitarEntero("Energía: ");
                $fuerza = solicitarEntero("Fuerza: ");
                $armadura = solicitarEntero("Armadura: ");
                $g = new Guerrero($nombre, $nivel, $pv, $energia, $fuerza, $armadura);
                $g->guardar();
                echo "{$green}Guerrero guardado con ID {$g->getId()}{$reset}\n";
                break;
            case 3:
                $nombre = leerCadena("Nombre: ");
                $nivel = solicitarEntero("Nivel: ");
                $pv = solicitarEntero("Puntos de Vida: ");
                $energia = solicitarEntero("Energía: ");
                $mana = solicitarEntero("Mana: ");
                $inteligencia = solicitarEntero("Inteligencia: ");
                $m = new Mago($nombre, $nivel, $pv, $energia, $mana, $inteligencia);
                $m->guardar();
                echo "{$green}Mago guardado con ID {$m->getId()}{$reset}\n";
                break;
            case 4:
                $nombre = leerCadena("Nombre: ");
                $nivel = solicitarEntero("Nivel: ");
                $pv = solicitarEntero("Puntos de Vida: ");
                $energia = solicitarEntero("Energía: ");
                $precision = solicitarEntero("Precisión: ");
                $velocidad = solicitarEntero("Velocidad: ");
                $a = new Arquero($nombre, $nivel, $pv, $energia, $precision, $velocidad);
                $a->guardar();
                echo "{$green}Arquero guardado con ID {$a->getId()}{$reset}\n";
                break;
            case 5:
                $id = solicitarEntero("ID del personaje lesionado: ");
                $p = Personaje::busquedaPorId($id);
                if ($p) {
                    if ($p->getEstado() == "lesionado") {
                        $p->recuperarVida(100);
                        $p->guardar();
                        echo "{$green}Personaje recuperado exitosamente.{$reset}\n";
                    } else {
                        echo "{$red}El personaje no está lesionado.{$reset}\n";
                    }
                } else {
                    echo "{$red}Personaje no encontrado.{$reset}\n";
                }
                break;
        }
    } while ($opcion != 0);
}

/**
 * Muestra y gestiona el menú de Armas
 * @return int
 */
function seleccionarOpcionArmas()
{
    $cyan    = "\033[36m";
    $yellow  = "\033[33m";
    $green   = "\033[32m";
    $magenta = "\033[35m";
    $reset   = "\033[0m";

    echo "\n{$magenta}═══════════════════════════════════════════════{$reset}\n";
    echo "{$yellow}        🗡️  {$green}Administrar Armas{$reset}\n";
    echo "{$magenta}═══════════════════════════════════════════════{$reset}\n\n";

    echo "{$cyan} 1){$reset} Listar todas\n";
    echo "{$cyan} 2){$reset} Registrar Arma\n";
    echo "{$cyan} 3){$reset} Equipar Arma a Personaje\n";
    echo "{$cyan} 0){$reset} Volver\n\n";

    echo "{$yellow}► Ingrese una opción (0-3): {$reset}";
    return solicitarNumeroEntre(0, 3);
}

/**
 * Submenú para gestionar Armas
 */
function subMenuArmas()
{
    $green = "\033[32m";
    $red   = "\033[31m";
    $reset = "\033[0m";

    do {
        $opcion = seleccionarOpcionArmas();
        switch ($opcion) {
            case 1:
                echo "\n";
                $armas = Arma::listar();
                foreach ($armas as $a) imprimirArma($a);
                break;
            case 2:
                $nombre = leerCadena("Nombre: ");
                $tipo = leerCadena("Tipo: ");
                $danio = solicitarEntero("Daño base: ");
                $nivelMin = solicitarEntero("Nivel mínimo: ");
                $a = new Arma($nombre, $tipo, $danio, $nivelMin, "disponible");
                $a->guardar();
                echo "{$green}Arma guardada con ID {$a->getId()}{$reset}\n";
                break;
            case 3:
                $idArma = solicitarEntero("ID del arma: ");
                $idPersonaje = solicitarEntero("ID del personaje: ");
                $arma = Arma::busquedaPorId($idArma);
                $personaje = Personaje::busquedaPorId($idPersonaje);
                if ($arma && $personaje) {
                    $torneo = new Torneo();
                    if ($torneo->equiparArma($personaje, $arma)) {
                        $personaje->guardar();
                        $arma->guardar();
                        echo "{$green}Arma equipada exitosamente.{$reset}\n";
                    } else {
                        echo "{$red}El arma no puede ser equipada por este personaje (nivel insuficiente o arma no disponible).{$reset}\n";
                    }
                } else {
                    echo "{$red}Arma o personaje no encontrados.{$reset}\n";
                }
                break;
        }
    } while ($opcion != 0);
}

/**
 * Muestra y gestiona el menú de Arenas
 * @return int
 */
function seleccionarOpcionArenas()
{
    $cyan    = "\033[36m";
    $yellow  = "\033[33m";
    $green   = "\033[32m";
    $magenta = "\033[35m";
    $reset   = "\033[0m";

    echo "\n{$magenta}═══════════════════════════════════════════════{$reset}\n";
    echo "{$yellow}        🏟️  {$green}Administrar Arenas{$reset}\n";
    echo "{$magenta}═══════════════════════════════════════════════{$reset}\n\n";

    echo "{$cyan} 1){$reset} Listar todas\n";
    echo "{$cyan} 2){$reset} Registrar Arena\n";
    echo "{$cyan} 0){$reset} Volver\n\n";

    echo "{$yellow}► Ingrese una opción (0-2): {$reset}";
    return solicitarNumeroEntre(0, 2);
}

/**
 * Submenú para gestionar Arenas
 */
function subMenuArenas()
{
    $green = "\033[32m";
    $reset = "\033[0m";

    do {
        $opcion = seleccionarOpcionArenas();
        switch ($opcion) {
            case 1:
                echo "\n";
                $arenas = Arena::listar();
                foreach ($arenas as $a) imprimirArena($a);
                break;
            case 2:
                $nombre = leerCadena("Nombre: ");
                $dificultad = solicitarEntero("Dificultad: ");
                $capacidad = solicitarEntero("Capacidad de público: ");
                $clima = leerCadena("Clima (normal, lluvia, tormenta, niebla): ");
                $a = new Arena($nombre, $dificultad, $capacidad, $clima);
                $a->guardar();
                echo "{$green}Arena guardada con ID {$a->getId()}{$reset}\n";
                break;
        }
    } while ($opcion != 0);
}

/**
 * Muestra y gestiona el menú de Duelos
 * @return int
 */
function seleccionarOpcionDuelos()
{
    $cyan    = "\033[36m";
    $yellow  = "\033[33m";
    $green   = "\033[32m";
    $magenta = "\033[35m";
    $reset   = "\033[0m";

    echo "\n{$magenta}═══════════════════════════════════════════════{$reset}\n";
    echo "{$yellow}        ⚡  {$green}Administrar Duelos{$reset}\n";
    echo "{$magenta}═══════════════════════════════════════════════{$reset}\n\n";

    echo "{$cyan} 1){$reset} Listar todos\n";
    echo "{$cyan} 2){$reset} Registrar Duelo\n";
    echo "{$cyan} 3){$reset} Ejecutar Duelo Pendiente\n";
    echo "{$cyan} 0){$reset} Volver\n\n";

    echo "{$yellow}► Ingrese una opción (0-3): {$reset}";
    return solicitarNumeroEntre(0, 3);
}

/**
 * Submenú para gestionar Duelos
 */
function subMenuDuelos()
{
    $green = "\033[32m";
    $red   = "\033[31m";
    $reset = "\033[0m";

    do {
        $opcion = seleccionarOpcionDuelos();
        switch ($opcion) {
            case 1:
                echo "\n";
                $duelos = Duelo::listar();
                foreach ($duelos as $d) imprimirDuelo($d);
                break;
            case 2:
                $idP1 = solicitarEntero("ID Personaje 1: ");
                $idP2 = solicitarEntero("ID Personaje 2: ");
                $idArena = solicitarEntero("ID Arena: ");
                $p1 = Personaje::busquedaPorId($idP1);
                $p2 = Personaje::busquedaPorId($idP2);
                $arena = Arena::busquedaPorId($idArena);

                if ($p1 && $p2 && $arena) {
                    $fecha = date("Y-m-d H:i:s");
                    $duelo = new Duelo($p1, $p2, $arena, $fecha, "pendiente");
                    if ($duelo->puedeRealizarse()) {
                        $duelo->guardar();
                        echo "{$green}Duelo registrado con ID {$duelo->getId()} (Estado: pendiente){$reset}\n";
                    } else {
                        echo "{$red}El duelo no puede realizarse (verificar que no sean el mismo personaje ni estén lesionados/retirados).{$reset}\n";
                    }
                } else {
                    echo "{$red}Datos inválidos (personajes o arena no encontrados).{$reset}\n";
                }
                break;
            case 3:
                $idDuelo = solicitarEntero("ID del Duelo a ejecutar: ");
                $duelo = Duelo::busquedaPorId($idDuelo);
                if ($duelo && $duelo->getEstado() == "pendiente") {
                    if ($duelo->realizarDuelo()) {
                        $duelo->guardar();
                        $duelo->getPersonaje1()->guardar();
                        $duelo->getPersonaje2()->guardar();
                        $ganador = $duelo->getGanador() ? $duelo->getGanador()->getNombre() : "Empate";
                        echo "{$green}Duelo ejecutado. Ganador: {$ganador}{$reset}\n";
                    } else {
                        echo "{$red}No se pudo realizar el duelo en este momento.{$reset}\n";
                    }
                } else {
                    echo "{$red}Duelo no encontrado o no está en estado pendiente.{$reset}\n";
                }
                break;
        }
    } while ($opcion != 0);
}

/**
 * Muestra y gestiona el menú de Consultas Obligatorias
 * @return int
 */
function seleccionarOpcionConsultas()
{
    $cyan    = "\033[36m";
    $yellow  = "\033[33m";
    $green   = "\033[32m";
    $magenta = "\033[35m";
    $reset   = "\033[0m";

    echo "\n{$magenta}═══════════════════════════════════════════════{$reset}\n";
    echo "{$yellow}        📊  {$green}Consultas Obligatorias{$reset}\n";
    echo "{$magenta}═══════════════════════════════════════════════{$reset}\n\n";

    echo "{$cyan}  1){$reset} Listar todos los personajes\n";
    echo "{$cyan}  2){$reset} Listar personajes disponibles para duelar\n";
    echo "{$cyan}  3){$reset} Listar personajes lesionados\n";
    echo "{$cyan}  4){$reset} Listar personajes retirados\n";
    echo "{$cyan}  5){$reset} Listar armas disponibles\n";
    echo "{$cyan}  6){$reset} Mostrar el arma equipada por cada personaje\n";
    echo "{$cyan}  7){$reset} Mostrar todos los duelos realizados\n";
    echo "{$cyan}  8){$reset} Mostrar todos los duelos pendientes\n";
    echo "{$cyan}  9){$reset} Mostrar el historial de duelos de un personaje\n";
    echo "{$cyan} 10){$reset} Mostrar el ranking de personajes (por victorias)\n";
    echo "{$cyan} 11){$reset} Mostrar el personaje con mayor cantidad de victorias\n";
    echo "{$cyan} 12){$reset} Mostrar el porcentaje de victorias de cada personaje\n";
    echo "{$cyan} 13){$reset} Mostrar la arena donde más duelos se realizaron\n";
    echo "{$cyan}  0){$reset} Volver\n\n";

    echo "{$yellow}► Ingrese una opción (0-13): {$reset}";
    return solicitarNumeroEntre(0, 13);
}

/**
 * Submenú para las Consultas Obligatorias
 */
function consultasObligatorias()
{
    $cyan    = "\033[36m";
    $yellow  = "\033[33m";
    $green   = "\033[32m";
    $magenta = "\033[35m";
    $reset   = "\033[0m";

    do {
        $opcion = seleccionarOpcionConsultas();

        if ($opcion != 0) {
            echo "\n{$magenta}-- RESULTADOS --{$reset}\n";
        }

        switch ($opcion) {
            case 1:
                foreach (Personaje::listar() as $p) imprimirPersonaje($p);
                break;
            case 2:
                foreach (Personaje::listarDisponibles() as $p) imprimirPersonaje($p);
                break;
            case 3:
                foreach (Personaje::listarLesionados() as $p) imprimirPersonaje($p);
                break;
            case 4:
                foreach (Personaje::listarRetirados() as $p) imprimirPersonaje($p);
                break;
            case 5:
                foreach (Arma::listarDisponibles() as $a) imprimirArma($a);
                break;
            case 6:
                foreach (Personaje::listar() as $p) {
                    $armaStr = $p->getArma() ? $p->getArma()->getNombre() : "Ninguna";
                    echo "{$yellow}Personaje:{$reset} {$p->getNombre()} | {$cyan}Arma equipada:{$reset} {$armaStr}\n";
                }
                break;
            case 7:
                foreach (Duelo::listarRealizados() as $d) imprimirDuelo($d);
                break;
            case 8:
                foreach (Duelo::listarPendientes() as $d) imprimirDuelo($d);
                break;
            case 9:
                $id = solicitarEntero("Ingrese el ID del personaje: ");
                $duelos = Duelo::historialPorPersonaje($id);
                if (empty($duelos)) echo "{$yellow}Sin historial.{$reset}\n";
                foreach ($duelos as $d) imprimirDuelo($d);
                break;
            case 10:
                foreach (Personaje::obtenerRanking() as $p) {
                    echo "{$yellow}Nombre:{$reset} {$p->getNombre()} | {$green}Victorias:{$reset} {$p->getDuelosGanados()}\n";
                }
                break;
            case 11:
                $p = Personaje::obtenerPersonajeMasVictorias();
                if ($p) echo "{$green}Mayor ganador:{$reset} {$p->getNombre()} con {$p->getDuelosGanados()} victorias.\n";
                else echo "{$yellow}No hay personajes registrados.{$reset}\n";
                break;
            case 12:
                $porcentajes = Personaje::obtenerPorcentajeVictorias();
                foreach ($porcentajes as $nombre => $pct) {
                    echo "{$yellow}Personaje:{$reset} {$nombre} | {$cyan}Winrate:{$reset} {$pct}%\n";
                }
                break;
            case 13:
                $a = Arena::obtenerArenaMasDuelos();
                if ($a) echo "{$green}La arena con más duelos es '{$a->getNombre()}' (ID: {$a->getId()}).{$reset}\n";
                else echo "{$yellow}No hay duelos registrados.{$reset}\n";
                break;
        }
    } while ($opcion != 0);
}


/**************************************/
/*********** PROGRAMA PRINCIPAL *******/
/**************************************/

//Declaración e inicialización de variables:
$magenta = "\033[35m";
$yellow  = "\033[33m";
$reset   = "\033[0m";
$opcion  = 0;

//Proceso:

//Menu de opciones
do {
    //Mensaje del menu de opciones principal
    $opcion = seleccionarOpcionPrincipal();

    switch ($opcion) {
        case 1:
            subMenuPersonajes();
            break;
        case 2:
            subMenuArmas();
            break;
        case 3:
            subMenuArenas();
            break;
        case 4:
            subMenuDuelos();
            break;
        case 5:
            consultasObligatorias();
            break;
        case 0:
            echo "\n{$yellow}Saliendo del sistema de torneos... ¡Hasta luego!{$reset}\n";
            break;
    }
} while ($opcion != 0);

//Mensaje de fin de programa
echo "{$magenta}Fin del programa.{$reset}\n";

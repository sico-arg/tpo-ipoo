<?php
// ==========================================
//                 INCLUSIONES                 
// ==========================================


include 'personaje.php';
include 'arma.php';
include 'arena.php';
include 'duelo.php';


class torneo
{

    // ==========================================
    //                 ATRIBUTOS              
    // ==========================================
    private array $personajes = [];
    private array  $armas = [];
    private array  $arenas = [];
    private array $duelos = [];


    // ==========================================
    //                 CONSTRUCTOR         
    // ==========================================
    public function __construct() {}

    // ==========================================
    //                 GETTERS          
    // ==========================================

    public function getPersonajes()
    {
        return $this->personajes;
    }
    public function getArmas()
    {
        return $this->armas;
    }
    public function getArenas()
    {
        return $this->arenas;
    }

    public function getDuelos()
    {
        return $this->duelos;
    }

    // ==========================================
    //                 SETTERS              
    // ==========================================

    private function setPersonajes(Personaje $nuevoPersonaje)
    {
        $this->personajes[] = $nuevoPersonaje;
    }
    private function setArmas(Arma $nuevaArma)
    {
        $this->armas[] = $nuevaArma;
    }
    private function setArenas(Arena $nuevaArena)
    {
        $this->arenas[] = $nuevaArena;
    }

    private function setDuelos(Duelo $nuevoDuelo)
    {
        $this->duelos[] = $nuevoDuelo;
    }

    // ==========================================
    //                 METODOS              
    // ==========================================



    public function agregarPersonaje(Personaje $personaje)
    {
        $this->setPersonajes($personaje);
    }
    public function agregarArma(Arma $arma)
    {
        $this->setArmas($arma);
    }
    public function agregarArena(Arena $arena)
    {
        $this->setArenas($arena);
    }
    public function equiparArma()
    {
        $exito = false;
        $armaEquipada = null;
        $personajeArma = null;

        foreach ($this->personajes as $personaje) {
            if ($personaje->getid()) {
                $personajeArma = $personaje;
                break;
            }
        }
        foreach ($this->armas as $arma) {
            if ($arma->getid()) {
                $armaEquipada = $arma;
                break;
            }
        }
        if ($personajeArma !== null && $armaEquipada !== null) {
            if ($armaEquipada->puedeSerEquipadoPor($personajeArma)) {
                $personajeArma->setarma($armaEquipada);
                $armaEquipada->setEstado('equipado');
                $exito = true;
            }
        }
        return $exito;
    }
    public function realizarDuelo()
    {
        $realizado = false;
        foreach ($this->duelos as $duelo) {
            if ($duelo->getid()) {
                if ($duelo->x()) {
                    //necesito un metodo de la clase duelo)
                    $realizado = true;
                }
                break;
            }
        }
        return $realizado;
    }
    public function rankingPersonaje()
    {
        $ranking = $this->personajes;
        $puntaje = 0;
        foreach ($this->duelos as $duelo) {
            if ($ranking) {
                $ganadorPersonaje1 =  $duelo->obtenerGanador();
                $ganadorPersonaje2 =  $duelo->obtenerGanador();
            }
            if ($ganadorPersonaje1 == $ganadorPersonaje2) {
                $ranking = 0;
            }
            if ($ganadorPersonaje1 > $ganadorPersonaje2) {
                $puntaje++;
            }
            if ($ganadorPersonaje2 > $ganadorPersonaje1) {
                $puntaje++;
            }
            $ranking = $puntaje;
        }
        return $ranking;
    }

    public function listarPersona()
    {
        $listadoPersonaje = "";
        foreach ($this->personajes as $personaje) {
            $listadoPersonaje = count($personaje);
        }
        return $listadoPersonaje;
    }
    public function listarArma()
    {
        $listadoArma = "";
        foreach ($this->armas as $arma) {
            $listadoArma = count($arma);
        }
        return $listadoArma;
    }
    public function listarArena()
    {
        $listadoArena = "";
        foreach ($this->arenas as $arena) {
            $listadoArena = count($arena);
        }
        return $listadoArena;
    }

    public function listarDuelos()
    {
        $listadoDuelos = "";
        foreach ($this->duelos as $duelo) {
            $listadoDuelos = count($duelo);
        }
        return $listadoDuelos;
    }
}

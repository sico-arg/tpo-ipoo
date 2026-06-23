<?php
// ==========================================
//                 INCLUSIONES                 
// ==========================================

require_once 'src/Personaje/Guerrero.php';
require_once 'src/Personaje/Mago.php';
require_once 'src/Personaje/Arquero.php';
require_once 'src/Duelo.php';

class Torneo
{
    // ==========================================
    //                 ATRIBUTOS              
    // ==========================================
    private array $personajes = [];
    private array $armas = [];
    private array $arenas = [];
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
    public function registrarDuelo(Duelo $duelo)
    {
        $this->setDuelos($duelo);
    }

    /**
     * Equips a weapon to a character if allowed.
     */
    public function equiparArma(Personaje $personaje, Arma $arma)
    {
        $exito = false;
        if ($arma->puedeSerEquipadoPor($personaje)) {
            $personaje->setArma($arma);
            $arma->setEstado('equipada');
            $exito = true;
        }
        return $exito;
    }

    /**
     * Executes a specific duel.
     */
    public function realizarDuelo(Duelo $duelo)
    {
        return $duelo->realizarDuelo();
    }

    /**
     * Lists all characters.
     */
    public function listarPersonajes()
    {
        return $this->personajes;
    }

    /**
     * Lists all weapons.
     */
    public function listarArmas()
    {
        return $this->armas;
    }

    /**
     * Lists all arenas.
     */
    public function listarArenas()
    {
        return $this->arenas;
    }

    /**
     * Lists all duels.
     */
    public function listarDuelos()
    {
        return $this->duelos;
    }

    /**
     * Returns characters sorted by victories (duelosGanados) descending.
     */
    public function rankingPersonajes()
    {
        $ranking = $this->personajes;
        usort($ranking, function ($a, $b) {
            return $b->getDuelosGanados() <=> $a->getDuelosGanados();
        });
        return $ranking;
    }

    // ==================================================
    //  ALIASES FOR OLD/INCORRECT STUDENT METHOD NAMES
    // ==================================================
    public function listarPersona()
    {
        return $this->listarPersonajes();
    }
    public function listarArma()
    {
        return $this->listarArmas();
    }
    public function listarArena()
    {
        return $this->listarArenas();
    }
    public function rankingPersonaje()
    {
        return $this->rankingPersonajes();
    }
}

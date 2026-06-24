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
     * Este metodo equipa un arma a un personaje si es permitido
     * @param Personaje $personaje
     * @param Arma $arma
     * @return bool
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
     * Este metodo ejecuta un duelo especifico
     * @param Duelo $duelo
     * @return bool
     */
    public function realizarDuelo(Duelo $duelo)
    {
        return $duelo->realizarDuelo();
    }

    /**
     * Este metodo lista todos los personajes del torneo
     * @return array
     */
    public function listarPersonajes()
    {
        return $this->personajes;
    }

    /**
     * Este metodo lista todas las armas del torneo
     * @return array
     */
    public function listarArmas()
    {
        return $this->armas;
    }

    /**
     * Este metodo lista todas las arenas del torneo
     * @return array
     */
    public function listarArenas()
    {
        return $this->arenas;
    }

    /**
     * Este metodo lista todos los duelos del torneo
     * @return array
     */
    public function listarDuelos()
    {
        return $this->duelos;
    }

    /**
     * Este metodo retorna los personajes ordenados por cantidad de victorias de forma descendente
     * @return array
     */
    public function rankingPersonajes()
    {
        $arrayPersonajes = $this->personajes;
        /**
         * El [$this, 'ordenarPersonajes'] es porque si ponemos solo ordenarPersonaje 
         * intenta llegar a una funcion global como count(), para que sepa donde buscar 
         * el metodo primero le pasamos la instancia y luego le pasamos el metodo
         */
        usort($arrayPersonajes, [$this, 'ordenarPersonajes']);
        return $arrayPersonajes;
    }

    public function ordenarPersonajes(Personaje $personaje1, Personaje $personaje2)
    {
        $valorRetornar = 0;
        $victorias1 = $personaje1->getDuelosGanados();
        $victorias2 = $personaje2->getDuelosGanados();

        if ($victorias1 < $victorias2) {
            $valorRetornar = 1;  // Si el 2 tiene más victorias, va antes (orden descendente)
        } elseif ($victorias1 > $victorias2) {
            $valorRetornar = -1; // Si el 1 tiene más victorias, va antes
        } else {
            $valorRetornar = 0;  // Empate
        }

        return $valorRetornar;
    }


    // ==================================================
    //  ALIASES PARA NOMBRES DE MÉTODOS ANTERIORES
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

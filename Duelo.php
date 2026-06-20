<?php

// ==========================================
//                 INCLUSIONES                 
// ==========================================

include_once __DIR__ . '/Personaje/Personaje.php';
include_once __DIR__ . '/Arena.php';

class Duelo
{
    // ==========================================
    //                 ATRIBUTOS                 
    // ==========================================
    private int $id;
    private Personaje $personaje1;
    private Personaje $personaje2;
    private Arena $arena;
    private string $fecha;
    private string $estado;
    private ?Personaje $ganador;

    // ==========================================
    //                 CONSTRUCTOR                 
    // ==========================================

    public function __construct(int $id, Personaje $personaje1, Personaje $personaje2, Arena $arena, string $fecha, string $estado, ?Personaje $ganador = null)
    {
        $this->id = $id;
        $this->personaje1 = $personaje1;
        $this->personaje2 = $personaje2;
        $this->arena = $arena;
        $this->fecha = $fecha;
        $this->estado = $estado;
        $this->ganador = $ganador;
    }

    // ==========================================
    //                 GETTERS                 
    // ==========================================

    public function getId()
    {
        return $this->id;
    }
    public function getPersonaje1()
    {
        return $this->personaje1;
    }
    public function getPersonaje2()
    {
        return $this->personaje2;
    }
    public function getArena()
    {
        return $this->arena;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function getGanador()
    {
        return $this->ganador;
    }

    // ==========================================
    //                 SETTERS                 
    // ==========================================

    private function setId(int $nuevoId)
    {
        $this->id = $nuevoId;
    }
    private function setPersonaje1(Personaje $nuevoPersonaje1)
    {
        $this->personaje1 = $nuevoPersonaje1;
    }
    private function setPersonaje2(Personaje $nuevoPersonaje2)
    {
        $this->personaje2 = $nuevoPersonaje2;
    }
    private function setArena(Arena $nuevaArena)
    {
        $this->arena = $nuevaArena;
    }
    private function setFecha(string $nuevaFecha)
    {
        $this->fecha = $nuevaFecha;
    }
    public function setEstado(string $nuevoEstado)
    {
        $this->estado = $nuevoEstado;
    }
    public function setGanador(?Personaje $nuevoGanador)
    {
        $this->ganador = $nuevoGanador;
    }

    // ==========================================
    //                 METODOS                
    // ==========================================

    /**
     * Este metodo verifica si el duelo puede llevarse a cabo basandose en el estado de los personajes
     * @return bool
     */
    public function puedeRealizarse(): bool
    {
        $personaje1Actual = $this->getPersonaje1();
        $personaje2Actual = $this->getPersonaje2();
        $personaje1Lesionado = $this->estaLesionado($personaje1Actual);
        $personaje2Lesionado = $this->estaLesionado($personaje2Actual);
        $personaje1Retirado = $this->estaRetirado($personaje1Actual);
        $personaje2Retirado = $this->estaRetirado($personaje2Actual);
        $puedeRealizarse = false;

        if ($personaje1Actual !== $personaje2Actual && !$personaje1Lesionado && !$personaje2Lesionado && !$personaje1Retirado && !$personaje2Retirado) $puedeRealizarse = true;
        return $puedeRealizarse;
    }


    /**
     * Este metodo verifica si un personaje esta en estado lesionado
     * @param Personaje $personaje
     * @return bool
     */
    public function estaLesionado(Personaje $personaje): bool
    {
        $lesionado = false;
        $estadoPersonaje = $personaje->getEstado();
        if ($estadoPersonaje == "lesionado") {
            $lesionado = true;
        }
        return $lesionado;
    }

    /**
     * Este metodo verifica si un personaje esta en estado retirado
     * @param Personaje $personaje
     * @return bool
     */
    public function estaRetirado(Personaje $personaje): bool
    {
        $retirado = false;
        $estadoPersonaje = $personaje->getEstado();
        if ($estadoPersonaje == "retirado") {
            $retirado = true;
        }
        return $retirado;
    }

    public function realizarDuelo()
    {
        $personaje1Actual = $this->getPersonaje1();
        $personaje2Actual = $this->getPersonaje2();
        $puedenRealizarDuelo = $this->puedeRealizarse();
        $ganadorDuelo = $this->obtenerGanador();
        $arenaActual = $this->getArena();

        if ($puedenRealizarDuelo) {
            if ($ganadorDuelo === $personaje1Actual) {
                //Acciones a personaje 1 en caso de que gane
                $personaje1Actual->aumentarNivel();
                $personaje1Actual->recuperarEnergia(5);
                $personaje1Actual->sumarDuelosGanados();

                //Acciones a personaje 2 en caso de que gane personaje 1
                $personaje2Actual->recibirDanio($personaje1Actual->calcularPoderTotal($arenaActual) - $personaje2Actual->calcularPoderTotal($arenaActual));
                $personaje2Actual->sumarDuelosPerdidos();
                $personaje2Actual->perderEnergia(5);
            } elseif ($ganadorDuelo === $personaje2Actual) {
                //Acciones a personaje 2 en caso de que gane
                $personaje2Actual->aumentarNivel();
                $personaje2Actual->recuperarEnergia(5);
                $personaje2Actual->sumarDuelosGanados();

                //Acciones a personaje 1 en caso de que gane personaje 2
                $personaje1Actual->recibirDanio($personaje2Actual->calcularPoderTotal($arenaActual) - $personaje1Actual->calcularPoderTotal($arenaActual));
                $personaje1Actual->sumarDuelosPerdidos();
                $personaje1Actual->perderEnergia(5);
            } else {
                // En caso de empate, ambos sumamos duelos perdidos?
                // The README says "El personaje con mayor poder será declarado ganador."
                // In case of exact tie, we just do nothing or treat as tie.
            }
            $this->setEstado('realizado');
            $this->setGanador($ganadorDuelo);
            return true;
        }
        return false;
    }

    public function obtenerGanador()
    {
        $personaje1Actual = $this->getPersonaje1();
        $personaje2Actual = $this->getPersonaje2();
        $arenaActual = $this->getArena();
        $poderTotalPersonaje1 = $personaje1Actual->calcularPoderTotal($arenaActual);
        $poderTotalPersonaje2 = $personaje2Actual->calcularPoderTotal($arenaActual);
        $personajeGanador = null;
        if ($poderTotalPersonaje1 > $poderTotalPersonaje2) {
            $personajeGanador = $personaje1Actual;
        } elseif ($poderTotalPersonaje1 < $poderTotalPersonaje2) {
            $personajeGanador = $personaje2Actual;
        }
        return $personajeGanador;
    }
}

<?php

// ==========================================
//                 INCLUSIONES                 
// ==========================================

include_once __DIR__ . '/Personaje/Personaje.php';

class Arena
{

    // ==========================================
    //                 ATRIBUTOS                
    // ==========================================

    private int $id;
    private string $nombre;
    private int $dificultad;
    private int $capacidadPublico;
    private string $clima;

    // ==========================================
    //                 CONSTRUCTOR                
    // ==========================================

    public function __construct(int $id, string $nombre, int $dificultad, int $capacidadPublico, string $clima)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->dificultad = $dificultad;
        $this->capacidadPublico = $capacidadPublico;
        $this->clima = $clima;
    }

    // ==========================================
    //                 GETTERS                 
    // ==========================================

    public function getid()
    {
        return $this->id;
    }
    public function getnombre()
    {
        return $this->nombre;
    }
    public function getdificultad()
    {
        return $this->dificultad;
    }
    public function getcapaciadadPublico()
    {
        return $this->capacidadPublico;
    }
    public function getclima()
    {
        return $this->clima;
    }

    // ==========================================
    //                 SETTERS                 
    // ==========================================

    public function setid(int $nuevoId)
    {
        $this->id = $nuevoId;
    }
    public function setnombre(string $nombre)
    {
        $this->nombre = $nombre;
    }
    public function setdificultad(int $nuevaDificultad)
    {
        $this->dificultad = $nuevaDificultad;
    }
    public function setcapaciadadPublico(int $nuevaCapacidadPublico)
    {
        $this->capacidadPublico = $nuevaCapacidadPublico;
    }
    public function setclima(string $nuevoClima)
    {
        if ($nuevoClima != "normal" || $nuevoClima != "lluvia" || $nuevoClima != "tormenta" || $nuevoClima != "niebla") return;
        $this->clima = $nuevoClima;
    }

    // ==========================================
    //                 METODOS                 
    // ==========================================

    /**
     * Este metodo calcula y retorna el modificador de clima segun el tipo de personaje
     * @param Personaje $personaje
     * @return int
     */
    public function calcularModificadorArena(Personaje $personaje): int
    {
        $climaActual = $this->getclima();
        $modificador = 0;

        // Verificar el tipo de personaje y determinar modificadores según el clima
        // CASO MAGO
        if ($personaje instanceof Mago) {
            if ($climaActual == "lluvia") {
                $modificador = 5;
            } elseif ($climaActual == "tormenta") {
                $modificador = 15;
            }
        // CASO ARQUERO
        } elseif ($personaje instanceof Arquero) {
            if ($climaActual == "lluvia") {
                $modificador = -10;
            } elseif ($climaActual == "tormenta") {
                $modificador = -5;
            } elseif ($climaActual == "niebla") {
                $modificador = -15;
            }
        // CASO GUERRERO
        } else {
            if ($climaActual == "tormenta") {
                $modificador = -5;
            } elseif ($climaActual == "niebla") {
                $modificador = 5;
            }
        }

        return $modificador;
    }
}

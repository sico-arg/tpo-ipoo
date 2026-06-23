<?php

// ==========================================
//                 INCLUSIONES                  
// ==========================================

require_once 'src/Personaje/Personaje.php';

/**
 * Clase Arquero
 * 
 */
class Arquero extends Personaje
{

    // ==========================================
    //                 ATRIBUTOS                  
    // ==========================================

    private int $precisionPersonaje;
    private int $velocidad;

    // ==========================================
    //                 CONSTRUCTOR               
    // ==========================================

    public function __construct(string $nombre, int $nivel, int $puntosVida, int $energia, int $precisionPersonaje, int $velocidad, ?Arma $arma = null, ?int $id = null, int $duelosGanados = 0, int $duelosPerdidos = 0)
    {
        parent::__construct($nombre, $nivel, $puntosVida, $energia, $arma, $id, $duelosGanados, $duelosPerdidos);
        $this->precisionPersonaje = $precisionPersonaje;
        $this->velocidad = $velocidad;
    }


    // ==========================================
    //                 GETTERS                 
    // ==========================================
    public function getPrecision()
    {
        return $this->precisionPersonaje;
    }
    public function getVelocidad()
    {
        return $this->velocidad;
    }

    // ==========================================
    //                 SETTERS                 
    // ==========================================

    private function setPrecision(int $nuevaPrecision)
    {
        $this->precisionPersonaje = $nuevaPrecision;
    }
    private function setVelocidad(int $nuevaVelocidad)
    {
        $this->velocidad = $nuevaVelocidad;
    }

    // ==========================================
    //                 MÉTODOS                  
    // ==========================================

    /**
     * Este metodo calcula el poder base del arquero
     * @return int
     */
    public function calcularPoderBase(): int
    {
        $nivelActual = $this->getNivel();
        $precisionActual = $this->getPrecision();
        $poderBase = $nivelActual * 12 + $precisionActual;
        return $poderBase;
    }

    /**
     * Este metodo calcula el poder especial del guerrero
     * @return int
     */
    public function calcularPoderEspecial(): int
    {
        $velocidadActual = $this->getVelocidad();
        $precisionActual = $this->getPrecision();
        $poderEspecial = $precisionActual * 2 + $velocidadActual;
        return $poderEspecial;
    }
}

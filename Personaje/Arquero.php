<?php

// ==========================================
//                 INCLUSIONES                  
// ==========================================

include 'Personaje.php';

/**
 * Clase Arquero
 * 
 */
class Arquero extends Personaje
{

    // ==========================================
    //                 ATRIBUTOS                  
    // ==========================================

    private int $precision;
    private int $velocidad;

    // ==========================================
    //                 CONSTRUCTOR               
    // ==========================================

    public function __construct(int $id, string $nombre, int $nivel, int $puntosVida, int $energia, int $duelosGanados, int $duelosPerdidos, bool $estado, Arma $arma, int $precision, int $velocidad)
    {
        parent::__construct($id, $nombre, $nivel, $puntosVida, $energia, $duelosGanados, $duelosPerdidos, $estado, $arma);
        $this->precision = $precision;
        $this->velocidad = $velocidad;
    }


    // ==========================================
    //                 GETTERS                 
    // ==========================================
    public function getPrecision()
    {
        return $this->precision;
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
        $this->precision = $nuevaPrecision;
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

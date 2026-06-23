<?php

// ==========================================
//                 INCLUSIONES                  
// ==========================================

require_once 'src/Personaje/Personaje.php';

/**
 * Clase Guerrero
 * 
 */
class Guerrero extends Personaje
{

    // ==========================================
    //                 ATRIBUTOS                  
    // ==========================================

    private int $fuerza;
    private int $armadura;

    // ==========================================
    //                 CONSTRUCTOR               
    // ==========================================

    public function __construct(string $nombre, int $nivel, int $puntosVida, int $energia, int $fuerza, int $armadura, ?Arma $arma = null, ?int $id = null, int $duelosGanados = 0, int $duelosPerdidos = 0)
    {
        parent::__construct($nombre, $nivel, $puntosVida, $energia, $arma, $id, $duelosGanados, $duelosPerdidos);
        $this->fuerza = $fuerza;
        $this->armadura = $armadura;
    }


    // ==========================================
    //                 GETTERS                 
    // ==========================================
    public function getFuerza()
    {
        return $this->fuerza;
    }
    public function getArmadura()
    {
        return $this->armadura;
    }

    // ==========================================
    //                 SETTERS                 
    // ==========================================

    private function setFuerza(int $nuevaFuerza)
    {
        $this->fuerza = $nuevaFuerza;
    }
    private function setArmadura(int $nuevaArmadura)
    {
        $this->armadura = $nuevaArmadura;
    }

    // ==========================================
    //                 MÉTODOS                  
    // ==========================================

    /**
     * Este metodo calcula el poder base del guerrero
     * @return int
     */
    public function calcularPoderBase(): int
    {
        $nivelActual = $this->getNivel();
        $poderBase = $nivelActual * 15;
        return $poderBase;
    }

    /**
     * Este metodo calcula el poder especial del guerrero
     * @return int
     */
    public function calcularPoderEspecial(): int
    {
        $fuerzaActual = $this->getFuerza();
        $armaduraActual = $this->getArmadura();
        $poderEspecial = $fuerzaActual * 2 + $armaduraActual;
        return $poderEspecial;
    }
}

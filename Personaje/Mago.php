<?php

// ==========================================
//                 INCLUSIONES                  
// ==========================================

include_once __DIR__ . '/Personaje.php';

/**
 * Clase Mago
 * 
 */
class Mago extends Personaje
{

    // ==========================================
    //                 ATRIBUTOS                  
    // ==========================================

    private int $mana;
    private int $inteligencia;

    // ==========================================
    //                 CONSTRUCTOR               
    // ==========================================

    public function __construct(int $id, string $nombre, int $nivel, int $puntosVida, int $energia, int $duelosGanados, int $duelosPerdidos, string $estado, ?Arma $arma, int $mana, int $inteligencia)
    {
        parent::__construct($id, $nombre, $nivel, $puntosVida, $energia, $duelosGanados, $duelosPerdidos, $estado, $arma);
        $this->mana = $mana;
        $this->inteligencia = $inteligencia;
    }


    // ==========================================
    //                 GETTERS                 
    // ==========================================
    public function getMana()
    {
        return $this->mana;
    }
    public function getInteligencia()
    {
        return $this->inteligencia;
    }

    // ==========================================
    //                 SETTERS                 
    // ==========================================

    private function setMana(int $nuevoMana)
    {
        $this->mana = $nuevoMana;
    }
    private function setInteligencia(int $nuevaInteligencia)
    {
        $this->inteligencia = $nuevaInteligencia;
    }

    // ==========================================
    //                 MÉTODOS                  
    // ==========================================

    /**
     * Este metodo calcula el poder base del mago
     * @return int
     */
    public function calcularPoderBase(): int
    {
        $nivelActual = $this->getNivel();
        $manaActual = $this->getMana();
        $poderBase = $nivelActual * 10 + $manaActual;
        return $poderBase;
    }

    /**
     * Este metodo calcula el poder especial del mago
     * @return int
     */
    public function calcularPoderEspecial(): int
    {
        $inteligenciaActual = $this->getInteligencia();
        $manaActual = $this->getMana();
        $poderEspecial = $inteligenciaActual + $manaActual * 3;
        return $poderEspecial;
    }
}

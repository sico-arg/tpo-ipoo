<?php

// ==========================================
//                 INCLUSIONES                  
// ==========================================

require_once 'src/Personaje/Personaje.php';

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

    public function __construct(string $nombre, int $nivel, int $puntosVida, int $energia, int $mana, int $inteligencia, ?Arma $arma = null, ?int $id = null, int $duelosGanados = 0, int $duelosPerdidos = 0)
    {
        parent::__construct($nombre, $nivel, $puntosVida, $energia, $arma, $id, $duelosGanados, $duelosPerdidos);
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
        $poderEspecial = $manaActual + ($inteligenciaActual * 3);
        return $poderEspecial;
    }
}

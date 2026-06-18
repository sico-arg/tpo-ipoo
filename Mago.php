<?php

class Mago extends Personaje{
    private $mana;
    private $intelgiencia;

    public function __construct($ElMana, $LaIntelgiencia,$id, $nombre, $nivel, $puntosVida, $energia, $duelosGanados, $duelosPerdidos, $estado, $arma, $disponilble, $lesionado, $retirado){
        $this->mana = $ElMana;
        $this->intelgiencia = $LaIntelgiencia;
        parent :: __construct($id, $nombre, $nivel, $puntosVida, $energia, $duelosGanados, $duelosPerdidos, $estado, $arma, $disponilble, $lesionado, $retirado)

    }

    public function getMana(){
        return $this->mana;
    }
    public function getIntelgiencia(){
        return $this->intelgiencia;
    }

    private function setMana($nuevoMana){
        $this->mana = $nuevoMana;
    }
    private function setIntelgiencia($nuevoIntelgiencia){
        $this->intelgiencia = $nuevoIntelgiencia;
    }
    public function calcularPoderBase(){
        $nivel = parent::getNivel();
        $poderBase = $nivel *10 + $this->mana;
        return $poderBase  
    }
    public function calcularPoderEspecial(){
        $poderEspecial = $this->mana + $this->inteligencia *3
        return $poderEspecial
    }
   
}
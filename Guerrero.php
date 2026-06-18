<?php

class Guerrero extends Personaje{
    private $fuerza;
    private $armadura;

    public function __construct($laFuerza, $laArmadura,$id, $nombre, $nivel, $puntosVida, $energia, $duelosGanados, $duelosPerdidos, $estado, $arma, $disponilble, $lesionado, $retirado){
        $this->fuerza = $laFuerza;
        $this->armadura = $laArmadura;
        parent :: __construct($id, $nombre, $nivel, $puntosVida, $energia, $duelosGanados, $duelosPerdidos, $estado, $arma, $disponilble, $lesionado, $retirado)

    }
    public function getFuerza(){
        return $this->fuerza;
    }
    public function getArmadura(){
        return $this->armadura;
    }

    private function setFuerza($nuevoFuerza){
        $this->fuerza = $nuevoFuerza;
    }
    private function setArmadura($nuevoArmadura){
        $this->armadura = $nuevoArmadura;
    }
    public function calcularPoderBase(){
        $nivel = parent::getNivel();
        $poderBase = $nivel * 15
        return $poderBase  
    }
    public function calcularPoderEspecial(){
        $poderEspecial = $this->fuerza *2 + $this->armadura;
        return $poderEspecial
    }
}
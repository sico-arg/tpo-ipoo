<?php

class Arquero extends Personaje{
    private $precision;
    private $velocidad;

    public function __construct($LaPrecision, $LaVelocidad,$id, $nombre, $nivel, $puntosVida, $energia, $duelosGanados, $duelosPerdidos, $estado, $arma, $disponilble, $lesionado, $retirado){
        $this->precision = $LaPrecision;
        $this->velocidad = $LaVelocidad;
        parent :: __construct($id, $nombre, $nivel, $puntosVida, $energia, $duelosGanados, $duelosPerdidos, $estado, $arma, $disponilble, $lesionado, $retirado)

    }

    public function getPrecision(){
        return $this->precision;
    }
    public function getVelocidad(){
        return $this->velocidad;
    }

    private function setPrecision($nuevoPrecision){
        $this->precision = $nuevoPrecision;
    }
    private function setVelocidad($nuevoVelocidad){
        $this->velocidad = $nuevoVelocidad;
    }
    public function calcularPoderBase(){
        $nivel = parent::getNivel();
        $poderBase = $nivel * 12 + $this->precision;
        return $poderBase  
    }
    public function calcularPoderEspecial(){
        $poderEspecial = $this->precision *2 + $this->velocidad;
        return $poderEspecial
    }
   
}
<?php

class arena {
    private string $id;
    private string $nombre;
    private string $dificultad;
    private float $capaciadadPublico;
    private string $clima;

    public function __construct(string $id,string $nombre,string $dificultad,float $capaciadadPublico,string $clima) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->dificultad = $dificultad;
        $this->capaciadadPublico = $capaciadadPublico;
        $this->clima = $clima;

    }
    public function getid(){
        return $this->id;
    }
    public function getnombre(){
        return $this->nombre;
    }
    public function getdificultad(){
        return $this->dificultad;
    }
    public function getcapaciadadPublico(){
        return $this->capaciadadPublico;
    }
    public function getclima(){
        return $this->clima;
    }
    public function setid(string $id){
        $this->id=$id;
    }
    public function setnombre(string $nombre){
        $this->nombre = $nombre;
    }
    public function setdificultad(string $dificultad){
        $this->dificultad = $dificultad;
    } 
    public function setcapaciadadPublico(float $capaciadadPublico){
        $this->capaciadadPublico = $capaciadadPublico;
    }
    public function setclima(string $clima){
        $this->clima = $clima;

    }

}
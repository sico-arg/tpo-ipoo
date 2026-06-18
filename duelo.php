<?php
include 'personaje.php';
include 'arena.php';

public class duelo{
private int id;
private personaje $personaje1;
private personaje $personaje2;
private arena $arena;
private string $fecha;
private string $estado;
private bool $ganador;

public function(int $id,personaje $personaje1,personaje $personaje2,arena $arena,int $fecha,string $estado,bool $ganador){
    $this->id= $id;
    $this->personaje1 =$personaje1;
    $this->personaje2 =$personaje2;
    $this->arena =$arena;
    $this->fecha =$fecha;
    $this->estado =$estado;
    $this->ganador =$ganador;
}

public function getid(){
    return $this->id;
}
public function getpersonaje1(){
    return $this->personaje1;
}
public function getpersonaje2(){
    return $this->personaje2;
}
public function getarena(){
    return $this->arena;
}
public function getfecha(){
    return $this->fecha;
}
public function getestado(){
    return $this->estado;
}
public function getganador(){
    return $this->ganador;
}
public function setid(int $id){
    $this->id=$id;
}
public function setpersonaje1(personaje $personaje1){
    $this->personaje1 =$personaje1;
}
public function setpersonaje2(personaje $personaje2){
    $this->personaje2 =$personaje2;
}
public function setfecha(int $fecha){
    $this->fecha =$fecha;
}
public function setestado(string $estado){
    $this->estado =$estado;
}
public function setganador(bool $ganador){
    $this->ganador =$ganador;
}
}
 

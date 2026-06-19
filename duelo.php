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

public function puedeRealizarse(){
     $valido =true;


    //ambos personajes no pueden ser los mismos
    if($this->getpersonaje1()->getid() === $this->getpersonaje2()->getid()){
        $valido =false;
    }
    
    //uno de los personajes no debe estar lesionado 
    if($this->getpersonaje1()->getestado()==='lesionado' || $this->getpersonaje2()->getestado() ==='lesionado'){
        $valido = false;
    }
    //uno de los personajes no deben estar retirado
    if($this->getpersonaje1()->getestado()==='retirado' || $this->getpersonaje2()->getestado() ==='retirado'){
        $valido = false;
    }
    return $valido;
    }
    public function realizarDuelo(){
        $ganador =false; 
        $dueloRealizado =false;

        if($this->puedeRealizarse()){
            $poder1 =$this->getpersonaje1()->calcularPoderBase() + $this->getpersonaje1()->calcularPoderEspecial()+ $this->getpersonaje1()->getdanioBase() + //falta un metodo de arena
            $poder2 =$this->getpersonaje2()->calcularPoderBase() + $this->getpersonaje2()->calcularPoderEspecial()+ $this->getpersonaje2()->getdanioBase();
                
            $ganador =true
            if($poder1>$poder2){
                $this->getganador() = $this->personaje1;
                $perdedor = $this->personaje2;
                $poderGanador =$poder1;
                $poderPerdedor= $poder2;
            }else if($poder2>$poder1){
                $this->getganador() = $this->personaje2;
                $perdedor = $this->personaje1;
                $poderGanador =$poder2;
                $poderPerdedor= $poder1;}else{

                    $ganador = false; //empate
                    }
        if($ganador) {
            $this->personaje1->setNivel($this->ganador->getNivel() + 1);
            $this->personaje1->setEnergia($this->ganador->getEnergia() + 5);
            $this->personaje1->incrementarDuelosGanados();
             
        }else{
            $this->personaje2->setNivel($this->ganador->getNivel() + 1);
            $this->personaje2->setEnergia($this->ganador->getEnergia() + 5);
            $this->personaje2->incrementarDuelosGanados();
        }
        $dañoRecibido = $poderGanador -$poderPerdedor;
        $perdedor = $this-> //na no se puede completar este metodo sin otros metodos de arena saludos
            
        }
    }
    public function obtenerGanador(){
       $personajeGanador = null;
       if($this->realizarDuelo()){
        if($this->getestado() === 'realizado' && )
        $personajeGanador = $this->getganador();

       }
       return $personajeGanador;
    }
}
}
 

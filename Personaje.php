<?php

include 'Arma.php';

/**Clase Personaje

*/
class Personaje
{
    //Atributos
    private int $id;
    private string $nombre;
    private int $nivel;
    private int $puntosVida;
    private int $energia;
    private int $duelosGanados;
    private int $duelosPerdidos;
    private bool $estado;
    /* @var Arma*/
    private Arma $arma;
    private bool $disponilble;
    private bool $lesionado;
    private bool $retirado;

    //constructor

    public function __construct($id, $nombre, $nivel, $puntosVida, $energia, $duelosGanados, $duelosPerdidos, $estado, $arma, $disponilble, $lesionado, $retirado){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->nivel = $nivel;
        $this->puntosVida = $puntosVida;
        $this->energia = $energia;
        $this->duelosGanados = $duelosGanados;
        $this->duelosPerdidos = $duelosPerdidos;
        $this->estado = $estado;
        $this->arma = $arma;
        $this->disponilble = $disponilble;
        $this->lesionado = $lesionado;
        $this->retirado = $retirado;

    }
    //----- Getters ------
    public function getId(){
        return $this->id;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function getNivel(){
        return $this->nivel;
    }
    public function getPuntosVida(){
        return $this->puntosVida;
    }
    public function getEnergia(){
        return $this->energia;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function getDuelosGanados(){
        return $this->duelosGanados;
    }
    public function getDuelosPerdidos(){
        return $this->duelosPerdidos;
    }
    public function getEstado(){
        return $this->estado;
    }
    public function getArma(){
        return $this->arma;
    }
    public function getDisponilble(){
        return $this->disponilble;
    }
    public function getLesionado(){
        return $this->lesionado;
    }
    public function getRetirado(){
        return $this->retirado;
    }

    //----- Setters ------
    private function setNombre($nuevoNombre){
        $this->nombre = $nuevoNombre;
    }
    private function setNivel($nuevoNivel){
        $this->nivel = $nuevoNivel;
    }
    private function setPuntosVida($nuevoPuntosVida){
        $this->puntosVida = $nuevoPuntosVida;
    }
    private function setEnergia($nuevoEnergia){
        $this->energia = $nuevoEnergia
    }
    //Este iria? como es duelo ganado corresponde mas a calculo segun los duelos que gano
    private function setDuelosGanados($nuevosDuelosGanados){
        $ths->duelosGanados = $nuevosDuelosGanados;
    }
    //Este lo mismo que el anterior
    private function setDuelosPerdidos($nuevosDuelosPerdidos){
        $this->duelosPerdidos = $nuevosDuelosPerdidos;
    }
    private function setEstado($nuevoEstado){
        $this->estado = $nuevoEstado;
    }
    private function setArma($nuevaArma){
        $this->arma = $nuevaArma;
    }
    private function setDisponilble($nuevoDisponilble){
        $this->disponilble = $nuevoDisponilble;
    }
    private function setLesionado($nuevoLesionado){
        $this->lesionado = $nuevoLesionado;
    }
    private function setRetirado($nuevoRetirado){
        $this->retirado = $nuevoRetirado;
    }

    //----- METODOS -----
    public function recibirDanio($cantidad){
        if($this->puntosVida <= 0){
            $this->puntosVida = 0;
        } else {
            $this->puntosVida = $this->puntosVida - $cantidad;
        }
    }
    public function recuperarVida($cantidad){
        if($this->puntosVida = 100){
            return;
        } else {
            $this->puntosVida = $this->puntosVida + $cantidad;
        }
    }
    public function recuperarEnergia($cantidad){
        if($this->energia = 100){
            return;
        } else {
            $this->energia = $this->puntosVida + $cantidad;
        }
    }
    /*Por ahora ni idea que quiere que haga esta funcion, osea entiendo que usara los atributos
    disponible, retirado y lesionado
    */
    public function puedeDuelar(){
        $valido =false;
        if(!$this->$retirado && !$this->$lesionado && $this->disponible){
            $valido =true;
        }  //correjir nombre de variable por las dudas
        return $valido;

    }
    //Imagino que dependera de varios factores como Arma, energia y nivel
    public function calcularPoderTotal(){
        return calcularPoderBase()+ calcularPoderEspecial;
    }

    abstract function calcularPoderBase()
    abstract function calcularPoderEspecial()
    
    
    

}


?>
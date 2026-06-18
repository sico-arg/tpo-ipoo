<?php
include 'personaje.php';
public class arma {
    private int $id;
    private string $nombre;
    private string $tipo;
    private int $danioBase;
    private int $nivelMinimo;
    private string $estado;

    public function __construct(int $id,string $nombre,string $tipo,int $danioBase,int $nivelMinimo,string $estado){
        $this->id=$id;
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->danioBase = $danioBase;
        $this->nivelMinimo = $nivelMinimo;
        $this->estado = $estado;
    }
    public function getid(){
        return $this->id;
    }
    public function getnombre(){
        return $this->nombre;
    }
    public function gettipo(){
        return $this->tipo;
    }
    public function getdanioBase(){
        return $this->danioBase;
    }
    public function getnivelMinimo(){
        return $this->nivelMinimo;
    }
    public function getestado(){
        return $this->estado;
    }
    public function setid(int $id){
        $this->id=$id;
    }
    public function setnombre(string $nombre){
        $this->nombre = $nombre;
    }
    public function settipo(string $tipo){
        $this->tipo = $tipo;
    }
    public function setdaniobase(int $danioBase){
        $this->danioBase = $danioBase;
    }
    public function setnivelMinimo(int $nivelMinimo) {
        $this->nivelMinimo = $nivelMinimo;
    }
    public function setestado(string $estad){
        $this->estado = $estado;
    }

    public function calculardanio(){
        $dañoCalculado =0;
        $estadoActual =$this->getestado();

        if($estadoActual =='disponible') {
            $dañoCalculado = $this->getdanioBase();
        }else {
            $dañoCalculado =0; //en caso que este rota
        }

        return $dañoCalculado;
    }
    public function puedeSerEquipado(personaje $personaje) {
        $equipado =false;

         if($personaje->getnivelMinimo()<=$this->getnivelMinimo()) {
            if($this->getestado() ='equipado'){
                $equipado =true;
            }else{
                //en caso que el arma este rota pero el personaje cumpla el nivel minimo.
                $equipado = false;

            }
            return $equipado;
         }


    }
}
?>

    
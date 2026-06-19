<?php

// ==========================================
//                 INCLUSIONES                 
// ==========================================

include '../Arma.php';

/**
 * Clase abstracta personaje
 * 
 */
abstract class Personaje
{

    // ==========================================
    //                 ATRIBUTOS                 
    // ==========================================

    protected int $id;
    protected string $nombre;
    protected int $nivel;
    protected int $puntosVida;
    protected int $energia;
    protected int $duelosGanados;
    protected int $duelosPerdidos;
    protected string $estado;
    protected Arma $arma;

    // ==========================================
    //                 CONSTRUCTOR                 
    // ==========================================

    public function __construct(int $id, string $nombre, int $nivel, int $puntosVida, int $energia, int $duelosGanados, int $duelosPerdidos, string $estado, Arma $arma)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->nivel = $nivel;
        $this->puntosVida = $puntosVida;
        $this->energia = $energia;
        $this->duelosGanados = $duelosGanados;
        $this->duelosPerdidos = $duelosPerdidos;
        $this->estado = $estado;
        $this->arma = $arma;
    }

    // ==========================================
    //                 GETTERS                  
    // ==========================================

    public function getId()
    {
        return $this->id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getNivel()
    {
        return $this->nivel;
    }
    public function getPuntosVida()
    {
        return $this->puntosVida;
    }
    public function getEnergia()
    {
        return $this->energia;
    }
    public function getDuelosGanados()
    {
        return $this->duelosGanados;
    }
    public function getDuelosPerdidos()
    {
        return $this->duelosPerdidos;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function getArma()
    {
        return $this->arma;
    }



    // ==========================================
    //                 SETTERS                 
    // ==========================================

    private function setNombre(string $nuevoNombre)
    {
        $this->nombre = $nuevoNombre;
    }
    private function setNivel(int $nuevoNivel)
    {
        $this->nivel = $nuevoNivel;
    }
    private function setPuntosVida(int $nuevoPuntosVida)
    {
        $this->puntosVida = $nuevoPuntosVida;
    }
    private function setEnergia(int $nuevaEnergia)
    {
        $this->energia = $nuevaEnergia;
    }
    private function setDuelosGanados(int $nuevosDuelosGanados)
    {
        $this->duelosGanados = $nuevosDuelosGanados;
    }
    private function setDuelosPerdidos(int $nuevosDuelosPerdidos)
    {
        $this->duelosPerdidos = $nuevosDuelosPerdidos;
    }
    private function setEstado(bool $nuevoEstado)
    {
        $this->estado = $nuevoEstado;
    }
    private function setArma(Arma $nuevaArma)
    {
        $this->arma = $nuevaArma;
    }

    // ==========================================
    //                 MÉTODOS                  
    // ==========================================

    /**
     * Este metodo recibe cantidad por parametro y aplica el daño al personaje
     * @param int $cantidad
     * @return void
     */
    public function recibirDanio(int $cantidad): void
    {
        $puntosVida = $this->getPuntosVida();
        $nuevosPuntosVida = $puntosVida - $cantidad;
        if ($nuevosPuntosVida < 0) {
            $nuevosPuntosVida = 0;
        }
        $this->setPuntosVida($nuevosPuntosVida);
    }

    /**
     * Este metodo recibe cantidad por parametro y recupera la vida al personaje
     * @param int $cantidad
     * @return void
     */
    public function recuperarVida(int $cantidad): void
    {
        $puntosVida = $this->getPuntosVida();
        $nuevosPuntosVida = $puntosVida + $cantidad;
        if ($nuevosPuntosVida > 100) {
            $nuevosPuntosVida = 100;
        }
        $this->setPuntosVida($nuevosPuntosVida);
    }

    /**
     * Este metodo recibe cantidad por parametro y recupera la energia del personaje
     * @param int $cantidad
     * @return void
     */
    public function recuperarEnergia(int $cantidad): void
    {
        $energiaActual = $this->getEnergia();
        $nuevaEnergia = $energiaActual + $cantidad;
        if ($nuevaEnergia > 100) {
            $nuevaEnergia = 100;
        }
        $this->setEnergia($nuevaEnergia);
    }

    /**
     * Este metodo verifica si el personaje puede duelear
     * @return bool
     */
    public function puedeDuelar(): bool
    {
        $valido = false;
        $estadoPersonaje = $this->getEstado();
        if ($estadoPersonaje != "lesionado" && $estadoPersonaje != "retirado" && $estadoPersonaje == "disponible") {
            $valido = true;
        }
        return $valido;
    }

    /**
     * Este metodo calcula el poder total sumando poder base y poder especial
     * @return int
     */
    public function calcularPoderTotal(): int
    {
        return $this->calcularPoderBase() + $this->calcularPoderEspecial();
    }

    /**
     * Este metodo abstracto calcula el poder base del personaje
     * @return int
     */
    abstract public function calcularPoderBase(): int;

    /**
     * Este metodo abstracto calcula el poder especial del personaje
     * @return int
     */
    abstract public function calcularPoderEspecial(): int;
}

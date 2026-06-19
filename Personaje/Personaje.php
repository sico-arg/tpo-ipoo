<?php

// ==========================================
//                 INCLUSIONES                 
// ==========================================

include './Arma.php';
include './Arena.php';
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
    public function setNivel(int $nuevoNivel)
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
    public function setDuelosGanados(int $nuevosDuelosGanados)
    {
        $this->duelosGanados = $nuevosDuelosGanados;
    }
    public function setDuelosPerdidos(int $nuevosDuelosPerdidos)
    {
        $this->duelosPerdidos = $nuevosDuelosPerdidos;
    }
    private function setEstado(string $nuevoEstado)
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
        if ($nuevosPuntosVida <= 0) {
            $nuevosPuntosVida = 0;
            $this->setEstado("retirado");
        } elseif ($nuevosPuntosVida <= 30 && $nuevosPuntosVida > 0) {
            $this->setEstado("lesionado");
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
     * Este metodo recibe cantidad por parametro y resta la energia del personaje
     * @param int $cantidad
     * @return void
     */
    public function perderEnergia(int $cantidad): void
    {
        $energiaActual = $this->getEnergia();
        $nuevaEnergia = $energiaActual - $cantidad;
        if ($nuevaEnergia < 0) {
            $nuevaEnergia = 0;
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
    public function calcularPoderTotal(Arena $arena): int
    {
        $armaPersonaje = $this->getArma();
        // Ternaria en caso de que el valor de $armaPersonaje sea null porque el mismo no tenga arma
        $danioArma = $armaPersonaje ? $armaPersonaje->calcularDanio() : 0;
        $modificadorArena = $arena->calcularModificadorArena($this);

        return $this->calcularPoderBase() + $this->calcularPoderEspecial() + $danioArma + $modificadorArena;
    }

    /**
     * Este metodo aumenta el nivel del personaje en 1
     * @return void
     */
    public function aumentarNivel(): void
    {
        $nivelActual = $this->getNivel();
        $this->setNivel($nivelActual + 1);
    }

    /**
     * Este metodo disminuye el nivel del personaje en 1
     * @return void
     */
    public function disminuirNivel(): void
    {
        $nivelActual = $this->getNivel();
        $this->setNivel($nivelActual - 1);
    }

    /**
     * Este metodo incrementa en 1 la cantidad de duelos ganados
     * @return void
     */
    public function sumarDuelosGanados(): void
    {
        $duelosGanadosActual = $this->getDuelosGanados();
        $this->setDuelosGanados($duelosGanadosActual + 1);
    }

    /**
     * Este metodo incrementa en 1 la cantidad de duelos perdidos
     * @return void
     */
    public function sumarDuelosPerdidos(): void
    {
        $duelosPerdidosActual = $this->getDuelosPerdidos();
        $this->setDuelosPerdidos($duelosPerdidosActual + 1);
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

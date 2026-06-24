<?php

// ==========================================
//                 INCLUSIONES                 
// ==========================================

require_once 'src/Arma.php';
require_once 'src/Arena.php';

/**
 * Clase abstracta personaje
 * 
 */
abstract class Personaje
{

    // ==========================================
    //                 ATRIBUTOS                 
    // ==========================================

    protected ?int $id;
    protected string $nombre;
    protected int $nivel;
    protected int $puntosVida;
    protected int $energia;
    protected int $duelosGanados;
    protected int $duelosPerdidos;
    protected string $estado;
    protected ?Arma $arma = null;

    // ==========================================
    //                 CONSTRUCTOR                 
    // ==========================================

    public function __construct(string $nombre, int $nivel, int $puntosVida, int $energia, ?Arma $arma = null, ?int $id = null, int $duelosGanados = 0, int $duelosPerdidos = 0)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->nivel = $nivel;
        $this->puntosVida = $puntosVida;
        $this->energia = $energia;
        $this->duelosGanados = $duelosGanados;
        $this->duelosPerdidos = $duelosPerdidos;
        $this->estado = $this->calcularEstadoPersonaje();
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

    private function setId(?int $nuevoId)
    {
        $this->id = $nuevoId;
    }

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
    private function setEstado(string $nuevoEstado)
    {
        $this->estado = $nuevoEstado;
    }
    public function setArma(?Arma $nuevaArma)
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
            $this->setEstado("disponible");
        } elseif ($nuevosPuntosVida > 30) {
            $this->setEstado("disponible");
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

    private function calcularEstadoPersonaje()
    {
        $puntosVidaActual = $this->getPuntosVida();
        $estado = 'disponible';
        if ($puntosVidaActual <= 30 && $puntosVidaActual > 0) {
            $estado = 'lesionado';
        } elseif ($puntosVidaActual <= 0) {
            $estado = 'retirado';
        }
        return $estado;
    }

    public function tipoPersonaje()
    {
        $personaje = $this;
        $tipoPersonaje = 'guerrero';
        if ($personaje instanceof Mago) {
            $tipoPersonaje = 'mago';
        } elseif ($personaje instanceof Arquero) {
            $tipoPersonaje = 'arquero';
        }
        return $tipoPersonaje;
    }

    // ==========================================
    //                 MÉTODOS BD                
    // ==========================================

    /**
     * Este metodo guarda o actualiza el personaje en la base de datos.
     * Si el personaje tiene ID, actualiza sus datos. Si no tiene, lo inserta como nuevo.
     * @return void
     */
    public function guardar()
    {
        global $database;
        $id = $this->getId();
        $armaEquipada = $this->getArma();
        $armaEquipadaId = $armaEquipada ? $armaEquipada->getId() : null;

        $datos = [
            "nombre" => $this->getNombre(),
            "tipoPersonaje" => $this->tipoPersonaje(),
            "nivel" => $this->getNivel(),
            "puntosVida" => $this->getPuntosVida(),
            "energia" => $this->getEnergia(),
            "duelosGanados" => $this->getDuelosGanados(),
            "duelosPerdidos" => $this->getDuelosPerdidos(),
            "estado" => $this->getEstado(),
            "idArmaEquipada" => $armaEquipadaId
        ];

        if ($this instanceof Guerrero) {
            $datos["fuerza"] = $this->getFuerza();
            $datos["armadura"] = $this->getArmadura();
        } elseif ($this instanceof Mago) {
            $datos["mana"] = $this->getMana();
            $datos["inteligencia"] = $this->getInteligencia();
        } elseif ($this instanceof Arquero) {
            $datos["precisionPersonaje"] = $this->getPrecision();
            $datos["velocidad"] = $this->getVelocidad();
        }

        if ($id) {
            $database->update("personajes", $datos, ["id" => $id]);
        } else {
            $database->insert("personajes", $datos);
            $this->setId($database->id());
        }
    }

    /**
     * Este metodo elimina el personaje de la base de datos usando su ID
     * @return void
     */
    public function eliminar()
    {
        global $database;
        $id = $this->getId();
        if ($id) {
            $database->delete("personajes", ["id" => $id]);
            $this->setId(null);
        } else {
            echo "El personaje no existe en la base de datos";
        }
    }

    /**
     * Este metodo busca un personaje en la base de datos por su ID y retorna un objeto del tipo correspondiente
     * @param int $idBusqueda
     * @return Personaje|null
     */
    public static function busquedaPorId(int $idBusqueda): Personaje|null
    {
        global $database;
        $personajeRetorno = null;

        $listaId = $database->get("personajes", "*", ["id" => $idBusqueda]);

        if ($listaId) {
            //Logica de arma equipada
            $idArma = $listaId["idArmaEquipada"];
            $armaEquipadaBD = $idArma ? Arma::busquedaPorId((int)$idArma) : null;
            $tipoPersonajeBD = $listaId["tipoPersonaje"];
            switch ($tipoPersonajeBD) {
                case 'guerrero':
                    $personajeRetorno = new Guerrero(
                        $listaId["nombre"],
                        (int)$listaId["nivel"],
                        (int)$listaId["puntosVida"],
                        (int)$listaId["energia"],
                        (int)$listaId["fuerza"],
                        (int)$listaId["armadura"],
                        $armaEquipadaBD,
                        (int)$listaId["id"],
                        (int)$listaId["duelosGanados"],
                        (int)$listaId["duelosPerdidos"]
                    );
                    break;
                case 'mago':
                    $personajeRetorno = new Mago(
                        $listaId["nombre"],
                        (int)$listaId["nivel"],
                        (int)$listaId["puntosVida"],
                        (int)$listaId["energia"],
                        (int)$listaId["mana"],
                        (int)$listaId["inteligencia"],
                        $armaEquipadaBD,
                        (int)$listaId["id"],
                        (int)$listaId["duelosGanados"],
                        (int)$listaId["duelosPerdidos"]
                    );
                    break;
                case 'arquero':
                    $personajeRetorno = new Arquero(
                        $listaId["nombre"],
                        (int)$listaId["nivel"],
                        (int)$listaId["puntosVida"],
                        (int)$listaId["energia"],
                        (int)$listaId["precisionPersonaje"],
                        (int)$listaId["velocidad"],
                        $armaEquipadaBD,
                        (int)$listaId["id"],
                        (int)$listaId["duelosGanados"],
                        (int)$listaId["duelosPerdidos"]
                    );
                    break;
            }
        }
        return $personajeRetorno;
    }

    /**
     * Este metodo lista todos los personajes de la base de datos y los retorna como un arreglo de objetos
     * @return array
     */
    public static function listar(): array
    {
        global $database;
        $listaPersonajes = $database->select("personajes", "*");
        return self::instanciarDesdeBD($listaPersonajes);
    }

    /**
     * Este metodo lista todos los personajes disponibles para duelar
     * @return array
     */
    public static function listarDisponibles(): array
    {
        global $database;
        $listaPersonajes = $database->select("personajes", "*", ["estado" => "disponible"]);
        return self::instanciarDesdeBD($listaPersonajes);
    }

    /**
     * Este metodo lista todos los personajes lesionados
     * @return array
     */
    public static function listarLesionados(): array
    {
        global $database;
        $listaPersonajes = $database->select("personajes", "*", ["estado" => "lesionado"]);
        return self::instanciarDesdeBD($listaPersonajes);
    }

    /**
     * Este metodo lista todos los personajes retirados
     * @return array
     */
    public static function listarRetirados(): array
    {
        global $database;
        $listaPersonajes = $database->select("personajes", "*", ["estado" => "retirado"]);
        return self::instanciarDesdeBD($listaPersonajes);
    }

    /**
     * Este metodo retorna el ranking de personajes ordenado por duelos ganados
     * @return array
     */
    public static function obtenerRanking(): array
    {
        global $database;
        $listaPersonajes = $database->select("personajes", "*", ["ORDER" => ["duelosGanados" => "DESC"]]);
        return self::instanciarDesdeBD($listaPersonajes);
    }

    /**
     * Este metodo retorna el personaje con más victorias
     * @return Personaje|null
     */
    public static function obtenerPersonajeMasVictorias(): Personaje|null
    {
        global $database;
        $personaje = null;
        $resultado = $database->select("personajes", "*", ["ORDER" => ["duelosGanados" => "DESC"], "LIMIT" => 1]);
        if ($resultado) {
            $instancias = self::instanciarDesdeBD($resultado);
            $personaje = $instancias[0] ?? null;
        }
        return $personaje;
    }

    /**
     * Este metodo calcula y retorna el porcentaje de victorias de cada personaje
     * @return array Arreglo asociativo ['nombre' => porcentaje]
     */
    public static function obtenerPorcentajeVictorias(): array
    {
        global $database;
        $porcentajes = [];
        $personajes = $database->select("personajes", ["nombre", "duelosGanados", "duelosPerdidos"]);
        if ($personajes) {
            foreach ($personajes as $p) {
                $ganados = (int)$p["duelosGanados"];
                $perdidos = (int)$p["duelosPerdidos"];
                $total = $ganados + $perdidos;
                $porcentaje = $total > 0 ? ($ganados / $total) * 100 : 0;
                $porcentajes[$p["nombre"]] = round($porcentaje, 2);
            }
        }
        return $porcentajes;
    }

    /**
     * Metodo auxiliar para instanciar un arreglo de objetos Personaje a partir de resultados de BD
     * @param array|null $filasBD
     * @return array
     */
    private static function instanciarDesdeBD(?array $filasBD): array
    {
        $personajesObjetos = [];
        if ($filasBD) {
            foreach ($filasBD as $personaje) {
                $tipoPersonajeBD = $personaje["tipoPersonaje"];
                //Logica de arma equipada
                $idArma = $personaje["idArmaEquipada"];
                $armaEquipadaBD = $idArma ? Arma::busquedaPorId((int)$idArma) : null;
                switch ($tipoPersonajeBD) {
                    case 'guerrero':
                        $personajesObjetos[] = new Guerrero(
                            $personaje["nombre"],
                            (int)$personaje["nivel"],
                            (int)$personaje["puntosVida"],
                            (int)$personaje["energia"],
                            (int)$personaje["fuerza"],
                            (int)$personaje["armadura"],
                            $armaEquipadaBD,
                            (int)$personaje["id"],
                            (int)$personaje["duelosGanados"],
                            (int)$personaje["duelosPerdidos"]
                        );
                        break;
                    case 'mago':
                        $personajesObjetos[] = new Mago(
                            $personaje["nombre"],
                            (int)$personaje["nivel"],
                            (int)$personaje["puntosVida"],
                            (int)$personaje["energia"],
                            (int)$personaje["mana"],
                            (int)$personaje["inteligencia"],
                            $armaEquipadaBD,
                            (int)$personaje["id"],
                            (int)$personaje["duelosGanados"],
                            (int)$personaje["duelosPerdidos"]
                        );
                        break;
                    case 'arquero':
                        $personajesObjetos[] = new Arquero(
                            $personaje["nombre"],
                            (int)$personaje["nivel"],
                            (int)$personaje["puntosVida"],
                            (int)$personaje["energia"],
                            (int)$personaje["precisionPersonaje"],
                            (int)$personaje["velocidad"],
                            $armaEquipadaBD,
                            (int)$personaje["id"],
                            (int)$personaje["duelosGanados"],
                            (int)$personaje["duelosPerdidos"]
                        );
                        break;
                }
            }
        }
        return $personajesObjetos;
    }

    public function __toString(): string
    {
        $armaStr = $this->getArma() ? (string)$this->getArma() : 'null';
        return "id: " . ($this->getId() ?? 'null') .
            ", nombre: " . $this->getNombre() .
            ", nivel: " . $this->getNivel() .
            ", puntosVida: " . $this->getPuntosVida() .
            ", energia: " . $this->getEnergia() .
            ", duelosGanados: " . $this->getDuelosGanados() .
            ", duelosPerdidos: " . $this->getDuelosPerdidos() .
            ", estado: " . $this->getEstado() .
            ", arma: " . $armaStr;
    }

    // ==========================================
    //                 MÉTODOS ABSTRACTOS               
    // ==========================================

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

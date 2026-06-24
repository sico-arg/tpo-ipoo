<?php

// ==========================================
//                 INCLUSIONES                 
// ==========================================

/**
 * Clase Arma
 * 
 */
class Arma
{

    // ==========================================
    //                 ATRIBUTOS                  
    // ==========================================

    private ?int $id;
    private string $nombre;
    private string $tipo;
    private int $danioBase;
    private int $nivelMinimo;
    private string $estado;

    // ==========================================
    //                 CONSTRUCTOR                  
    // ==========================================

    public function __construct(string $nombre, string $tipo, int $danioBase, int $nivelMinimo, string $estado, ?int $id = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->danioBase = $danioBase;
        $this->nivelMinimo = $nivelMinimo;
        $this->estado = $estado;
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
    public function getTipo()
    {
        return $this->tipo;
    }
    public function getDanioBase()
    {
        return $this->danioBase;
    }
    public function getNivelMinimo()
    {
        return $this->nivelMinimo;
    }
    public function getEstado()
    {
        return $this->estado;
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
    private function setTipo(string $nuevoTipo)
    {
        $this->tipo = $nuevoTipo;
    }
    private function setDanioBase(int $nuevoDanioBase)
    {
        $this->danioBase = $nuevoDanioBase;
    }
    private function setNivelMinimo(int $nuevoNivelMinimo)
    {
        $this->nivelMinimo = $nuevoNivelMinimo;
    }
    // Público porque lo necesita Torneo::equiparArma()
    public function setEstado(string $nuevoEstado)
    {
        $this->estado = $nuevoEstado;
    }

    // ==========================================
    //                 MÉTODOS                  
    // ==========================================

    /**
     * Modifica los datos del arma.
     * @param string $nuevoNombre
     * @param int $nuevoDanioBase
     * @return void
     */
    public function modificar(string $nuevoNombre, int $nuevoDanioBase)
    {
        $this->setNombre($nuevoNombre);
        $this->setDanioBase($nuevoDanioBase);
    }

    /**
     * Este metodo devuelve el daño esperado del arma
     * 
     */
    public function calcularDanio(): int
    {
        $dañoCalculado = 0;
        $estadoActual = $this->getEstado();

        // ARREGLO: Se cambia '&&' por '||' porque una variable no puede tener 2 estados a la vez.
        if ($estadoActual == 'disponible' || $estadoActual == 'equipada') {
            $dañoCalculado = $this->getDanioBase();
        }

        return $dañoCalculado;
    }

    /**
     * Este metodo verifica si el arma puede ser equipada por el personaje
     * @param Personaje $personaje
     * @return bool
     */
    public function puedeSerEquipadoPor(Personaje $personaje): bool
    {
        $equipado = false;
        $nivelPersonaje = $personaje->getNivel();
        $nivelMinimoArma = $this->getNivelMinimo();
        $estadoArma = $this->getEstado();

        if ($estadoArma != 'rota' && $estadoArma != 'equipada') {
            if ($nivelPersonaje >= $nivelMinimoArma) {
                $equipado = true;
            }
        }
        return $equipado;
    }

    // ==========================================
    //                 MÉTODOS BD                
    // ==========================================

    /**
     * Este metodo guarda o actualiza el arma en la base de datos.
     * Si el arma tiene ID, actualiza sus datos. Si no tiene, la inserta como nueva.
     * @return void
     */
    public function guardar()
    {
        global $database;
        $id = $this->getId();

        $datos = [
            "nombre" => $this->getNombre(),
            "tipo" => $this->getTipo(),
            "danioBase" => $this->getDanioBase(),
            "nivelMinimo" => $this->getNivelMinimo(),
            "estado" => $this->getEstado(),
        ];
        if ($id) {
            $database->update("armas", $datos, ["id" => $id]);
        } else {
            $database->insert("armas", $datos);
            $this->setId($database->id());
        }
    }

    /**
     * Este metodo elimina el arma de la base de datos usando su ID
     * @return void
     */
    public function eliminar()
    {
        global $database;
        $id = $this->getId();
        if ($id) {
            $database->delete("armas", ["id" => $id]);
            $this->setId(null);
        } else {
            echo "El arma no existe en la base de datos";
        }
    }

    /**
     * Este metodo busca un arma en la base de datos por su ID y retorna un objeto Arma
     * @param int $idBusqueda
     * @return Arma|null
     */
    public static function busquedaPorId(int $idBusqueda): Arma|null
    {
        global $database;
        $armaRetorno = null;

        $listaId = $database->get("armas", "*", ["id" => $idBusqueda]);

        if ($listaId) {
            $armaRetorno = new Arma(
                $listaId["nombre"],
                $listaId["tipo"],
                (int)$listaId["danioBase"],
                (int)$listaId["nivelMinimo"],
                $listaId["estado"],
                (int)$listaId["id"],
            );
        }
        return $armaRetorno;
    }

    /**
     * Este metodo lista todas las armas de la base de datos y las retorna como un arreglo de objetos
     * @return array
     */
    public static function listar(): array
    {
        global $database;
        $listaArmas = $database->select("armas", "*");
        return self::instanciarDesdeBD($listaArmas);
    }

    /**
     * Este metodo lista todas las armas disponibles de la base de datos y las retorna como un arreglo de objetos
     * @return array
     */
    public static function listarDisponibles(): array
    {
        global $database;
        $listaArmas = $database->select("armas", "*", ["estado" => "disponible"]);
        return self::instanciarDesdeBD($listaArmas);
    }

    /**
     * Metodo auxiliar para instanciar un arreglo de objetos Arma a partir de resultados de BD
     * @param array|null $filasBD
     * @return array
     */
    private static function instanciarDesdeBD(?array $filasBD): array
    {
        $armasObjetos = [];
        if ($filasBD) {
            foreach ($filasBD as $arma) {
                $armasObjetos[] = new Arma(
                    $arma["nombre"],
                    $arma["tipo"],
                    (int)$arma["danioBase"],
                    (int)$arma["nivelMinimo"],
                    $arma["estado"],
                    (int)$arma["id"],
                );
            }
        }
        return $armasObjetos;
    }
}

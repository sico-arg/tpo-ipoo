<?php

// ==========================================
//                 INCLUSIONES                 
// ==========================================

class Arena
{

    // ==========================================
    //                 ATRIBUTOS                
    // ==========================================

    private ?int $id;
    private string $nombre;
    private int $dificultad;
    private int $capacidadPublico;
    private string $clima;

    // ==========================================
    //                 CONSTRUCTOR                
    // ==========================================

    public function __construct(string $nombre, int $dificultad, int $capacidadPublico, string $clima, ?int $id = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->dificultad = $dificultad;
        $this->capacidadPublico = $capacidadPublico;
        $this->clima = $clima;
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
    public function getDificultad()
    {
        return $this->dificultad;
    }
    public function getCapacidadPublico()
    {
        return $this->capacidadPublico;
    }
    public function getClima()
    {
        return $this->clima;
    }

    // ==========================================
    //                 SETTERS                 
    // ==========================================

    private function setId(?int $nuevoId)
    {
        $this->id = $nuevoId;
    }
    private function setNombre(string $nombre)
    {
        $this->nombre = $nombre;
    }
    private function setDificultad(int $nuevaDificultad)
    {
        $this->dificultad = $nuevaDificultad;
    }
    private function setCapacidadPublico(int $nuevaCapacidadPublico)
    {
        $this->capacidadPublico = $nuevaCapacidadPublico;
    }
    private function setClima(string $nuevoClima)
    {
        if ($nuevoClima != "normal" && $nuevoClima != "lluvia" && $nuevoClima != "tormenta" && $nuevoClima != "niebla") return;
        $this->clima = $nuevoClima;
    }

    // ==========================================
    //                 METODOS                 
    // ==========================================

    /**
     * Actualiza el clima y la capacidad de público de la arena.
     * @param int $nuevaCapacidadPublico
     * @param string $nuevoClima
     * @return void
     */
    public function actualizarClimaYCapacidad(int $nuevaCapacidadPublico, string $nuevoClima)
    {
        $this->setCapacidadPublico($nuevaCapacidadPublico);
        $this->setClima($nuevoClima);
    }

    /**
     * Este metodo calcula y retorna el modificador de clima segun el tipo de personaje
     * @param Personaje $personaje
     * @return int
     */
    public function calcularModificadorArena(Personaje $personaje): int
    {
        $climaActual = $this->getClima();
        $modificador = 0;

        // Verificar el tipo de personaje y determinar modificadores según el clima
        // CASO MAGO
        if ($personaje instanceof Mago) {
            if ($climaActual == "lluvia") {
                $modificador = 5;
            } elseif ($climaActual == "tormenta") {
                $modificador = 15;
            }
            // CASO ARQUERO
        } elseif ($personaje instanceof Arquero) {
            if ($climaActual == "lluvia") {
                $modificador = -10;
            } elseif ($climaActual == "tormenta") {
                $modificador = -5;
            } elseif ($climaActual == "niebla") {
                $modificador = -15;
            }
            // CASO GUERRERO
        } else {
            if ($climaActual == "tormenta") {
                $modificador = -5;
            } elseif ($climaActual == "niebla") {
                $modificador = 5;
            }
        }

        return $modificador;
    }

    // ==========================================
    //                 MÉTODOS BD                
    // ==========================================

    /**
     * Este metodo guarda o actualiza la arena en la base de datos.
     * Si la arena tiene ID, actualiza sus datos. Si no tiene, la inserta como nueva.
     * @return void
     */
    public function guardar()
    {
        global $database;
        $id = $this->getId();

        $datos = [
            "nombre" => $this->getNombre(),
            "dificultad" => $this->getDificultad(),
            "capacidadPublico" => $this->getCapacidadPublico(),
            "clima" => $this->getClima(),
        ];
        if ($id) {
            $database->update("arenas", $datos, ["id" => $id]);
        } else {
            $database->insert("arenas", $datos);
            $this->setId($database->id());
        }
    }

    /**
     * Este metodo elimina la arena de la base de datos usando su ID
     * @return void
     */
    public function eliminar()
    {
        global $database;
        $id = $this->getId();
        if ($id) {
            $database->delete("arenas", ["id" => $id]);
            $this->setId(null);
        } else {
            echo "La arena no existe en la base de datos";
        }
    }

    /**
     * Este metodo busca una arena en la base de datos por su ID y retorna un objeto Arena
     * @param int $idBusqueda
     * @return Arena|null
     */
    public static function busquedaPorId(int $idBusqueda): Arena|null
    {
        global $database;
        $arenaRetorno = null;

        $listaId = $database->get("arenas", "*", ["id" => $idBusqueda]);

        if ($listaId) {
            $arenaRetorno = new Arena(
                $listaId["nombre"],
                (int)$listaId["dificultad"],
                (int)$listaId["capacidadPublico"],
                $listaId["clima"],
                (int)$listaId["id"],
            );
        }
        return $arenaRetorno;
    }

    /**
     * Este metodo lista todas las arenas de la base de datos y las retorna como un arreglo de objetos
     * @return array
     */
    public static function listar(): array
    {
        global $database;
        $listaArenas = $database->select("arenas", "*");
        return self::instanciarDesdeBD($listaArenas);
    }

    /**
     * Este metodo obtiene la arena donde más duelos se realizaron
     * @return Arena|null
     */
    public static function obtenerArenaMasDuelos(): Arena|null
    {
        global $database;
        $resultado = $database->query(
            "SELECT idArena, COUNT(*) as cant FROM duelos GROUP BY idArena ORDER BY cant DESC LIMIT 1"
        )->fetchAll();

        if ($resultado && count($resultado) > 0) {
            $idArena = (int)$resultado[0]["idArena"];
            return self::busquedaPorId($idArena);
        }
        return null;
    }

    /**
     * Metodo auxiliar para instanciar un arreglo de objetos Arena a partir de resultados de BD
     * @param array|null $filasBD
     * @return array
     */
    private static function instanciarDesdeBD(?array $filasBD): array
    {
        $arenasObjetos = [];
        if ($filasBD) {
            foreach ($filasBD as $arena) {
                $arenasObjetos[] = new Arena(
                    $arena["nombre"],
                    (int)$arena["dificultad"],
                    (int)$arena["capacidadPublico"],
                    $arena["clima"],
                    (int)$arena["id"],
                );
            }
        }
        return $arenasObjetos;
    }
}

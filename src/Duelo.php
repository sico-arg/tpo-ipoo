<?php

// ==========================================
//                 INCLUSIONES                 
// ==========================================

require_once 'src/Personaje/Personaje.php';

class Duelo
{
    // ==========================================
    //                 ATRIBUTOS                 
    // ==========================================
    private ?int $id;
    private Personaje $personaje1;
    private Personaje $personaje2;
    private Arena $arena;
    private string $fecha;
    private string $estado;
    private ?Personaje $ganador;
    private ?int $poderPersonaje1 = null;
    private ?int $poderPersonaje2 = null;
    private ?int $danioAplicado = null;

    // ==========================================
    //                 CONSTRUCTOR                 
    // ==========================================

    public function __construct(Personaje $personaje1, Personaje $personaje2, Arena $arena, string $fecha, string $estado, ?Personaje $ganador = null, ?int $id = null)
    {
        $this->id = $id;
        $this->personaje1 = $personaje1;
        $this->personaje2 = $personaje2;
        $this->arena = $arena;
        $this->fecha = $fecha;
        $this->estado = $estado;
        $this->ganador = $ganador;
        $this->poderPersonaje1 = null;
        $this->poderPersonaje2 = null;
        $this->danioAplicado = null;
    }

    // ==========================================
    //                 GETTERS                 
    // ==========================================

    public function getId()
    {
        return $this->id;
    }
    public function getPersonaje1()
    {
        return $this->personaje1;
    }
    public function getPersonaje2()
    {
        return $this->personaje2;
    }
    public function getArena()
    {
        return $this->arena;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function getGanador()
    {
        return $this->ganador;
    }
    public function getPoderPersonaje1()
    {
        return $this->poderPersonaje1;
    }
    public function getPoderPersonaje2()
    {
        return $this->poderPersonaje2;
    }
    public function getDanioAplicado()
    {
        return $this->danioAplicado;
    }

    // ==========================================
    //                 SETTERS                 
    // ==========================================

    private function setId(?int $nuevoId)
    {
        $this->id = $nuevoId;
    }
    private function setPersonaje1(Personaje $nuevoPersonaje1)
    {
        $this->personaje1 = $nuevoPersonaje1;
    }
    private function setPersonaje2(Personaje $nuevoPersonaje2)
    {
        $this->personaje2 = $nuevoPersonaje2;
    }
    private function setArena(Arena $nuevaArena)
    {
        $this->arena = $nuevaArena;
    }
    private function setFecha(string $nuevaFecha)
    {
        $this->fecha = $nuevaFecha;
    }
    private function setEstado(string $nuevoEstado)
    {
        $this->estado = $nuevoEstado;
    }
    private function setGanador(?Personaje $nuevoGanador)
    {
        $this->ganador = $nuevoGanador;
    }
    private function setPoderPersonaje1(int $poder)
    {
        $this->poderPersonaje1 = $poder;
    }
    private function setPoderPersonaje2(int $poder)
    {
        $this->poderPersonaje2 = $poder;
    }
    private function setDanioAplicado(int $danio)
    {
        $this->danioAplicado = $danio;
    }

    // ==========================================
    //                 METODOS                
    // ==========================================

    /**
     * Este metodo verifica si el duelo puede llevarse a cabo basandose en el estado de los personajes
     * @return bool
     */
    public function puedeRealizarse(): bool
    {
        $personaje1Actual = $this->getPersonaje1();
        $personaje2Actual = $this->getPersonaje2();
        $personaje1Lesionado = $this->estaLesionado($personaje1Actual);
        $personaje2Lesionado = $this->estaLesionado($personaje2Actual);
        $personaje1Retirado = $this->estaRetirado($personaje1Actual);
        $personaje2Retirado = $this->estaRetirado($personaje2Actual);
        $puedeRealizarse = false;

        if ($personaje1Actual !== $personaje2Actual && !$personaje1Lesionado && !$personaje2Lesionado && !$personaje1Retirado && !$personaje2Retirado) $puedeRealizarse = true;
        return $puedeRealizarse;
    }


    /**
     * Este metodo verifica si un personaje esta en estado lesionado
     * @param Personaje $personaje
     * @return bool
     */
    public function estaLesionado(Personaje $personaje): bool
    {
        $lesionado = false;
        $estadoPersonaje = $personaje->getEstado();
        if ($estadoPersonaje == "lesionado") {
            $lesionado = true;
        }
        return $lesionado;
    }

    /**
     * Este metodo verifica si un personaje esta en estado retirado
     * @param Personaje $personaje
     * @return bool
     */
    public function estaRetirado(Personaje $personaje): bool
    {
        $retirado = false;
        $estadoPersonaje = $personaje->getEstado();
        if ($estadoPersonaje == "retirado") {
            $retirado = true;
        }
        return $retirado;
    }

    public function realizarDuelo()
    {
        $personaje1Actual = $this->getPersonaje1();
        $personaje2Actual = $this->getPersonaje2();
        $puedenRealizarDuelo = $this->puedeRealizarse();
        $ganadorDuelo = $this->obtenerGanador();
        $danioAplicar = $this->calcularDanio();

        if ($puedenRealizarDuelo) {
            if ($ganadorDuelo === $personaje1Actual) {
                //Acciones a personaje 1 en caso de que gane
                $personaje1Actual->aumentarNivel();
                $personaje1Actual->recuperarEnergia(5);
                $personaje1Actual->sumarDuelosGanados();

                //Acciones a personaje 2 en caso de que gane personaje 1
                $personaje2Actual->recibirDanio($danioAplicar);
                $personaje2Actual->sumarDuelosPerdidos();
                $personaje2Actual->perderEnergia(5);
            } elseif ($ganadorDuelo === $personaje2Actual) {
                //Acciones a personaje 2 en caso de que gane
                $personaje2Actual->aumentarNivel();
                $personaje2Actual->recuperarEnergia(5);
                $personaje2Actual->sumarDuelosGanados();

                //Acciones a personaje 1 en caso de que gane personaje 2
                $personaje1Actual->recibirDanio($danioAplicar);
                $personaje1Actual->sumarDuelosPerdidos();
                $personaje1Actual->perderEnergia(5);
            } else {
                // En caso de empate exacto, no se aplican consecuencias
            }
            //Actualizar datos de Poder y Danio al realizarze un duelo
            $this->setPoderPersonaje1($this->calcularPoderPersonaje1());
            $this->setPoderPersonaje2($this->calcularPoderPersonaje2());
            $this->setDanioAplicado($danioAplicar);

            $this->setEstado('realizado');
            $this->setGanador($ganadorDuelo);
            return true;
        }
        return false;
    }

    public function obtenerGanador()
    {
        $personaje1Actual = $this->getPersonaje1();
        $personaje2Actual = $this->getPersonaje2();
        $poderTotalPersonaje1 = $this->calcularPoderPersonaje1();
        $poderTotalPersonaje2 = $this->calcularPoderPersonaje2();
        $personajeGanador = null;
        if ($poderTotalPersonaje1 > $poderTotalPersonaje2) {
            $personajeGanador = $personaje1Actual;
        } elseif ($poderTotalPersonaje1 < $poderTotalPersonaje2) {
            $personajeGanador = $personaje2Actual;
        }
        return $personajeGanador;
    }

    private function calcularDanio()
    {
        $danio = 0;
        $poderPersonaje1 = $this->calcularPoderPersonaje1();
        $poderPersonaje2 = $this->calcularPoderPersonaje2();
        if ($poderPersonaje1 > $poderPersonaje2) {
            $danio = $poderPersonaje1 - $poderPersonaje2;
        } elseif ($poderPersonaje1 < $poderPersonaje2) {
            $danio = $poderPersonaje2 - $poderPersonaje1;
        }

        return $danio;
    }

    private function calcularPoderPersonaje1()
    {
        $personaje1Actual = $this->getPersonaje1();
        $arenaActual = $this->getArena();
        $poderPersonaje1 = $personaje1Actual->calcularPoderTotal($arenaActual);
        return $poderPersonaje1;
    }

    private function calcularPoderPersonaje2()
    {
        $personaje2Actual = $this->getPersonaje2();
        $arenaActual = $this->getArena();
        $poderPersonaje2 = $personaje2Actual->calcularPoderTotal($arenaActual);
        return $poderPersonaje2;
    }

    // ==========================================
    //                 MÉTODOS BD                
    // ==========================================

    /**
     * Este metodo guarda o actualiza el duelo en la base de datos.
     * Si el duelo tiene ID, actualiza sus datos. Si no tiene, lo inserta como nuevo.
     * @return void
     */
    public function guardar()
    {
        global $database;
        $id = $this->getId();

        $datos = [
            "idPersonaje1" => $this->getPersonaje1()->getId(),
            "idPersonaje2" => $this->getPersonaje2()->getId(),
            "idArena" => $this->getArena()->getId(),
            "fecha" => $this->getFecha(),
            "estado" => $this->getEstado(),
            "idGanador" => $this->getGanador() ? $this->getGanador()->getId() : null,
            "poderPersonaje1" => $this->calcularPoderPersonaje1(),
            "poderPersonaje2" => $this->calcularPoderPersonaje2(),
            "danioAplicado" => $this->calcularDanio()
        ];
        if ($id) {
            $database->update("duelos", $datos, ["id" => $id]);
        } else {
            $database->insert("duelos", $datos);
            $this->setId($database->id());
        }
    }

    /**
     * Este metodo elimina el duelo de la base de datos usando su ID
     * @return void
     */
    public function eliminar()
    {
        global $database;
        $id = $this->getId();
        if ($id) {
            $database->delete("duelos", ["id" => $id]);
            $this->setId(null);
        } else {
            echo "El duelo no existe en la base de datos";
        }
    }

    /**
     * Este metodo busca un duelo en la base de datos por su ID y retorna un objeto Duelo
     * @param int $idBusqueda
     * @return Duelo|null
     */
    public static function busquedaPorId(int $idBusqueda): Duelo|null
    {
        global $database;
        $dueloRetorno = null;

        $listaId = $database->get("duelos", "*", ["id" => $idBusqueda]);

        if ($listaId) {
            $dueloRetorno = new Duelo(
                Personaje::busquedaPorId((int)$listaId["idPersonaje1"]),
                Personaje::busquedaPorId((int)$listaId["idPersonaje2"]),
                Arena::busquedaPorId((int)$listaId["idArena"]),
                $listaId["fecha"],
                $listaId["estado"],
                $listaId["idGanador"] ? Personaje::busquedaPorId((int)$listaId["idGanador"]) : null,
                (int)$listaId["id"],
            );

            if ($listaId["danioAplicado"] !== null) {
                $dueloRetorno->setPoderPersonaje1((int)$listaId["poderPersonaje1"]);
                $dueloRetorno->setPoderPersonaje2((int)$listaId["poderPersonaje2"]);
                $dueloRetorno->setDanioAplicado((int)$listaId["danioAplicado"]);
            }
        }
        return $dueloRetorno;
    }

    /**
     * Este metodo lista todos los duelos de la base de datos y los retorna como un arreglo de objetos
     * @return array
     */
    public static function listar(): array
    {
        global $database;
        $listaDuelos = $database->select("duelos", "*");
        return self::instanciarDesdeBD($listaDuelos);
    }

    /**
     * Este metodo lista todos los duelos realizados
     * @return array
     */
    public static function listarRealizados(): array
    {
        global $database;
        $listaDuelos = $database->select("duelos", "*", ["estado" => "realizado"]);
        return self::instanciarDesdeBD($listaDuelos);
    }

    /**
     * Este metodo lista todos los duelos pendientes
     * @return array
     */
    public static function listarPendientes(): array
    {
        global $database;
        $listaDuelos = $database->select("duelos", "*", ["estado" => "pendiente"]);
        return self::instanciarDesdeBD($listaDuelos);
    }

    /**
     * Este metodo muestra el historial de duelos de un personaje
     * @param int $idPersonaje
     * @return array
     */
    public static function historialPorPersonaje(int $idPersonaje): array
    {
        global $database;
        $listaDuelos = $database->select("duelos", "*", [
            "OR" => [
                "idPersonaje1" => $idPersonaje,
                "idPersonaje2" => $idPersonaje
            ]
        ]);
        return self::instanciarDesdeBD($listaDuelos);
    }

    /**
     * Metodo auxiliar para instanciar un arreglo de objetos Duelo a partir de resultados de BD
     * @param array|null $filasBD
     * @return array
     */
    private static function instanciarDesdeBD(?array $filasBD): array
    {
        $duelosObjetos = [];
        if ($filasBD) {
            foreach ($filasBD as $dueloBD) {
                $nuevoDuelo = new Duelo(
                    Personaje::busquedaPorId((int)$dueloBD["idPersonaje1"]),
                    Personaje::busquedaPorId((int)$dueloBD["idPersonaje2"]),
                    Arena::busquedaPorId((int)$dueloBD["idArena"]),
                    $dueloBD["fecha"],
                    $dueloBD["estado"],
                    $dueloBD["idGanador"] ? Personaje::busquedaPorId((int)$dueloBD["idGanador"]) : null,
                    (int)$dueloBD["id"],
                );

                if ($dueloBD["danioAplicado"] !== null) {
                    $nuevoDuelo->setPoderPersonaje1((int)$dueloBD["poderPersonaje1"]);
                    $nuevoDuelo->setPoderPersonaje2((int)$dueloBD["poderPersonaje2"]);
                    $nuevoDuelo->setDanioAplicado((int)$dueloBD["danioAplicado"]);
                }

                $duelosObjetos[] = $nuevoDuelo;
            }
        }
        return $duelosObjetos;
    }
}

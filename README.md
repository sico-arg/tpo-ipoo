# TRABAJO PRÁCTICO FINAL OBLIGATORIO - 2026
## Materia: Introducción a la Programación Orientada a Objetos (IPOO)
## Carrera: Tecnicatura Superior en Desarrollo Web
## Facultad de Informática - Universidad Nacional del Comahue
## Proyecto: "Los Juegos del Hambre"

---

### DESCRIPCIÓN GENERAL
La organización internacional Arena Masters realiza torneos de duelos entre personajes de distintas clases y habilidades. Cada personaje puede equiparse con un arma, participar en duelos dentro de diferentes arenas y ganar experiencia a medida que obtiene victorias. La organización necesita desarrollar un sistema que permita administrar los personajes, las armas, las arenas y los duelos realizados durante un torneo.

El objetivo del trabajo práctico es diseñar e implementar un sistema orientado a objetos completo utilizando:
- Abstracción / Encapsulamiento / Ocultamiento
- Herencia / Polimorfismo
- Relaciones entre objetos
- Colecciones
- Persistencia mediante ORM
- Base de datos MariaDB/mySQL

---

### 1. CLASE PERSONAJE
Crear una clase abstracta denominada Personaje.

#### Atributos
Todo personaje posee:
- id
- nombre
- nivel
- puntosVida
- energia
- duelosGanados
- duelosPerdidos
- estado (Valores posibles: disponible, lesionado, retirado)

Además, cada personaje puede tener equipada un arma.

#### Métodos
La clase deberá implementar:
- Constructor
- Getters y Setters
- recibirDanio($cantidad)
- recuperarVida($cantidad)
- recuperarEnergia($cantidad)
- puedeDuelar()
- calcularPoderTotal()

La clase deberá definir como abstractos los métodos:
- calcularPoderBase()
- calcularPoderEspecial()

---

### 2. CLASES DERIVADAS
Crear al menos las siguientes clases derivadas:

#### GUERRERO
- Atributos: fuerza, armadura
- Poder base: nivel * 15
- Poder especial: fuerza * 2 + armadura

#### MAGO
- Atributos: mana, inteligencia
- Poder base: nivel * 10 + mana
- Poder especial: mana + inteligencia * 3

#### ARQUERO
- Atributos: precision, velocidad
- Poder base: nivel * 12 + precision
- Poder especial: precision * 2 + velocidad

---

### 3. CLASE ARMA
Representa un arma que puede ser utilizada por un personaje.

#### Atributos
- id
- nombre
- tipo
- danioBase
- nivelMinimo
- estado (Valores posibles: disponible, equipada, rota)

#### Métodos
- Constructor
- Getters y Setters
- calcularDanio()
- puedeSerEquipadaPor(Personaje $personaje)

#### Reglas
- Un arma rota no puede equiparse.
- Un personaje debe cumplir el nivel mínimo para equipar un arma.
- Un arma equipada no puede asignarse a otro personaje.

---

### 4. CLASE ARENA
Representa el lugar donde se desarrollan los duelos.

#### Atributos
- id
- nombre
- dificultad
- capacidadPublico
- clima (Climas posibles: normal, lluvia, tormenta, niebla)

#### Métodos
- Constructor
- Getters y Setters
- calcularModificadorArena(Personaje $personaje)

#### Modificadores de Clima por Tipo de Personaje:
- **Clima Normal:** No modifica nada.
- **Lluvia:**
  * Arquero: -10
  * Guerrero: 0
  * Mago: +5
- **Tormenta:**
  * Mago: +15
  * Arquero: -5
  * Guerrero: -5
- **Niebla:**
  * Arquero: -15
  * Guerrero: +5
  * Mago: 0

---

### 5. CLASE DUELO
Representa un enfrentamiento entre dos personajes.

#### Atributos
- id
- personaje1
- personaje2
- arena
- fecha
- estado (Valores posibles: pendiente, realizado, cancelado)
- ganador

#### Métodos
- Constructor
- puedeRealizarse()
- realizarDuelo()
- obtenerGanador()

#### Reglas
No podrá realizarse un duelo si:
- Ambos personajes son el mismo.
- Alguno de los personajes está lesionado.
- Alguno de los personajes está retirado.

El poder de cada personaje se calculará mediante:
Poder Base + Poder Especial + Daño del Arma + Modificador de la Arena

El personaje con mayor poder será declarado ganador.

#### Consecuencias del duelo
- **Ganador:**
  * Gana 1 nivel.
  * Recupera 5 puntos de energía.
  * Incrementa en 1 sus duelos ganados.
- **Perdedor:**
  * Recibe daño igual a: PoderGanador - PoderPerdedor
  * Incrementa en 1 sus duelos perdidos.
  * Pierde energía.

#### Estados post-duelo
- Si luego del daño los puntos de vida son menores o iguales a 0 el personaje pasa a estado: retirado.
- Si luego del daño los puntos de vida están entre 0 y 30 el personaje pasa a estado: lesionado.
- Los personajes lesionados no pueden participar en nuevos duelos hasta recuperarse.

---

### 6. CLASE TORNEO
Representa el torneo completo.

#### Colecciones (Debe administrar):
- personajes
- armas
- arenas
- duelos

#### Métodos mínimos
- agregarPersonaje()
- agregarArma()
- agregarArena()
- equiparArma()
- registrarDuelo()
- realizarDuelo()
- listarPersonajes()
- listarArmas()
- listarArenas()
- listarDuelos()
- rankingPersonajes()

---

### 7. PERSISTENCIA
La información deberá persistirse utilizando:
- MariaDB / MySQL
- ORM (Medoo o similar)

Se entrega un script de creación de tablas y otro de inserción de datos de prueba. Puede utilizarlos y modificarlos si lo cree necesario.

Como mínimo se deberá permitir (Operaciones CRUD):
- Alta
- Baja
- Modificación
- Búsqueda por ID
- Listado completo
Además deberán implementarse consultas utilizando JOIN de ser necesario.

---

### 8. CONSULTAS OBLIGATORIAS
El sistema deberá permitir:
1. Listar todos los personajes.
2. Listar personajes disponibles para duelar.
3. Listar personajes lesionados.
4. Listar personajes retirados.
5. Listar armas disponibles.
6. Mostrar el arma equipada por cada personaje.
7. Mostrar todos los duelos realizados.
8. Mostrar todos los duelos pendientes.
9. Mostrar el historial de duelos de un personaje.
10. Mostrar el ranking de personajes ordenado por cantidad de victorias.
11. Mostrar el personaje con mayor cantidad de victorias.
12. Mostrar el porcentaje de victorias de cada personaje.
13. Mostrar la arena donde más duelos se realizaron.

---

### 9. PROGRAMA PRINCIPAL
Deberá existir un menú que permita:
- Registrar personajes.
- Registrar armas.
- Registrar arenas.
- Equipar armas.
- Registrar duelos.
- Ejecutar duelos pendientes.
- Recuperar personajes lesionados.
- Consultar rankings.
- Consultar historial de personajes.

---

### DETALLES DE LA ENTREGA
- El trabajo puede realizarse en grupos de hasta 4 personas.
- Se deberá realizar una sola entrega por grupo, con una carátula en la que se indiquen los integrantes del mismo.
- Deberán conformar el grupo anotándose en la planilla compartida.
- Los alumnos deberán entregar:
  * Código fuente completo.
  * Base de datos MariaDB.
  * Script SQL de creación de tablas y datos de prueba (si es que modificó los entregados).
  * Diagrama UML.
  * Programa principal funcional.
- El programa funcionando se deberá defender en forma oral estando todos los participantes del grupo en la fecha a acordar con los docentes.
- La presentación de este trabajo es condición para la aprobación de la materia.
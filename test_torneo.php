<?php
// Test verification script for the Torneo class and related systems.

include_once 'src/Torneo.php';

echo "=== STARTING TORNEO VERIFICATION ===\n\n";

// 1. Create a Torneo instance
$torneo = new Torneo();
echo "1. Torneo created successfully.\n";

// 2. Create Weapons
$espada = new Arma('Espada de Hierro', 'espada', 20, 1, 'disponible');
$baculo = new Arma('Báculo Arcano', 'baculo', 25, 2, 'disponible');
$arco = new Arma('Arco Élfico', 'arco', 18, 1, 'disponible');

$torneo->agregarArma($espada);
$torneo->agregarArma($baculo);
$torneo->agregarArma($arco);
echo "2. Weapons created and added to Torneo.\n";

// 3. Create Arenas
$coliseo = new Arena('Coliseo Central', 3, 5000, 'normal');
$bosque = new Arena('Bosque Nublado', 4, 1200, 'niebla');

$torneo->agregarArena($coliseo);
$torneo->agregarArena($bosque);
echo "3. Arenas created and added to Torneo.\n";

// 4. Create Characters
// Constructor signature: int $id, string $nombre, int $nivel, int $puntosVida, int $energia, int $duelosGanados, int $duelosPerdidos, string $estado, ?Arma $arma, special attributes...
$thorgar = new Guerrero('Thorgar', 3, 100, 90, 18, 12, null, null);
$elandra = new Mago('Elandra', 4, 80, 100, 35, 20, null, null);
$lorian = new Arquero('Lorian', 2, 90, 95, 22, 18, null, null);

$torneo->agregarPersonaje($thorgar);
$torneo->agregarPersonaje($elandra);
$torneo->agregarPersonaje($lorian);
echo "4. Characters created and added to Torneo.\n";

// 5. Equip Weapons
echo "5. Equipping weapons:\n";
$eq1 = $torneo->equiparArma($thorgar, $espada);
$eq2 = $torneo->equiparArma($elandra, $baculo);
echo " - Thorgar equips Espada: " . ($eq1 ? "SUCCESS" : "FAILED") . "\n";
echo " - Elandra equips Báculo: " . ($eq2 ? "SUCCESS" : "FAILED") . "\n";

// Verify weapon attributes on characters
echo " - Thorgar weapon: " . ($thorgar->getArma() ? $thorgar->getArma()->getNombre() : 'None') . "\n";
echo " - Elandra weapon: " . ($elandra->getArma() ? $elandra->getArma()->getNombre() : 'None') . "\n";

// 6. Register and execute Duels
echo "\n6. Registering and executing duels:\n";
$duel1 = new Duelo($thorgar, $elandra, $coliseo, '2026-06-20', 'pendiente');
$torneo->registrarDuelo($duel1);

echo " - Duel 1 can be realized? " . ($duel1->puedeRealizarse() ? "YES" : "NO") . "\n";
echo " - Executing Duel 1...\n";
$success1 = $torneo->realizarDuelo($duel1);
echo " - Execution success? " . ($success1 ? "YES" : "NO") . "\n";
echo " - Poder Personaje 1: " . $duel1->getPoderPersonaje1() . "\n";
echo " - Poder Personaje 2: " . $duel1->getPoderPersonaje2() . "\n";
echo " - Danio Aplicado: " . $duel1->getDanioAplicado() . "\n";
echo " - Duel 1 status: " . $duel1->getEstado() . "\n";
echo " - Duel 1 winner: " . ($duel1->getGanador() ? $duel1->getGanador()->getNombre() : 'None/Tie') . "\n";

// Display stats after Duel 1
echo " - Thorgar HP: " . $thorgar->getPuntosVida() . ", Energy: " . $thorgar->getEnergia() . ", Status: " . $thorgar->getEstado() . ", Wins: " . $thorgar->getDuelosGanados() . ", Losses: " . $thorgar->getDuelosPerdidos() . "\n";
echo " - Elandra HP: " . $elandra->getPuntosVida() . ", Energy: " . $elandra->getEnergia() . ", Status: " . $elandra->getEstado() . ", Wins: " . $elandra->getDuelosGanados() . ", Losses: " . $elandra->getDuelosPerdidos() . "\n";

// 7. Rankings and lists
echo "\n7. Listing and rankings:\n";
echo " - Total Characters registered: " . count($torneo->listarPersonajes()) . "\n";
echo " - Total Weapons registered: " . count($torneo->listarArmas()) . "\n";
echo " - Total Arenas registered: " . count($torneo->listarArenas()) . "\n";
echo " - Total Duels registered: " . count($torneo->listarDuelos()) . "\n";

echo "\nRanking of Characters by Wins:\n";
$ranking = $torneo->rankingPersonajes();
foreach ($ranking as $index => $char) {
    echo " [" . ($index + 1) . "] " . $char->getNombre() . " (" . get_class($char) . ") - Wins: " . $char->getDuelosGanados() . "\n";
}

echo "\n=== VERIFICATION COMPLETED ===\n";

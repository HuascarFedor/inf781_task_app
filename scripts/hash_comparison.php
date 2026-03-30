<?php

/**
 * INF781 - Seguridad de Software
 * Demostración: Comparación de algoritmos de hashing
 * Ejecutar: php scripts/hash_comparison.php
 */

// ── Contraseñas de prueba ───────────────────────────────────────
$passwords = [
    'password123',          // contraseña débil común
    'Admin@2025!',          // contraseña moderada
    'T@skS3cur3_Lab!2025',  // contraseña fuerte
];

echo str_repeat('=', 70) . PHP_EOL;
echo ' INF781 — Comparación de Algoritmos de Hashing' . PHP_EOL;
echo str_repeat('=', 70) . PHP_EOL . PHP_EOL;

foreach ($passwords as $pwd) {
    echo "Contraseña: {$pwd}" . PHP_EOL;
    echo str_repeat('-', 70) . PHP_EOL;

    // ── MD5 (INSEGURO) ─────────────────────────────────────────
    $t = microtime(true);
    $hash = md5($pwd);
    $elapsed = (microtime(true) - $t) * 1000;
    printf("  MD5    : %s\n",  $hash);
    printf("  Tiempo : %.6f ms\n", $elapsed);
    printf("  Longitud: %d chars | INSEGURO: sin salt, colisiones conocidas\n", strlen($hash));
    echo PHP_EOL;

    // ── SHA-1 (INSEGURO) ───────────────────────────────────────
    $t = microtime(true);
    $hash = sha1($pwd);
    $elapsed = (microtime(true) - $t) * 1000;
    printf("  SHA-1  : %s\n",  $hash);
    printf("  Tiempo : %.6f ms\n", $elapsed);
    printf("  Longitud: %d chars | INSEGURO: SHAttered (2017), sin salt\n", strlen($hash));
    echo PHP_EOL;

    // ── SHA-256 (mejor, pero aún NO recomendado para contraseñas) ──
    $t = microtime(true);
    $hash = hash('sha256', $pwd);
    $elapsed = (microtime(true) - $t) * 1000;
    printf("  SHA-256: %s\n",  $hash);
    printf("  Tiempo : %.6f ms\n", $elapsed);
    printf("  Longitud: %d chars | Mejor, pero sigue siendo rápido = vulnerable\n", strlen($hash));
    echo PHP_EOL;

    // ── bcrypt cost=10 ─────────────────────────────────────────
    $t = microtime(true);
    $hash = password_hash($pwd, PASSWORD_BCRYPT, ['cost' => 10]);
    $elapsed = (microtime(true) - $t) * 1000;
    printf("  bcrypt/10: %s\n", substr($hash, 0, 30) . '...');
    printf("  Tiempo : %.2f ms | SEGURO: salt automático incluido\n", $elapsed);
    printf("  Salt   : %s\n", substr($hash, 7, 22));
    echo PHP_EOL;

    // ── bcrypt cost=12 ─────────────────────────────────────────
    $t = microtime(true);
    $hash = password_hash($pwd, PASSWORD_BCRYPT, ['cost' => 12]);
    $elapsed = (microtime(true) - $t) * 1000;
    printf("  bcrypt/12: %s\n", substr($hash, 0, 30) . '...');
    printf("  Tiempo : %.2f ms | SEGURO: recomendado producción\n", $elapsed);
    echo PHP_EOL;

    // ── Argon2id ───────────────────────────────────────────────
    if (defined('PASSWORD_ARGON2ID')) {
        $t = microtime(true);
        $hash = password_hash($pwd, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,  // 64 MB
            'time_cost'   => 4,
            'threads'     => 1,
        ]);
        $elapsed = (microtime(true) - $t) * 1000;
        printf("  Argon2id : %s\n", substr($hash, 0, 40) . '...');
        printf("  Tiempo   : %.2f ms | MUY SEGURO: resiste ASICs\n", $elapsed);
        echo PHP_EOL;
    }

    // ── Verificación de hash ───────────────────────────────────
    echo '  VERIFICACIÓN bcrypt:' . PHP_EOL;
    $storedHash = password_hash($pwd, PASSWORD_BCRYPT, ['cost' => 12]);
    $correct    = password_verify($pwd,      $storedHash);
    $incorrect  = password_verify('wrong',   $storedHash);
    printf("  password_verify(correcto) : %s\n", $correct   ? 'true ✓' : 'false ✗');
    printf("  password_verify(incorrecto): %s\n", $incorrect ? 'true ✗' : 'false ✓');
    echo PHP_EOL . str_repeat('=', 70) . PHP_EOL . PHP_EOL;
}

// ── Demostración: mismo input → mismo MD5 (predecible) ─────────
echo 'PROBLEMA DE MD5: Mismo input SIEMPRE produce mismo output' . PHP_EOL;
echo '  Sin salt: todos los usuarios con "password" tienen el mismo hash' . PHP_EOL;
echo '  MD5("password") = ' . md5('password') . PHP_EOL;
echo '  (Este hash aparece en TODAS las rainbow tables)' . PHP_EOL;
echo PHP_EOL;

// ── Demostración: bcrypt genera salt único por hash ────────────
echo 'VENTAJA bcrypt: Mismo input → hashes DIFERENTES (salt único)' . PHP_EOL;
$p = 'password';
echo '  hash1: ' . password_hash($p, PASSWORD_BCRYPT) . PHP_EOL;
echo '  hash2: ' . password_hash($p, PASSWORD_BCRYPT) . PHP_EOL;
echo '  (Son distintos, pero password_verify() funciona con ambos)' . PHP_EOL;

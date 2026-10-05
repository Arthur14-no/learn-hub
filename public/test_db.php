<?php
echo "Test de connexion MySQL<br><br>";

// Test 1 : port 3307
try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3307;dbname=learn_hub;charset=utf8mb4", "root", "");
    echo "✅ Connexion réussie sur 127.0.0.1:3307<br>";
} catch (PDOException $e) {
    echo "❌ Échec sur 127.0.0.1:3307 : " . $e->getMessage() . "<br>";
}

// Test 2 : port 3306 (WAMP)
try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=learn_hub;charset=utf8mb4", "root", "");
    echo "✅ Connexion réussie sur 127.0.0.1:3306<br>";
} catch (PDOException $e) {
    echo "❌ Échec sur 127.0.0.1:3306 : " . $e->getMessage() . "<br>";
}

// Test 3 : port 3308 (au cas où)
try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3308;dbname=learn_hub;charset=utf8mb4", "root", "");
    echo "✅ Connexion réussie sur 127.0.0.1:3308<br>";
} catch (PDOException $e) {
    echo "❌ Échec sur 127.0.0.1:3308 : " . $e->getMessage() . "<br>";
}
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Kaczy\Myjnia\Login;
use Kaczy\Myjnia\Connect;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$login = Login::getInstance();
if (!$login->isLoggedIn()) {
    header('Location: logowanie.php');
    exit;
}

$pdo = Connect::gConnection();

// Usuwanie rezerwacji
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM rezerwacje WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: admin.php');
    exit;
}

// Pobierz rezerwacje
$stmt = $pdo->query("SELECT * FROM rezerwacje ORDER BY data ASC");
$reservations = $stmt->fetchAll();

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader);

echo $twig->render('admin.twig', ['reservations' => $reservations]);

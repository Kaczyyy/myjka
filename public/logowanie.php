<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Kaczy\Myjnia\Login;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader);

$login = Login::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($login->login($_POST['username'], $_POST['password'])) {
        header('Location: admin.php');
        exit;
    } else {
        echo $twig->render('login.twig', ['error' => 'Nieprawidłowe dane logowania.']);
        exit;
    }
}

echo $twig->render('login.twig');

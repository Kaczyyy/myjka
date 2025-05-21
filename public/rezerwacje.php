<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Kaczy\Myjnia\Connect;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $reservationDate = $_POST['reservationDate'] ?? '';

    try {
        $pdo = Connect::gConnection();

        $stmt = $pdo->prepare('INSERT INTO rezerwacje (data, imie, telefon, email) VALUES (:data, :imie, :telefon, :email)');

        $success = $stmt->execute([
            ':data'    => $reservationDate,
            ':imie'    => $name,
            ':telefon' => $phone,
            ':email'   => $email
        ]);

        if ($success) {
            echo "<script>
                alert('Rezerwacja została zapisana!');
                window.location.href = './index.php';
            </script>";
        } else {
            echo "<script>
                alert('Wystąpił błąd przy zapisie rezerwacji.');
                window.location.href = './index.php';
            </script>";
        }

    } catch (PDOException $e) {
        echo "<script>
            alert('Błąd bazy danych: " . addslashes($e->getMessage()) . "');
            window.location.href = './index.php';
        </script>";
    }
}

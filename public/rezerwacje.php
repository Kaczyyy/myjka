<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Kaczy\Myjnia\Connect;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $reservationDate = $_POST['reservationDate'] ?? '';
    $reservationTime = $_POST['reservationTime'] ?? '';

    try {
        $pdo = Connect::gConnection();
        $check = $pdo->prepare("SELECT COUNT(*) FROM rezerwacje WHERE data = :data AND reservation_time = :time");
        $check->execute([
            ':data' => $reservationDate,
            ':time' => $reservationTime
        ]);

        if ($check->fetchColumn() > 0) {
            echo "<script>
                alert('Wybrana godzina ($reservationTime) w dniu $reservationDate jest już zajęta. Wybierz inną godzinę.');
                window.location.href = './index.php';
            </script>";
            exit;
        }
        $stmt = $pdo->prepare('INSERT INTO rezerwacje (data, imie, telefon, email, reservation_time) VALUES (:data, :imie, :telefon, :email, :reservation_time)');

        $success = $stmt->execute([
            ':data'    => $reservationDate,
            ':imie'    => $name,
            ':telefon' => $phone,
            ':email'   => $email,
            ':reservation_time' => $reservationTime
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

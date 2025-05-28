<?php
namespace Kaczy\Myjnia;

class Login
{
    private static ?Login $instance = null;

    private function __construct() {
        session_start();
    }

    public static function getInstance(): Login {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function login(string $username, string $password): bool {
        $validUser = $_ENV['ADMIN_USER'] ?? 'admin';
        $validPass = $_ENV['ADMIN_PASS'] ?? 'admin123';

        if ($username === $validUser && $password === $validPass) {
            $_SESSION['logged_in'] = true;
            return true;
        }
        return false;
    }

    public function isLoggedIn(): bool {
        return $_SESSION['logged_in'] ?? false;
    }

    public function logout(): void {
        session_destroy();
    }
}

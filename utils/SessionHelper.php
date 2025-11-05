<?php
/**
 * SessionHelper
 * Manejo de sesión y navegación para 4VGym
 */
class SessionHelper {

    /** Inicia la sesión si no está activa */
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* --- Gestión de usuario --- */

    public static function setUser(array $user): void {
        self::start();
        $_SESSION['user'] = [
            'id' => $user['id'] ?? null,
            'username' => $user['username'] ?? null,
            'role' => $user['role'] ?? 'monitor'
        ];
    }

    public static function getUser(): ?array {
        self::start();
        return $_SESSION['user'] ?? null;
    }

    public static function isLogged(): bool {
        self::start();
        return isset($_SESSION['user']);
    }

    public static function clearUser(): void {
        self::start();
        unset($_SESSION['user']);
    }

    /* --- Navegación --- */

    public static function setLastPage(string $page): void {
        self::start();
        $_SESSION['last_page'] = $page;
    }

    public static function getLastPage(): string {
        self::start();
        return $_SESSION['last_page'] ?? '/app/listaActividades.php';
    }

    public static function clearLastPage(): void {
        self::start();
        unset($_SESSION['last_page']);
    }

    public static function destroy(): void {
        self::start();
        session_unset();
        session_destroy();
    }

    /** Guardar la página actual automáticamente si no es una página excluida */
    public static function registerCurrentPage(array $excludePages = []): void {
        self::start();
        if (!self::isLogged()) return;

        $currentPage = $_SERVER['REQUEST_URI'];
        foreach ($excludePages as $page) {
            if (str_contains($currentPage, $page)) {
                return;
            }
        }
        self::setLastPage($currentPage);
    }
}

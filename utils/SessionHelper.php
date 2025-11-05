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

    /** Guarda usuario logueado */
    public static function setUser(array $user): void {
        self::start();
        $_SESSION['user'] = [
            'id' => $user['id'] ?? null,
            'username' => $user['username'] ?? null,
            'role' => $user['role'] ?? 'monitor'
        ];
    }

    /** Devuelve los datos del usuario o null */
    public static function getUser(): ?array {
        self::start();
        return $_SESSION['user'] ?? null;
    }

    /** Devuelve true si hay sesión iniciada */
    public static function isLogged(): bool {
        self::start();
        return isset($_SESSION['user']);
    }

    /** Cierra sesión */
    public static function clearUser(): void {
        self::start();
        unset($_SESSION['user']);
    }

    /* --- Navegación --- */

    /** Guarda la última página visitada */
    public static function setLastPage(string $page): void {
        self::start();
        $_SESSION['last_page'] = $page;
    }

    /** Devuelve la última página o el listado */
    public static function getLastPage(): string {
        self::start();
        return $_SESSION['last_page'] ?? '/app/listaActividades.php';
    }

    /** Borra la última página registrada */
    public static function clearLastPage(): void {
        self::start();
        unset($_SESSION['last_page']);
    }

    /** Cierre completo de sesión */
    public static function destroy(): void {
        self::start();
        session_unset();
        session_destroy();
    }
}

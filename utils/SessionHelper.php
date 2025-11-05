<?php
/**
 * SessionHelper
 *
 * Utilidades para gestionar la sesión de usuario
 * y la navegación de la aplicación 4VGym.
 *
 * Adaptado para 4VGym
 */

class SessionHelper {

    /** Inicia sesión si no está activa */
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* ------------------------------
       USUARIO
    ------------------------------ */

    /** Guarda los datos básicos del usuario logueado */
    public static function setUser(array $user) {
        self::start();
        $_SESSION['user_id'] = $user['id'] ?? null;
        $_SESSION['username'] = $user['username'] ?? null;
        $_SESSION['role'] = $user['role'] ?? 'monitor'; // Por defecto monitor
    }

    /** Devuelve el ID del usuario */
    public static function getUserId(): ?int {
        self::start();
        return $_SESSION['user_id'] ?? null;
    }

    /** Devuelve el nombre de usuario */
    public static function getUsername(): ?string {
        self::start();
        return $_SESSION['username'] ?? null;
    }

    /** Devuelve el rol del usuario */
    public static function getUserRole(): ?string {
        self::start();
        return $_SESSION['role'] ?? null;
    }

    /** Comprueba si hay sesión iniciada */
    public static function isLogged(): bool {
        self::start();
        return !empty($_SESSION['user_id']);
    }

    /** Cierra la sesión del usuario */
    public static function clearUser() {
        self::start();
        unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['role']);
    }

    /* ------------------------------
       NAVEGACIÓN
    ------------------------------ */

    /** Guarda la última página visitada (para redirecciones) */
    public static function setLastPage(string $page) {
        self::start();
        $_SESSION['last_page'] = $page;
    }

    /** Obtiene la última página visitada */
    public static function getLastPage(): string {
        self::start();
        return $_SESSION['last_page'] ?? 'pages/listaActividades.php';
    }

    /** Limpia el registro de última página */
    public static function clearLastPage() {
        self::start();
        unset($_SESSION['last_page']);
    }

    /* ------------------------------
       SESIÓN GENERAL
    ------------------------------ */

    /** Destruye completamente la sesión */
    public static function destroy() {
        self::start();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
}

<?php

/**
 * SessionHelper – Solo guarda la última página visitada
 */
class SessionHelper
{

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Guardar la página actual (solo la ruta, sin parámetros)
    public static function setLastPage(): void
    {
        self::start();
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $_SESSION['last_page'] = $path;
    }

    // Obtener la última página (o listaActividades si no hay)
    public static function getLastPage(): string
    {
        self::start();
        $last = $_SESSION['last_page'] ?? '';
        if ($last && $path = parse_url($last, PHP_URL_PATH)) {
            return $path;
        }
        return '/maite_sola/dw_01Eval_4VGym/app/listaActividades.php';
    }

    // Limpiar sesión (opcional, para pruebas)
    public static function destroy(): void
    {
        self::start();
        session_unset();
        session_destroy();
    }
}

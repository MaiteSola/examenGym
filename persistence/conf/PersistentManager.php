<?php

class PersistentManager {

    /** @var PersistentManager|null */
    private static $instance = null;

    /** @var mysqli|null */
    private static $connection = null;

    /** Parámetros de conexión */
    private $userBD = "";
    private $psswdBD = "";
    private $nameBD = "";
    private $hostBD = "";

    /**
     * Devuelve la instancia única (Singleton)
     */
    public static function getInstance(): self {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor privado: establece conexión a la BD
     */
    private function __construct() {
        // Mostrar errores SQL durante desarrollo
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $this->establishCredentials();

        try {
            self::$connection = mysqli_connect(
                $this->hostBD,
                $this->userBD,
                $this->psswdBD,
                $this->nameBD
            );
            mysqli_set_charset(self::$connection, 'utf8');
        } catch (mysqli_sql_exception $e) {
            error_log("❌ Error de conexión a la BD: " . $e->getMessage());
            throw new Exception("Error al conectar con la base de datos.");
        }
    }

    /**
     * Lee las credenciales desde el JSON
     */
    private function establishCredentials(): void {
        $path = __DIR__ . DIRECTORY_SEPARATOR . 'credentials.json';

        if (!file_exists($path)) {
            throw new Exception("❌ Archivo credentials.json no encontrado en: $path");
        }

        $credentialsJSON = file_get_contents($path);
        $credentials = json_decode($credentialsJSON, true);

        if (!$credentials) {
            throw new Exception("❌ Error al leer credentials.json (JSON mal formado)");
        }

        $this->userBD = $credentials["user"] ?? "";
        $this->psswdBD = $credentials["password"] ?? "";
        $this->nameBD = $credentials["name"] ?? "";
        $this->hostBD = $credentials["host"] ?? "localhost";
    }

    /**
     * Devuelve la conexión activa
     */
    public function get_connection(): mysqli {
        return self::$connection;
    }

    /**
     * Cierra la conexión actual
     */
    public function close_connection(): void {
        if (self::$connection) {
            mysqli_close(self::$connection);
            self::$connection = null;
        }
    }

    /**
     * Obtiene un usuario por email (ejemplo de método útil)
     */
    public function getUserByEmail(string $email): ?array {
        $conn = $this->get_connection();
        $stmt = mysqli_prepare($conn, "SELECT * FROM usuarios WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return $result && mysqli_num_rows($result) > 0
            ? mysqli_fetch_assoc($result)
            : null;
    }

    /* --- Métodos auxiliares (opcionales) --- */

    public function get_hostBD(): string { return $this->hostBD; }
    public function get_usuarioBD(): string { return $this->userBD; }
    public function get_psswdBD(): string { return $this->psswdBD; }
    public function get_nombreBD(): string { return $this->nameBD; }
}

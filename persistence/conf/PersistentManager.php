<?php

class PersistentManager {

    private static ?self $instance = null;
    private static ?mysqli $connection = null;

    private string $hostBD;
    private string $userBD;
    private string $psswdBD;
    private string $nameBD;

    /** Singleton: devuelve la única instancia */
    public static function getInstance(): self {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /** Constructor privado: abre la conexión */
    private function __construct() {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->loadCredentials();

        try {
            self::$connection = new mysqli(
                $this->hostBD,
                $this->userBD,
                $this->psswdBD,
                $this->nameBD
            );
            self::$connection->set_charset('utf8mb4');
        } catch (mysqli_sql_exception $e) {
            throw new Exception("❌ Error de conexión a la BD: " . $e->getMessage());
        }
    }

    /** Carga credenciales desde credentials.json */
    private function loadCredentials(): void {
        $path = __DIR__ . '/credentials.json';
        if (!file_exists($path)) {
            throw new Exception("Archivo credentials.json no encontrado en $path");
        }

        $data = json_decode(file_get_contents($path), true);
        if (!$data) {
            throw new Exception("Error al leer credentials.json");
        }

        $this->hostBD = $data['host'] ?? 'localhost';
        $this->userBD = $data['user'] ?? '';
        $this->psswdBD = $data['password'] ?? '';
        $this->nameBD = $data['name'] ?? '';
    }

    /** Devuelve conexión mysqli */
    public function get_connection(): mysqli {
        return self::$connection;
    }

    /** Cierra la conexión */
    public function close_connection(): void {
        if (self::$connection) {
            self::$connection->close();
            self::$connection = null;
        }
    }

    /* -------------------------------------------------------------------
       MÉTODOS ESPECÍFICOS PARA ACTIVIDADES
       ------------------------------------------------------------------- */

    /**
     * Obtiene todas las actividades (opcionalmente filtradas por fecha)
     */
    public function getActivities(?string $date = null): mysqli_result {
        $conn = self::$connection;
        if ($date) {
            $stmt = $conn->prepare("SELECT * FROM activities WHERE DATE(date) = ? ORDER BY date DESC");
            $stmt->bind_param('s', $date);
        } else {
            $stmt = $conn->prepare("SELECT * FROM activities ORDER BY date DESC");
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    // MÉTODO PARA NUEVAACTIVIDAD

        /**
     * Inserta una nueva actividad en la base de datos.
     */
    public function insertActivity(string $type, string $monitor, string $place, string $date): bool {
        $conn = self::$connection;
        $stmt = $conn->prepare("INSERT INTO activities (type, monitor, place, date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $type, $monitor, $place, $date);
        return $stmt->execute();
    }

}

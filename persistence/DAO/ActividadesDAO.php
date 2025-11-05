<?php
require_once __DIR__ . '/GenericDAO.php';

class ActividadesDAO extends GenericDAO
{

    /**
     * Obtiene todas las actividades, opcionalmente filtradas por fecha
     */
    public function getAllActividades($dateFilter = null)
    {
        try {
            if ($dateFilter) {
                $stmt = $this->conn->prepare(
                    "SELECT * FROM activities WHERE DATE(date) = ? ORDER BY date ASC"
                );
                $stmt->bind_param("s", $dateFilter);
            } else {
                $stmt = $this->conn->prepare(
                    "SELECT * FROM activities ORDER BY date ASC"
                );
            }

            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        } catch (mysqli_sql_exception $e) {
            throw new Exception("Error al obtener actividades: " . $e->getMessage());
        }
    }

    /**
     * Inserta una nueva actividad en la base de datos
     */
    public function insertActividad($type, $monitor, $place, $date)
    {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO activities (type, monitor, place, date) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("ssss", $type, $monitor, $place, $date);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            throw new Exception("Error al insertar actividad: " . $e->getMessage());
        }
    }

    /**
     * Obtiene una actividad por ID (para editar o borrar)
     */
    public function getActividadById($id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM activities WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            throw new Exception("Error al obtener la actividad: " . $e->getMessage());
        }
    }

    /**
     * Actualiza una actividad existente
     */
    public function updateActividad($id, $type, $monitor, $place, $date)
    {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE activities SET type = ?, monitor = ?, place = ?, date = ? WHERE id = ?"
            );
            $stmt->bind_param("ssssi", $type, $monitor, $place, $date, $id);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            throw new Exception("Error al actualizar actividad: " . $e->getMessage());
        }
    }

    /**
     * Elimina una actividad por ID
     */
    public function deleteActividad($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM activities WHERE id = ?");
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            throw new Exception("Error al eliminar actividad: " . $e->getMessage());
        }
    }
}

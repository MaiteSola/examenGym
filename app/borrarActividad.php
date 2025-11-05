<?php
require_once __DIR__ . '/../persistence/conf/PersistentManager.php';
require_once __DIR__ . '/../utils/sessionHelper.php';

SessionHelper::start();

if (!isset($_GET['id'])) {
    die("❌ No se especificó el ID de la actividad a eliminar.");
}

$id = intval($_GET['id']);

try {
    $pm = PersistentManager::getInstance();
    $conn = $pm->get_connection();

    $stmt = $conn->prepare("DELETE FROM activities WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();

    header('Location: listaActividades.php?msg=deleted');
    exit();
} catch (mysqli_sql_exception $e) {
    die("❌ Error al borrar la actividad: " . $e->getMessage());
}
?>

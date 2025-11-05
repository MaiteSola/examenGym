<?php
require_once __DIR__ . '/../utils/sessionHelper.php';
require_once __DIR__ . '/../persistence/dao/ActividadesDAO.php';

SessionHelper::start();

if (!isset($_GET['id'])) {
    die("❌ ID de actividad no especificado.");
}

$id = intval($_GET['id']);
$dao = new ActividadesDAO();

try {
    $dao->deleteActividad($id);
} catch (Exception $e) {
    die("❌ Error al borrar la actividad: " . htmlspecialchars($e->getMessage()));
}

// Redirigir al listado
header('Location: /maite_sola/dw_01Eval_4VGym/app/listaActividades.php');
exit();

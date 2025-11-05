<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../utils/sessionHelper.php';
require_once __DIR__ . '/../persistence/dao/ActividadesDAO.php';

SessionHelper::start();
SessionHelper::setLastPage($_SERVER['REQUEST_URI']); // Guardar la página actual

$dao = new ActividadesDAO();
$message = '';

if (!isset($_GET['id'])) {
    die("❌ ID de actividad no especificado.");
}

$id = intval($_GET['id']);
$activity = $dao->getActividadById($id);

if (!$activity) {
    die("⚠️ Actividad no encontrada.");
}

// Procesar formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = trim($_POST['type'] ?? '');
    $monitor = trim($_POST['monitor'] ?? '');
    $place = trim($_POST['place'] ?? '');
    $date = trim($_POST['date'] ?? '');

    if ($type && $monitor && $place && $date) {
        try {
            $ok = $dao->updateActividad($id, $type, $monitor, $place, $date);
            if ($ok) {
                header('Location: /maite_sola/dw_01Eval_4VGym/app/listaActividades.php');
                exit();
            } else {
                $message = "❌ No se pudo actualizar la actividad.";
            }
        } catch (Exception $e) {
            $message = "❌ Error al actualizar actividad: " . $e->getMessage();
        }
    } else {
        $message = "⚠️ Todos los campos son obligatorios.";
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Editar Actividad</h2>

    <?php if ($message): ?>
        <div class="alert alert-warning"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" class="border rounded p-4 bg-light shadow-sm">
        <div class="mb-3">
            <label for="type" class="form-label">Tipo</label>
            <input type="text" class="form-control" id="type" name="type" 
                   value="<?= htmlspecialchars($activity['type']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="monitor" class="form-label">Monitor</label>
            <input type="text" class="form-control" id="monitor" name="monitor" 
                   value="<?= htmlspecialchars($activity['monitor']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="place" class="form-label">Lugar</label>
            <input type="text" class="form-control" id="place" name="place" 
                   value="<?= htmlspecialchars($activity['place']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Fecha y hora</label>
            <input type="datetime-local" class="form-control" id="date" name="date" 
                   value="<?= date('Y-m-d\TH:i', strtotime($activity['date'])) ?>" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-success">Guardar cambios</button>
            <a href="/maite_sola/dw_01Eval_4VGym/app/listaActividades.php" class="btn btn-secondary">Volver</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>

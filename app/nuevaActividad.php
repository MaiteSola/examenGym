<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../utils/sessionHelper.php';
require_once __DIR__ . '/../persistence/conf/PersistentManager.php';

// Iniciar sesión y registrar última página vista
SessionHelper::start();
SessionHelper::setLastPage('/app/nuevaActividad.php');

// Obtener instancia del gestor de persistencia
$pm = PersistentManager::getInstance();
$message = '';

// Procesar formulario al enviar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = trim($_POST['type'] ?? '');
    $monitor = trim($_POST['monitor'] ?? '');
    $place = trim($_POST['place'] ?? '');
    $date = trim($_POST['date'] ?? '');

    if ($type && $monitor && $place && $date) {
        try {
            $ok = $pm->insertActivity($type, $monitor, $place, $date);
            if ($ok) {
                header('Location: listaActividades.php');
                exit();
            } else {
                $message = "❌ No se pudo guardar la actividad.";
            }
        } catch (Exception $e) {
            $message = "❌ Error al insertar actividad: " . $e->getMessage();
        }
    } else {
        $message = "⚠️ Todos los campos son obligatorios.";
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Nueva Actividad</h2>

    <?php if ($message): ?>
        <div class="alert alert-warning text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" class="border rounded p-4 bg-light shadow-sm mx-auto" style="max-width: 600px;">
        <div class="mb-3">
            <label for="type" class="form-label">Tipo de Actividad</label>
            <select class="form-select" id="type" name="type" required>
                <option value="">Selecciona tipo...</option>
                <option value="bodypump">BodyPump</option>
                <option value="pilates">Pilates</option>
                <option value="spinning">Spinning</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="monitor" class="form-label">Monitor</label>
            <input type="text" class="form-control" id="monitor" name="monitor" required>
        </div>

        <div class="mb-3">
            <label for="place" class="form-label">Lugar</label>
            <input type="text" class="form-control" id="place" name="place" required>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Fecha y hora</label>
            <input type="datetime-local" class="form-control" id="date" name="date" required>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success me-2">Guardar</button>
            <a href="listaActividades.php" class="btn btn-secondary">Volver</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>

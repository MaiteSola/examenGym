<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../utils/SessionHelper.php';
require_once __DIR__ . '/../persistence/dao/ActividadesDAO.php';


SessionHelper::setLastPage();

$dao = new ActividadesDAO();
$error = '';
$type = $monitor = $place = $date = '';

// Solo procesar POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = trim($_POST['type'] ?? '');
    $monitor = trim($_POST['monitor'] ?? '');
    $place = trim($_POST['place'] ?? '');
    $date = trim($_POST['date'] ?? '');

    // Validación secuencial
    if (!$type) {
        $error = "El campo 'Tipo de Actividad' es obligatorio.";
    } elseif (!in_array(strtolower($type), ['bodypump', 'pilates', 'spinning'])) {
        $error = "Tipo de actividad no válido. Debe ser BodyPump, Spinning o Pilates.";
    } elseif (!$monitor) {
        $error = "El campo 'Monitor' es obligatorio.";
    } elseif (!$place) {
        $error = "El campo 'Lugar' es obligatorio.";
    } elseif (!$date) {
        $error = "El campo 'Fecha y hora' es obligatorio.";
    } elseif (strtotime($date) <= time()) {
        $error = "La fecha debe ser posterior a la fecha y hora actual.";
    } else {
        // Insertar si todo es correcto
        try {
            $ok = $dao->insertActividad($type, $monitor, $place, $date);
            if ($ok) {
                header('Location: /maite_sola/dw_01Eval_4VGym/app/listaActividades.php');
                exit();
            } else {
                $error = "❌ No se pudo guardar la actividad.";
            }
        } catch (Exception $e) {
            $error = "❌ Error al insertar actividad: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Nueva Actividad</h2>

    <?php if ($error): ?>
        <div class="alert alert-warning text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="border rounded p-4 bg-light shadow-sm mx-auto" style="max-width: 600px;">
        <div class="mb-3">
            <label for="type" class="form-label">Tipo de Actividad</label>
            <select class="form-select" id="type" name="type">
                <option value="">Selecciona tipo...</option>
                <option value="bodypump" <?= ($type === 'bodypump') ? 'selected' : '' ?>>BodyPump</option>
                <option value="pilates" <?= ($type === 'pilates') ? 'selected' : '' ?>>Pilates</option>
                <option value="spinning" <?= ($type === 'spinning') ? 'selected' : '' ?>>Spinning</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="monitor" class="form-label">Monitor</label>
            <input type="text" class="form-control" id="monitor" name="monitor" value="<?= htmlspecialchars($monitor) ?>">
        </div>

        <div class="mb-3">
            <label for="place" class="form-label">Lugar</label>
            <input type="text" class="form-control" id="place" name="place" value="<?= htmlspecialchars($place) ?>">
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Fecha y hora</label>
            <input type="datetime-local" class="form-control" id="date" name="date" value="<?= htmlspecialchars($date) ?>">
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success me-2">Guardar</button>
            <a href="/maite_sola/dw_01Eval_4VGym/app/listaActividades.php" class="btn btn-secondary">Volver</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
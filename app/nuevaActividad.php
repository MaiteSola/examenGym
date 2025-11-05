<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../persistence/conf/PersistentManager.php';
require_once __DIR__ . '/../utils/sessionHelper.php';

SessionHelper::start();
SessionHelper::setLastPage('/app/nuevaActividad.php');

// Conexión
$pm = PersistentManager::getInstance();
$conn = $pm->get_connection();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = trim($_POST['type']);
    $monitor = trim($_POST['monitor']);
    $place = trim($_POST['place']);
    $date = trim($_POST['date']);

    if ($type && $monitor && $place && $date) {
        try {
            $stmt = $conn->prepare("INSERT INTO activities (type, monitor, place, date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $type, $monitor, $place, $date);
            $stmt->execute();

            header('Location: listaActividades.php');
            exit();
        } catch (mysqli_sql_exception $e) {
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
        <div class="alert alert-warning"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" class="border rounded p-4 bg-light shadow-sm">
        <div class="mb-3">
            <label for="type" class="form-label">Tipo de Actividad</label>
            <input type="text" class="form-control" id="type" name="type" required>
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
        <div class="text-center">
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="listaActividades.php" class="btn btn-secondary">Volver</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>

<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../persistence/conf/PersistentManager.php';
require_once __DIR__ . '/../utils/sessionHelper.php';

SessionHelper::start();
SessionHelper::setLastPage('/app/editarActividad.php');

// Conexión
$pm = PersistentManager::getInstance();
$conn = $pm->get_connection();

if (!isset($_GET['id'])) {
    die("❌ ID de actividad no especificado.");
}

$id = intval($_GET['id']);
$message = '';

// Obtener datos actuales
$stmt = $conn->prepare("SELECT * FROM activities WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$activity = $result->fetch_assoc();

if (!$activity) {
    die("⚠️ Actividad no encontrada.");
}

// Guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = trim($_POST['type']);
    $monitor = trim($_POST['monitor']);
    $place = trim($_POST['place']);
    $date = trim($_POST['date']);

    if ($type && $monitor && $place && $date) {
        try {
            $stmt = $conn->prepare("UPDATE activities SET type=?, monitor=?, place=?, date=? WHERE id=?");
            $stmt->bind_param('ssssi', $type, $monitor, $place, $date, $id);
            $stmt->execute();

            header('Location: listaActividades.php');
            exit();
        } catch (mysqli_sql_exception $e) {
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
            <input type="text" class="form-control" id="type" name="type" value="<?= htmlspecialchars($activity['type']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="monitor" class="form-label">Monitor</label>
            <input type="text" class="form-control" id="monitor" name="monitor" value="<?= htmlspecialchars($activity['monitor']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="place" class="form-label">Lugar</label>
            <input type="text" class="form-control" id="place" name="place" value="<?= htmlspecialchars($activity['place']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Fecha y hora</label>
            <input type="datetime-local" class="form-control" id="date" name="date" 
                   value="<?= date('Y-m-d\TH:i', strtotime($activity['date'])) ?>" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-success">Guardar cambios</button>
            <a href="listaActividades.php" class="btn btn-secondary">Volver</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>

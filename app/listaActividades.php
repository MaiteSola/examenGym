<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../persistence/conf/PersistentManager.php';
require_once __DIR__ . '/../utils/sessionHelper.php';

// Iniciar sesión y registrar la última página vista
SessionHelper::start();
SessionHelper::setLastPage('/app/listaActividades.php');

// Obtener conexión a la BD desde PersistentManager
$pm = PersistentManager::getInstance();
$conn = $pm->get_connection();

// Filtro por fecha
$where = '';
$params = [];

if (!empty($_GET['activityDate'])) {
    $date = $_GET['activityDate'];
    $where = "WHERE DATE(date) = ?";
    $params[] = $date;
}

// Consulta de actividades
$query = "SELECT * FROM activities $where ORDER BY date DESC";

try {
    $stmt = $conn->prepare($query);

    if (!empty($params)) {
        $stmt->bind_param('s', $params[0]);
    }

    $stmt->execute();
    $result = $stmt->get_result();
} catch (mysqli_sql_exception $e) {
    die("❌ Error al ejecutar la consulta: " . $e->getMessage());
}
?>

<!-- Welcome Content -->
<div class="container-fluid">
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <div class="row align-items-center">
            <div class="col-md-5">
                <img class="img-fluid img-rounded" src="../assets/img/main-logo.png" alt="Logo 4VGym">
            </div>
            <div class="col-md-7">
                <h1 class="alert-heading">4VGym, GYM de 4V</h1>
                <p>Ponte en forma y ganarás vida</p>
                <hr />
                <form action="" method="get" class="row g-2 align-items-center">
                    <div class="col-auto">
                        <input 
                            name="activityDate" 
                            id="activityDate" 
                            class="form-control" 
                            type="date"
                            value="<?= htmlspecialchars($_GET['activityDate'] ?? '') ?>"
                        />
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-outline-success" type="submit">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Listado de Actividades -->
<div class="container my-4">
    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($activity = $result->fetch_assoc()): ?>
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="card mb-4 shadow-sm">
                        <!-- Imagen según el tipo -->
                        <img 
                            class="card-img-top w-50 p-3 img-fluid mx-auto" 
                            src="../assets/img/<?= htmlspecialchars($activity['type']) ?>.png" 
                            alt="<?= htmlspecialchars($activity['type']) ?>"
                        >
                        <div class="card-body text-center">
                            <h2 class="card-title h5"><?= htmlspecialchars($activity['place']) ?></h2>
                            <p class="card-text mb-1"><?= date('d M Y H:i', strtotime($activity['date'])) ?></p>
                            <p class="card-text fw-semibold"><?= htmlspecialchars($activity['monitor']) ?></p>
                        </div>
                        <div class="card-footer text-center">
                            <a class="btn btn-success btn-sm me-2" href="editarActividad.php?id=<?= $activity['id'] ?>">Modificar</a>
                            <a 
                                class="btn btn-danger btn-sm" 
                                href="borrarActividad.php?id=<?= $activity['id'] ?>" 
                                onclick="return confirm('¿Seguro que quieres borrar esta actividad?');"
                            >Borrar</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <div class="alert alert-info mt-4">No hay actividades registradas para la fecha seleccionada.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>

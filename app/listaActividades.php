<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../utils/sessionHelper.php';
require_once __DIR__ . '/../persistence/dao/ActividadesDAO.php';

// Iniciar sesión y registrar última página automáticamente
// Excluimos páginas como login.php o logout.php si las hubiera
SessionHelper::registerCurrentPage();

// Instanciar el DAO
$dao = new ActividadesDAO();
$dateFilter = $_GET['activityDate'] ?? null;

// Obtener todas las actividades
$actividades = $dao->getAllActividades($dateFilter);
?>

<!-- Banner -->
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
                            value="<?= htmlspecialchars($dateFilter ?? '') ?>"
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

<!-- Listado -->
<div class="container my-4">
    <div class="row">
        <?php if (!empty($actividades)): ?>
            <?php foreach ($actividades as $activity): ?>
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="card mb-4 shadow-sm text-center">
                        <img 
                            class="card-img-top w-50 p-3 img-fluid mx-auto" 
                            src="../assets/img/<?= htmlspecialchars($activity['type']) ?>.png" 
                            alt="<?= htmlspecialchars($activity['type']) ?>"
                        >
                        <div class="card-body">
                            <h2 class="card-title h5"><?= htmlspecialchars($activity['place']) ?></h2>
                            <p class="card-text mb-1"><?= date('d M Y H:i', strtotime($activity['date'])) ?></p>
                            <p class="card-text fw-semibold"><?= htmlspecialchars($activity['monitor']) ?></p>
                        </div>
                        <div class="card-footer text-center">
                            <a 
                                class="btn btn-success btn-sm text-white me-2 px-3" 
                                href="editarActividad.php?id=<?= $activity['id'] ?>"
                            >Modificar</a>
                            <a 
                                class="btn btn-danger btn-sm text-white px-3" 
                                href="borrarActividad.php?id=<?= $activity['id'] ?>" 
                                onclick="return confirm('¿Seguro que quieres borrar esta actividad?');"
                            >Borrar</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <div class="alert alert-info mt-4">No hay actividades registradas para la fecha seleccionada.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>

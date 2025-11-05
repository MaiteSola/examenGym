<div class="col-sm-12 col-md-6 col-lg-4">
  <div class="card mb-4">
    <img class="card-img-top w-50 p-3 img-fluid mx-auto" src="assets/img/<?= $activity['type'] ?>.png" alt="">
    <div class="card-body text-center">
      <h2 class="card-title"><?= htmlspecialchars($activity['place']) ?></h2>
      <p class="card-text"><?= date('d M Y H:i', strtotime($activity['date'])) ?></p>
      <p class="card-text"><?= htmlspecialchars($activity['monitor']) ?></p>
    </div>
    <div class="card-footer text-center">
      <a class="btn btn-success" href="editActivity.php?id=<?= $activity['id'] ?>">Modificar</a>
      <a class="btn btn-danger" href="deleteActivity.php?id=<?= $activity['id'] ?>">Borrar</a>
    </div>
  </div>
</div>

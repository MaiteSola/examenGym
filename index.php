<?php
require_once __DIR__ . '/utils/SessionHelper.php';

// Iniciar sesión
SessionHelper::start();

// Si hay última página guardada → ir ahí
if (!empty($_SESSION['last_page'])) {
    $lastPage = SessionHelper::getLastPage();
    header("Location: $lastPage");
    exit();
}

// Si no hay nada → ir al listado
header('Location: /maite_sola/dw_01Eval_4VGym/app/listaActividades.php');
exit();
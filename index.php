<?php
require_once __DIR__ . '/utils/sessionHelper.php';

SessionHelper::start();

// Si no hay sesión → al listado público
if (!SessionHelper::isLogged()) {
    header('Location: /maite_sola/dw_01Eval_4VGym/app/listaActividades.php');
    exit();
}

// Usuario logueado → redirige a la última página visitada
$lastPage = SessionHelper::getLastPage();
header("Location: $lastPage");
exit();

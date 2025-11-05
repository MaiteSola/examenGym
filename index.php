<?php
require_once __DIR__ . '/utils/sessionHelper.php';

/*
  Este index es el punto de entrada principal de la app 4VGym.
  - Si no hay sesión, redirige al listado de actividades (página pública).
  - Si hay sesión, redirige a la última página visitada.
*/

SessionHelper::start();

// Si no hay sesión activa → al listado
if (!SessionHelper::isLogged()) {
    header('Location: app/listaActividades.php');
    exit();
}

// Si hay sesión → redirigir a la última página vista
$lastPage = SessionHelper::getLastPage();
header("Location: $lastPage");
exit();

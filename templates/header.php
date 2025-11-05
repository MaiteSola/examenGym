<?php 
require_once __DIR__ . '/../utils/sessionHelper.php';

// Ruta base (ajústala si el nombre de la carpeta cambia)
define("RUTA_BASE", "/maite_sola/dw_01Eval_4VGym");

// Rutas relativas
$listaActividades = RUTA_BASE . "/app/listaActividades.php";
$nuevaActividad   = RUTA_BASE . "/app/nuevaActividad.php";
$logo             = RUTA_BASE . "/assets/img/main-logo.png";
$css              = RUTA_BASE . "/assets/css/style.css";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>4VGym</title>

    <!-- Bootstrap Core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Icons CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/octicons/3.5.0/octicons.min.css">

    <!-- CSS Propio -->
    <link rel="stylesheet" href="<?= $css ?>">
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-light bg-light navbar-fixed-top navbar-expand-md shadow-sm" role="navigation">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler02"
                aria-controls="navbarToggler02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarToggler02">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="navbar-brand" href="<?= $listaActividades ?>">
                            <img class="img-fluid rounded d-inline-block align-top"
                                 src="<?= $logo ?>" alt="Logo 4VGym" width="30" height="30">
                            4VGYM
                        </a>
                    </li>
                </ul>
                <div class="ml-auto">
                    <a type="button" class="btn btn-info" href="<?= $nuevaActividad ?>">
                        <span class="octicon octicon-cloud-upload"></span> Subir Actividad
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="mt-4 container-fluid">

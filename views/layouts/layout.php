<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="<?= asset('build/js/app.js') ?>"></script>
    <link rel="shortcut icon" href="<?= asset('images/cit.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <title>DemoApp</title>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container-fluid">

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item me-4">
            <a class="nav-link active" aria-current="page" href="/proyecto_uno/inicio"><i class="bi bi-house"> INICIO</i></a>
          </li>
          
          <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            ADMINISTRADOR
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/proyecto_uno/registro">USUARIOS</a></li>
            <li><a class="dropdown-item" href="/proyecto_uno/permiso">PERMISOS</a></li>
            <li><a class="dropdown-item" href="/proyecto_uno/aplicacion">APLICACIONES</a></li>
            <li><a class="dropdown-item" href="/proyecto_uno/rol">ROLES</a></li>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            TIENDA
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/proyecto_uno/clientes">CLIENTES</a></li>
            <li><a class="dropdown-item" href="/proyecto_uno/ventas">VENTAS</a></li>
            <li><a class="dropdown-item" href="/proyecto_uno/inventario">INVENTARIO</a></li>
            <li><a class="dropdown-item" href="/proyecto_uno/marcas">MARCAS</a></li>
          </ul>
        </li>
        </ul>
        
      </div>
    </div>
  </nav>
    <div class="progress fixed-bottom" style="height: 6px;">
        <div class="progress-bar progress-bar-animated bg-danger" id="bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="container-fluid pt-5 mb-4" style="min-height: 85vh">
        
        <?php echo $contenido; ?>
    </div>
    <div class="container-fluid " >
        <div class="row justify-content-center text-center">
            <div class="col-12">
                <p style="font-size:xx-small; font-weight: bold;">
                        Comando de Informática y Tecnología, <?= date('Y') ?> &copy;
                </p>
            </div>
        </div>
    </div>


</body>
</html>
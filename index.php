<?php

require 'config/config.php';
require 'config/database.php';

$conexion = new Database();
$pdo = $conexion->conectar();

$sql = "SELECT * FROM productos WHERE activo = 1";
$statement = $pdo->prepare($sql);
$statement->execute();

$resultado = $statement->fetchAll(PDO::FETCH_ASSOC);

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <header data-bs-theme="dark">
        <div class="navbar navbar-dark bg-dark navbar-expand-lg">
            <div class="container">
                <a href="/pasarela" class="navbar-brand">
                    <strong>Cadape Store</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarHeader">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="#" class="nav-link active">Catalogo</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Contacto</a>
                        </li>
                    </ul>

                    <a href="carrito.php" class="btn btn-primary">Carrito</a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php foreach ($resultado as $producto) { ?>
                    <div class="col">
                        <div class="card shadow-sm">

                            <?php
                            $id = $producto['id'];
                            $imagen = "img/productos/" . $id . "/principal.avif";

                            if (!file_exists($imagen)) {
                                $imagen = "img/no-photo.png";
                            }
                            ?>

                            <img src="<?php echo $imagen; ?>" class="d-block w-100">
                            <div class="card-body">
                                <h5 class="card-title"><?= $producto['nombre'] ?></h5>
                                <p class="card-text">$<?= number_format($producto['precio'], 2, '.', ',') ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="detalles.php?id=<?= $producto['id'] ?>&token=<?= hash_hmac('sha1', $producto['id'], KEY_TOKEN); ?>" class="btn btn-primary">Detalles</a>
                                    </div>
                                    <a href="" class="btn btn-success">Agregar</a>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>
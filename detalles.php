<?php

require 'config/config.php';
require 'config/database.php';

$conexion = new Database();
$pdo = $conexion->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id == '' || $token == '') {
    echo "No se ha encontrado el producto";
    exit;
} else {
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
    if ($token_tmp == $token) {

        $sql = "SELECT count(id) FROM productos WHERE id =? AND activo = 1";
        $statement = $pdo->prepare($sql);
        $statement->execute(array($id));

        if ($statement->fetchColumn() > 0) {
            $sql = "SELECT * FROM productos WHERE id =? AND activo = 1 LIMIT 1";
            $statement = $pdo->prepare($sql);
            $statement->execute(array($id));
            $producto = $statement->fetch(PDO::FETCH_ASSOC);

            $precio = $producto['precio'];
            $nombre = $producto['nombre'];
            $descripcion = $producto['descripcion'];
            $descuento = $producto['descuento'];
            $precio_descuento = $precio - ($precio * $descuento / 100);
            $dir_imagenes = 'img/productos/' . $id . '/';

            $rutaImg = $dir_imagenes . 'principal.avif';

            if (!file_exists($rutaImg)) {
                $rutaImg = 'img/no-photo.png';
            }

            $imagenes = array();
            $dir = dir($dir_imagenes);

            while (false !== ($archivo = $dir->read())) {
                if ($archivo != 'principal.avif' && (strpos($archivo, '.avif') || strpos($archivo, '.webp'))) {
                    $imagenes[] = $dir_imagenes . $archivo;
                }
            }
            $dir->close();
        }
    } else {
        echo "No se ha encontrado el producto";
        exit;
    }
}

$sql = "SELECT * FROM productos";
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
            <div class="row">
                <div class="col-md-6 order-md-1">

                    <div id="carruselImagenes" class="carousel slide">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="img/productos/<?= $id; ?>/principal.avif" class="d-block w-100" alt="<?php echo $nombre; ?>">
                            </div>
                            <?php foreach ($imagenes as $imagen) { ?>
                                <div class="carousel-item">
                                    <img src="<?= $imagen; ?>" class="d-block w-100" alt="<?php echo $nombre; ?>">
                                </div>
                            <?php } ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carruselImagenes" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carruselImagenes" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="col-md-6 order-md-2">
                    <h2><?php echo $nombre; ?></h2>

                    <?php if ($descuento > 0) { ?>
                        <p><del><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></del></p>
                        <h3>
                            <?php echo MONEDA . number_format($precio_descuento, 2, '.', ','); ?>
                            <small class="text-success">
                                <?php echo $descuento; ?>% Descuento
                            </small>
                        </h3>

                    <?php } else { ?>
                        <h3><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></h3>
                    <?php } ?>
                    <p class="lead"><?php echo $descripcion; ?></p>
                    <div class="d-grid gap-3 col-10 mx-auto">
                        <button class="btn btn-primary" type="button">
                            Agregar al carrito
                        </button>
                        <button class="btn btn-primary" type="button">
                            Comprar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>
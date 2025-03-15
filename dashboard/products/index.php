<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Productos</title>
</head>
<body>
    <main>
        <section class="container">
            <h1 class="title">Productos</h1>
            <div class="flexContainer newProductWrapper">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                    <label class="form-check-label" for="flexSwitchCheckDefault">Los Mesmos Show</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                    <label class="form-check-label" for="flexSwitchCheckDefault">K Rolllo Show</label>
                </div>
                <button class='newProductButton borderRadius' data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bi bi-plus-circle"></i>
                    Agregar nuevo producto
                </button>
            </div>
            <?php
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://localhost/ecommerce-backend/controllers/products/getAllProducts/index.php");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
                
                $datos = json_decode($response, true);                
            ?>
            <div class="productsWrapper flexContainer">
            <?php foreach ($datos as $producto): ?>
            <a href='#' class="productCard boxShadow borderRadius hover">
                <figure class="productImage">
                    <img src="<?php echo $producto['producto_imagen']?>"/>
                </figure>
                <span>
                    <?php echo $producto['producto_nombre']; ?>
                </span>
            </a>
            <?php endforeach; ?>
            </div>
        </section>
    </main>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    Agregar nuevo producto
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action='./index.php'>
                <div class="mb-3">
                    <label for="recipient-name" class="col-form-label">Nombre:</label>
                    <input type="text" class="form-control" id="recipient-name">
                </div>
                <div class="mb-3">
                    <label for="message-text" class="col-form-label">Precio:</label>
                    <input type="number" class="form-control" id="recipient-name">
                </div>
                <div class="mb-3">
                    <label for="message-text" class="col-form-label">Imágen:</label>
                    <input type="file" class="form-control" id="recipient-name">
                </div>
                <div class="mb-3">
                    <label for="message-text" class="col-form-label">Marca:</label>
                    <select class="form-select" aria-label="Default select example">
                        <option required>Seleccione una opción</option>
                        <option value="1">Mesmos Show</option>
                        <option value="2">K Rollo Show</option>
                    </select>
                </div>
                <button class="btn btn-primary">Agregar producto</button>
                </form>
            </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
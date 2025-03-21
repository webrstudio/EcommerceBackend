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
            <h1 class="title">Gestiona tus productos</h1>
            <?php
                require './menu.php';
            ?>
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
    <?php
        require './newProduct.php';
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
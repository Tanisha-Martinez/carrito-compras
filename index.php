<?php 
    include 'global/config.php';
    include 'global/conexion.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hilos de Amor</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">"
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
  
</head>
<body>
<!–- Inicio de barra de navegacion -–> 
    <div class="w-100">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <img src="images/logo1.png" width="70" height="70">
            <a class="navbar-brand" href="#">Creaciones Hilos de Amor</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Tienda <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Carrito(0)</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="text" placeholder="Buscar...">
                    <button class="btn btn-secondary my-2 my-sm-0" type="submit">Buscar</button>
                </form>
            </div>
        </nav>
    </div>
    
<!-– Fin de barra de navegacion -–> 

<!-– Contenido del sitio -–> 

    <div class="container-fluid py-5 px-5">
        <div class="row">
        <?php 
            $sentencia=$pdo->prepare("SELECT * FROM `productos`");
            $sentencia->execute();
            $listaProductos=$sentencia->fetchAll(PDO::FETCH_ASSOC);
            //print_r($listaProductos);
        ?>
        <?php foreach($listaProductos as $producto){  ?>

            <div class="col-3 mb-5">
                <div class="card">
                    <img src="<?php echo $producto['imagen'];?>" title="<?php echo $producto['nombre'];?>" alt="<?php echo $producto['nombre'];?>" class="card-img-top">
                    <div class="card-body p-2">
                        <span><?php echo $producto['nombre'];?></span>
                        <h5 class="card-title text-truncate">Q.<?php echo $producto['precio'];?> </h5>
                        <button class="btn btn-sm btn-success" data-toggle="tooltip" title="Agregar al carrito"> Agregar al carrito </button>
                    </div>
                </div>
            </div>


        <?php } ?>


        </div>
    </div>

<!-– Fin del Contenido del sitio -–> 






</body>
</html>
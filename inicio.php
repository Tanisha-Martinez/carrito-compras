<?php 
    include 'global/config.php';
    include 'global/conexion.php';
    include 'carrito.php';
    include 'templates/cabecera.php';

?>
<!-– Contenido del sitio -–> 
        <?php if($mensaje!="") {?>
        <div class="alert alert-success">
            <?php echo($mensaje); ?>
            <a href="mostrarCarrito.php" class="badge badge-success">Ver carrito</a> 
        </div>
        <?php }?>
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
                    <img src="<?php echo $producto['imagen'];?>" title="<?php echo $producto['nombre'];?>" 
                    alt="<?php echo $producto['nombre'];?>" data-toggle="popover" data-trigger="hover"
                    data-content="<?php echo $producto['descripcion'];?>" 
                    class="card-img-top">
                    <div class="card-body p-2">
                        <span><?php echo $producto['nombre'];?></span>
                        <h5 class="card-title text-truncate">Q.<?php echo $producto['precio'];?> </h5>
                        <p class="card-text">Descripcion</p>

                    <form action="" method="post">
                        <input type="hidden" name="id" id="id" value="<?php echo openssl_encrypt($producto['id'],CODIGO,KEY);?>">
                        <input type="hidden" name="nombre" id="nombre" value="<?php echo openssl_encrypt($producto['nombre'],CODIGO,KEY);?>">
                        <input type="hidden" name="precio" id="precio" value="<?php echo openssl_encrypt($producto['precio'],CODIGO,KEY);?>">
                        <input type="hidden" name="cantidad" id="cantidad" value="<?php echo openssl_encrypt(1,CODIGO,KEY);?>">

                        <button class="btn btn-sm btn-success" data-toggle="tooltip" name="btnAccion" 
                        value="Agregar" type="submit"> Agregar al carrito </button>
                    </form>

                    </div>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>

<!-– Fin del Contenido del sitio -–> 

<script>
    $(function () {
        $('[data-toggle="popover"]').popover()
    });
</script>
<?php include 'templates/pie.php';?>

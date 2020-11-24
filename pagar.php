<?php 
    include 'global/config.php';
    include 'global/conexion.php';
    include 'carrito.php';
    include 'templates/cabecera.php';
?>

<?php 
if($_POST){

    $total=0;
    $sid=session_id();
    $correo=$_POST['email'];

    foreach($_SESSION['carrito'] as $indice=>$producto){
        $total=$total+($producto['precio']*$producto['cantidad']);
        $envio=35;
        $total2=$total+$envio;
    }

        $sentencia=$pdo->prepare("INSERT INTO `ventas` (`id`, `claveTransaccion`, `paypalDatos`, 
                            `fecha`, `correo`, `total`, `status`) 
        VALUES (NULL, :claveTransaccion, '', NOW(), :correo, :total, 'pendiente');");

        $sentencia->bindParam(":claveTransaccion",$sid);
        $sentencia->bindParam(":correo",$correo);
        $sentencia->bindParam(":total",$total2);
        $sentencia->execute();
        $idVenta=$pdo->lastInsertId();

        foreach($_SESSION['carrito'] as $indice=>$producto){

            $sentencia=$pdo->prepare("INSERT INTO 
            `detalleventa` (`id`, `idVenta`, `idProducto`, `precioUnitario`, `cantidad`, `descargado`) 
            VALUES (NULL,:idVenta, :idProducto, :precioUnitario, :cantidad, '0');");
            
            $sentencia->bindParam(":idVenta",$idVenta);
            $sentencia->bindParam(":idProducto",$producto['id']);
            $sentencia->bindParam(":precioUnitario",$producto['precio']);
            $sentencia->bindParam(":cantidad",$producto['cantidad']);
            $sentencia->execute();
        }
    //echo "<h3>".$total2."</h3>";
}
?>

<!-- Include the PayPal JavaScript SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=sb&currency=USD"></script>
<style>
        /* Media query for mobile viewport */
        @media screen and (max-width: 400px) {
            #paypal-button-container {
                width: 100%;
            }
        }
        /* Media query for desktop viewport */
        @media screen and (min-width: 400px) {
            #paypal-button-container {
                width: 250px;
                display: inline-block;
            }
        }
</style>

<div class="jumbotron text-center" >
    <h1 class="display-4">¡Paso final!</h1>
    <hr class="my-4">
    <p class="lead">Estás a punto de pagar con Paypal la cantidad de: 
        <h4>Q.<?php echo number_format($total2,2);?></h4>
        <div id="paypal-button-container"></div>
    </p>
    <p>Los productos podrán ser descargados una vez que se procese el pago.</p>
    <strong>(Para aclaraciones: hilosdeamor@gmail.com)</strong>
</div>

<script>
    // Render the PayPal button into #paypal-button-container
    paypal.Buttons().render('#paypal-button-container');
</script>


<?php include 'templates/pie.php';?>
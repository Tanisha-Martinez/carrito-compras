<?php 
    include 'global/config.php';
    include 'carrito.php';
    include 'templates/cabecera.php';
?>

<br>
<h3> Lista del Carrito</h3>
<?php 
    if(!empty($_SESSION['carrito'])){ ?>

<table class="table table-bordered">
    <tbody>
        <tr>
            <th width=40%>Descripcion</th>
            <th width=15% class="text-center">Cantidad</th>
            <th width=20% class="text-center">Precio</th>
            <th width=20% class="text-center">Total</th>
            <th width=5%>--</th>
        </tr>
        <?php $total=0; ?>
        <?php foreach($_SESSION['carrito'] as $indice=>$producto){ ?>
        <tr>
            <td width=40%><?php echo $producto['nombre'] ?></td>
            <td width=15% class="text-center"><?php echo $producto['cantidad'] ?></td>
            <td width=20% class="text-center">Q.<?php echo $producto['precio'] ?></td>
            <td width=20% class="text-center">Q.<?php echo number_format($producto['precio']*$producto['cantidad'],2); ?></td>
            <td width=5% >

        <form action="" method="post">
            <input type="hidden" name="id" id="id" value="<?php echo openssl_encrypt($producto['id'],CODIGO,KEY);?>">
            <button class="btn btn-danger" type="submit" name="btnAccion"
            value="Eliminar"> Eliminar</button></td>
        </form>    


        </tr>
        <?php $total=$total+($producto['precio']*$producto['cantidad']); ?>
        <?php }?>
        <tr>
            <td colspan="3" align="right"><h3>Total</h3></td>
            <td align="right"><h3>Q.<?php echo number_format($total,2); ?></h3</td>
            <td></td>
        </tr>

    </tbody>
</table>
<?php }else{ ?>
<div class="alert alert-success" role="alert">
    No hay productos en el carrito 
</div>
<?php } ?>

<?php include 'templates/pie.php';?>

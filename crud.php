<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hilos de Amor</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
  
</head>
<body>
<!–- Inicio de barra de navegacion -–> 
    <div class="w-100">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <img src="images/logo1.png" width="70" height="70">
            <a class="navbar-brand" >Creaciones Hilos de Amor</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav form-inline">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Cerrar sesion <span class="sr-only">(current)</span></a>
                    </li>
                    
                </ul>
                
            </div>
        </nav>
    </div>

<?php 
    include 'global/config.php';
    include 'global/conexion.php';

$txtId=(isset($_POST['txtId']))?$_POST['txtId']:"";
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtPrecio=(isset($_POST['txtPrecio']))?$_POST['txtPrecio']:"";
$txtDescripcion=(isset($_POST['txtDescripcion']))?$_POST['txtDescripcion']:"";
$txtImg=(isset($_FILES['txtImg']['name']))?$_FILES['txtImg']:"";

$accion=(isset($_POST['accion']))?$_POST['accion']:"";

$accionAgregar="";
$accionModificar=$accionEliminar=$accionCancelar="disabled";
$mostrarModal=false;

switch($accion){
    
    case "btnAgregar":

        $sentencia=$pdo->prepare("INSERT INTO productos(id, nombre, precio, descripcion, imagen) 
        VALUES (NULL,:nombre,:precio,:descripcion,:imagen)");

        $sentencia->bindParam(':nombre',$txtNombre);
        $sentencia->bindParam(':precio',$txtPrecio);
        $sentencia->bindParam(':descripcion',$txtDescripcion);

        $Fecha = new DateTime();
        $nombreArchivo = ($txtImg!="")?$Fecha->getTimestamp()."_".$_FILES["txtImg"]["name"]:"imagen.png";
        $tmpImagen = $_FILES["txtImg"]["tmp_name"];
        if($tmpImagen!=""){
            move_uploaded_file($tmpImagen,"images/".$nombreArchivo); 
        }

        $sentencia->bindParam(':imagen',$nombreArchivo);
        $sentencia->execute();

        header('crud.php');
    break;

    case "btnModificar":

        $sentencia=$pdo->prepare("UPDATE `productos` SET 
        `id`=:id,
        `nombre`=:nombre, 
        `precio`=:precio, 
        `descripcion`=:descripcion WHERE id=:id");

        $sentencia->bindParam(':nombre',$txtNombre);
        $sentencia->bindParam(':precio',$txtPrecio);
        $sentencia->bindParam(':descripcion',$txtDescripcion);
        $sentencia->bindParam(':id',$txtId);
        $sentencia->execute();

        $Fecha = new DateTime();
        $nombreArchivo = ($txtImg!="")?$Fecha->getTimestamp()."_".$_FILES["txtImg"]["name"]:"imagen.png";
        
        $tmpImagen = $_FILES["txtImg"]["tmp_name"];
        
        if($tmpImagen!=""){
            move_uploaded_file($tmpImagen,"images/".$nombreArchivo); 


            $sentencia=$pdo->prepare("SELECT imagen FROM `productos` WHERE id=:id");
            $sentencia->bindParam(':id',$txtId);
            $sentencia->execute();
            $producto=$sentencia->fetch(PDO::FETCH_LAZY);
            print_r($producto);

        if(isset($producto["imagen"])&&($item['imagen']!="imagen.png")){
            if(file_exists("images/".$producto["imagen"])){               
                unlink("images/".$producto["imagen"]);    
            }
        }

            $sentencia=$pdo->prepare("UPDATE `productos` SET `imagen`=:imagen WHERE id=:id");
            $sentencia->bindParam(':imagen',$nombreArchivo);
            $sentencia->bindParam(':id',$txtId);
            $sentencia->execute();
        }

        header('crud.php');

    break;

    case "btnEliminar":

        $sentencia=$pdo->prepare("SELECT imagen FROM `productos` WHERE id=:id");
        $sentencia->bindParam(':id',$txtId);
        $sentencia->execute();
        $producto=$sentencia->fetch(PDO::FETCH_LAZY);
        print_r($producto);

        if(isset($producto["imagen"])){
            if(file_exists("images/".$producto["imagen"])){

                if($item['imagen']!="imagen.png"){ 
                unlink("images/".$producto["imagen"]);
                }
            }
        }
    
        $sentencia=$pdo->prepare("DELETE FROM `productos` WHERE id=:id");
        $sentencia->bindParam(':id',$txtId);
        $sentencia->execute();
        header('crud.php');
    
        echo "presionaste eliminar";
    break;

    case "btnCancelar":
        header('crud.php');
    break; 

    case "Seleccionar":
        $accionAgregar="disabled";
        $accionModificar=$accionEliminar=$accionCancelar="";
        $mostrarModal=true; 

        $sentencia=$pdo->prepare("SELECT * FROM `productos` WHERE id=:id");
        $sentencia->bindParam(':id',$txtId);
        $sentencia->execute();
        $producto=$sentencia->fetch(PDO::FETCH_LAZY);

    break;
}

    $sentencia = $pdo->prepare("SELECT * FROM `productos` WHERE 1");
    $sentencia->execute();
    $listaProductos=$sentencia->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>
</head>
<body>
    <div class="container">
        <form action="" method="post" enctype="multipart/form-data">

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form'row">
                    <input type="hidden" required name="txtId" value="<?php echo $txtId; ?>" placeholder="" id="txtId" require="">
                    
                    <label for="">Nombre:</label>
                    <input type="text" class="form-control" name="txtNombre" required value="<?php echo $txtNombre; ?>" placeholder="" id="txtNombre" require="">
                    <br>

                    <label for="">Precio:</label>
                    <input type="text" class="form-control" name="txtPrecio" required  value="<?php echo $txtPrecio; ?>"  placeholder="" id="txtPrecio" require="">
                    <br>

                    <label for="">Descripcion:</label>
                    <input type="text" class="form-control" name="txtDescripcion" required value="<?php echo $txtDescripcion; ?>"  placeholder="" id="txtDescripcion" require="">
                    <br>

                    <label for="">Imagen:</label>
                    <?php if($txtImg!="") { ?>
                    <br/>
                        <img class="img-thumbnail rounded mx-auto d-block" width="100px" src="images/<?php echo $txt; ?>"/>
                    <br/>
                    <?php }?>


                    <input type="file" class="form-control" accept="image/*" name="txtImg"  value="<?php echo $txtImg; ?>"  placeholder="" id="txtImg" require="">
                    <br>
                </div>
            </div>
            <div class="modal-footer">
                <button value="btnAgregar" <?php echo $accionAgregar; ?>  class="btn btn-success" type="submit" name="accion">Agregar</button>
                <button value="btnModificar" <?php echo $accionModificar; ?>  class="btn btn-warning" type="submit" name="accion">Modificar</button>
                <button value="btnEliminar" <?php echo $accionEliminar; ?>  class="btn btn-danger" type="submit" name="accion">Eliminar</button>
                <button value="btnCancelar" <?php echo $accionCancelar; ?> class="btn btn-primary" type="submit" name="accion">Cancelar</button>
            </div>
            </div>
        </div>
        </div>
        <br/>
        <br/>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
        Agregar producto +
        </button>
        <br/>
        <br/>

    
        </form>
    <div class="row">
        <table class="table table-hover table-bordered" >
            <thead class="thead-dark">
                <tr>
                    <th>Imagen del producto</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>

        <?php foreach($listaProductos as $producto ) {?>

            <tr>
                <td><img class="img-thumbnail" width="100px" src="images/<?php echo $producto['imagen']; ?>"/> </td>
                <td> <?php echo $producto['nombre']; ?> </td>
                <td> <?php echo $producto['descripcion']; ?> </td>
                <td> <?php echo $producto['precio']; ?> </td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="txtId" value="<?php echo $producto['id']; ?>">
                        <input type="hidden" name="txtNombre" value="<?php echo $producto['nombre']; ?>">
                        <input type="hidden" name="txtPrecio" value="<?php echo $producto['precio']; ?>">
                        <input type="hidden" name="txtDescripcion" value="<?php echo $producto['descripcion']; ?>">
                        <input type="hidden" name="txtImg" value="<?php echo $producto['imagen']; ?>">


                        <input type="submit" value="Seleccionar" class="btn btn-info" name="accion">
                        <button value="btnEliminar" type="submit" class="btn btn-danger" name="accion">Eliminar</button>

                    </form>
                </td>
            </tr>

        <?php } ?>
        </table>
    </div>
    <?php if($mostrarModal){ ?>
        <script>
            $('#exampleModal').modal('show');
        </script>
    <?php } ?>
    </div>
</body>
</html>
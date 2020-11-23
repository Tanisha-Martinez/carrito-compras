<?php 

session_start();

$mensaje="";

if(isset($_POST['btnAccion'])){

    switch($_POST['btnAccion']){
        
        case 'Agregar':
            if(is_numeric( openssl_decrypt($_POST['id'],CODIGO,KEY))){
                $id=openssl_decrypt($_POST['id'],CODIGO,KEY);
                $mensaje.="Id correcto".$id;
            }else{
                $mensaje.="id incorrecto".$id;
            }

            if(is_string( openssl_decrypt($_POST['nombre'],CODIGO,KEY))){
                $nombre=openssl_decrypt($_POST['nombre'],CODIGO,KEY);
                $mensaje.="nombre correcto".$nombre;
            }else{
                $mensaje.="Algo pasa con el nombre ";
            break;
            }

            if(is_numeric( openssl_decrypt($_POST['precio'],CODIGO,KEY))){
                $precio=openssl_decrypt($_POST['precio'],CODIGO,KEY);
                $mensaje.="Ok precio".$precio;
            }else{
                $mensaje.="Algo pasa con el precio ";
            break;
            }

            if(is_numeric( openssl_decrypt($_POST['cantidad'],CODIGO,KEY))){
                $cantidad=openssl_decrypt($_POST['cantidad'],CODIGO,KEY);
                $mensaje.="Ok cantidad".$cantidad;
            }else{
                $mensaje.="Algo pasa con la cantidad ";
            break;
            }

        if(!isset($_SESSION['carrito'])){

            $producto=array(
                'id'      =>$id,
                'nombre'  =>$nombre,
                'precio'  =>$precio,
                'cantidad'=>$cantidad
            );
            $_SESSION['carrito'][0]=$producto;
        }else{
            $numeroProductos=count($_SESSION['carrito']);
            $producto=array(
                'id'      =>$id,
                'nombre'  =>$nombre,
                'precio'  =>$precio,
                'cantidad'=>$cantidad
            );
            $_SESSION['carrito'][$numeroProductos]=$producto;
        }
        $mensaje= print_r($_SESSION,true);

        break;

        case 'Eliminar':
            if(is_numeric(openssl_decrypt($_POST['id'],CODIGO,KEY))){
                $id=openssl_decrypt($_POST['id'],CODIGO,KEY);

                foreach($_SESSION['carrito'] as $indice=>$producto){
                    if($producto['id']==$id){
                        unset($_SESSION['carrito'][$indice]);
                        echo "<script> alert('Elemento borrado...');</script>";
                    }
                }

            }else{
                $mensaje.="Id incorrecto".$id."<br>";

            }
        
        break;
    }
}

?>
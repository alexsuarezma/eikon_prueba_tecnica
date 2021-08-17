<?php
    require_once('Models/Bodega.php');

    session_start();

    try {
        
        if(isset($_POST['enviar'])){
            $bodega = new Bodega(0,0);
            if(isset($_POST['bodegaIdEmisor']) && $_POST['bodegaIdReceptor']){
                unset($_SESSION['mensaje']);
                $bodega->moverCajasABodegas($_SESSION['bodegas'], $_POST['bodegaIdEmisor'], $_POST['numeroCajas'], $_POST['bodegaIdReceptor']);
            }else{
                throw new Exception('Debe seleccionar Bodega origen, y Bodega destino',1);
            }
            
        }else{
            $_SESSION['bodegas'] = [];
            
            $bodega1 = new Bodega(1,30);
            $bodega2 = new Bodega(2,20);
            $bodega3 = new Bodega(3,25);
            $bodega4 = new Bodega(4,10);
            
            array_push($_SESSION['bodegas'], $bodega1);
            array_push($_SESSION['bodegas'], $bodega2);
            array_push($_SESSION['bodegas'], $bodega3);
            array_push($_SESSION['bodegas'], $bodega4);
        }
    } catch (\Exception $error) {
        $_SESSION['mensaje'] = $error->getMessage();
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eikon - Tarea 3</title>
</head>
<body>
    <table style="border: 1px solid black;">
        <thead>
            <th>Bodega Id</th>
            <th>Cajas</th>
        </thead>
        <tbody>
            <?php if(isset($_SESSION['bodegas'])): ?>
                <?php foreach($_SESSION['bodegas'] as $bodega): ?>
                    <tr>
                        <td><?=$bodega->getBodegaId()?></td>
                        <td><?=$bodega->getCajas()?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <div style="margin-top: 30px;"></div>
    <hr>
    <div style="color:red; margin-bottom: 10px; ">
        <?=isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : ''?>
    </div>
    <form action="index.php" method="post">
        <label for="bodegaIdEmisor">Bodega Origen:</label>
        <select id="tipo" name="bodegaIdEmisor" required>
            <option selected disabled>Seleccione...</option>
            <?php if(isset($_SESSION['bodegas'])): ?>
                <?php foreach($_SESSION['bodegas'] as $bodega): ?>
                    <option value="<?=$bodega->getBodegaId()?>"><?=$bodega->getBodegaId()?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
        
        <label for="numeroCajas">Cajas A Egresar:</label>
        <input type="text" name="numeroCajas" required>

        <label for="bodegaIdReceptor">Bodega Destino:</label>
        <select id="tipo" name="bodegaIdReceptor" required>
            <option selected disabled>Seleccione...</option>
            <?php if(isset($_SESSION['bodegas'])): ?>
                <?php foreach($_SESSION['bodegas'] as $bodega): ?>
                    <option value="<?=$bodega->getBodegaId()?>"><?=$bodega->getBodegaId()?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
        <br>
        <hr>
        <button style="margin-top: 10px;"  type="submit" name="enviar">Trasladar inventario</button>    
    </form>
</body>
</html>
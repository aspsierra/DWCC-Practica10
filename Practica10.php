<?php

function borraPosicion($arArchivos, $posicion){
    foreach ($arArchivos as $key => $prop) {
        unset($prop[$posicion]);
        $arArchivos[$key]= $prop;
    }
    return $arArchivos;
}

if (isset($_POST["enviar"])) {
    try {
        $archivos=$_FILES['files'];
        
        //compruebo si existe ['files'] dentro del array superglobal $_FILES
        if(!isset(($archivos['error']))){
            throw new RuntimeException('Error en el envío de los ficheros');
        }
      
        //Como los archivos se reciben en un array, hago un foreach para comprobar que no haya errores
        $errEnvio="";
        foreach ($archivos['error'] as $key => $error) {
            switch ($error) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $archivos = borraPosicion($archivos, $key);
                    $errEnvio .= 'No se recibió el archivo '.$key . '<br>';
                    break;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:     
                    $archivos = borraPosicion($archivos, $key);
                    $errEnvio .= 'El archivo '.$key.' sobrepasa el tamaño máximo'. '<br>';
                    break;
                default:
                    $archivos = borraPosicion($archivos, $key);
                    $errEnvio .= 'Error desconocido para el archivo '.$key. '<br>';             
                }                      
        }   
        //para que me muestre los errores individuales los guardo en una variable y luego muestro el error
        echo $errEnvio;
        
        //creo una variable que usaré para cpmprobar el Mime type de los ficheros enviados
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        
        $extension = array();
        
        //compruebo cuales son las extensiones de cada uno de los archivos enviados
        foreach ($archivos['tmp_name'] as $key => $fichero) {
            $extension[$key]= array_search(finfo_file($fileInfo, $fichero), array('jpg'=>'image/jpeg','png'=>'image/png','gif'=>'image/gif','pdf'=>'application/pdf'));                       
        }
        //cierro el finfo ya que no lo voy a seguir usando
        finfo_close($fileInfo);
        
        //si en alguna posición hay un false, quiere decir que el tipo de archivo no se corresponde
        foreach ($extension as $key => $value) {
            if ($value === false){
                $archivos = borraPosicion($archivos, $key);
                echo 'archivo ' . $key . ' no reconocido'."<br>";              
            }
        }
        
        $errDir = "";
        //creo el directorio en caso de que no exista
        if(!file_exists('./practica10/')){
            mkdir('./practica10');
        }
        //subo cada fichero      
        foreach ($archivos['tmp_name'] as $key => $fichero) {
            $res = move_uploaded_file($fichero, "./practica10/file" . $key . "." . $extension[$key]);
            if(!$res){
                echo "El fichero ".$key." no pudo ser cambiado de dirección"."<br>";
            } else {
                echo $archivos['name'][$key]. " subido correctamente<br>";
            }
        }
        
    } catch (RuntimeException $ex) {
        echo $ex->getMessage();
    }
}
?>




<html>
    <head>
        <title>Práctica 10</title>		
        <meta charset = "UTF-8">
        
    </head>
    <body>
        <form enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <p>Ficheiros a enviar: </p>
            
            <div>
                <input type="file" name="files[]" value="<?php if(isset($_FILES['files']))echo $_FILES['files']['name'][0];?>"/>
            </div>          
            <br>            
            <input name="enviar" type="submit" value="Enviar" /> 
            
        </form>
        <button id = "agregar">Agregar campo</button>
        <button id = "borrar">Borrar campo</button>
    </body>
    <footer>
        <script src="./main.js"></script>
    </footer>
</html>
<?php

if (isset($_POST["enviar"])) {
    try {
        //compruebo si existe ['files'] dentro del array superglobal $_FILES
        if(!isset(($_FILES['files']['error']))){
            throw new RuntimeException('Error en el envío del fichero ');
        }
        
        //Como los archivos se reciben en un array, hago un foreach para comprobar que no haya errores
        $errEnvio="";
        foreach ($_FILES['files']['error'] as $key => $error) {
            switch ($error) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:                   
                    $errEnvio .= 'No se recibió el archivo '.$key . '<br>';
                    break;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:               
                    $errEnvio .= 'El archivo '.$key.' sobrepasa el tamaño máximo'. '<br>';
                    break;
                default:
                     $errEnvio .= 'Error desconocido para el archivo '.$key. '<br>';             
                }                      
        }      
        //para que me muestre los errores individuales los guardo en una variable y luego muestro el error
        if ($errEnvio != ""){
            throw new RuntimeException($errEnvio);
        }
        
        //Mismo caso que el anterior pero con el tamaño
        $errSize = "";        
        foreach($_FILES["files"]["size"] as $key => $size){
            if($size > 50000000){
                $errSize .= 'El tamaño de la imagen '.$key.'es mayor de lo permitido'. '<br>';
            }
        }
        //muestro los errores de cada imagen
        if($errSize != ""){
            throw new RuntimeException($errSize);
        }
        
        //creo una variable que usaré para cpmprobar el Mime type de los ficheros enviados
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        
        $extension = array();
        
        //compruebo cuales son las extensiones de cada uno de los archivos enviados
        foreach ($_FILES['files']['tmp_name'] as $key => $fichero) {
            $extension[$key]= array_search(finfo_file($fileInfo, $fichero), array('jpg'=>'image/jpeg','png'=>'image/png','gif'=>'image/gif','pdf'=>'application/pdf'));                       
        }
        //cierro el finfo ya que no lo voy a seguir usando
        finfo_close($fileInfo);
        
        //si en alguna posición hay un false, quiere decir que el tipo de archivo no se corresponde
        foreach ($extension as $key => $value) {
            if ($value === false){
                throw new RuntimeException('archivo ' . $key . ' no reconocido');
            }
        }
        
        $errDir="";
        //creo el directorio en caso de que no exista
        if(!file_exists('./practica10/')){
            mkdir('./practica10');
        }
        //subo cada fichero      
        foreach ($_FILES['files']['tmp_name'] as $key => $fichero) {
            $res = move_uploaded_file($fichero, "./practica10/file" . $key . "." . $extension[$key]);
            if(!$res){
                $errDir .= "El fichero ".$key." no pudo ser cambiado de dirección"+"<br>";
            }
        }
        if ($errDir!=""){
            throw new RuntimeException($errDir);
        }
        
        //si todo sale bien, muestro esto
        echo "Archivos subidos correctamente";
        
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
                <input type="file" name="files[]" />
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
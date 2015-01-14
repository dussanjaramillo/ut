<?php

//Incluimos la libreria

include('pclzip.lib.php');

  

$tipo_archivo=$HTTP_POST_FILES['archivo']['type'];

$tamanoKB = $HTTP_POST_FILES['archivo']['size']/1024;

$name=$HTTP_POST_FILES['archivo']['name'];



if ((strpos($tipo_archivo, "x-zip-compressed")) && ($tamanoKB < 1000)) {

          

//nombre para la carpeta

$ran=rand(0,1000);

   $nom="m".$ran;

   mkdir("data/".$nom);

  

//forma de llamar la clase

$archive = new PclZip($name);



//Ejecutamos la funcion extract



if ($archive->extract(PCLZIP_OPT_PATH, 'data/'.$nom."/",

                        PCLZIP_OPT_REMOVE_PATH, 'temp_install') == 0) {

     die("Error : ".$archive->errorInfo(true));

   }

  

}else{



   echo "archivo no Valido. Solo son permitidos archivos *.zip, con un tamaño de 1Mb Maximo";

  

}

?> 
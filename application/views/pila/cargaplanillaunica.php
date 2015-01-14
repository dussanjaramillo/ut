<div id="planilla"><h1>CARGUE ARCHIVOS PLANILLA ÚNICA</h1></div>
<table align="center">
    <tr>
        <td align="center"><h5>Seleccione la opcion deseada</h5></td>
    </tr>
</table>
    <table align="center">
    <tr>
        <td style="width: 5px"><input type="radio" name="radio" id="radio1" class='opcion' value='1'></td>
        <td>Cargar archivos</td>
    </tr>
     <tr >
        <td><input type="radio" name="radio" id="radio2" class='opcion' value='2'></td>
        <td>Cargar archivos de ajuste</td>
    </tr>
     <tr >
        <td><input type="radio" name="radio" id="radio3" class='opcion' value='3'></td> 
        <td>Consultar resultados de cargue</td>
    </tr>
    </table>
    <table align="center">
    <tr>
        <td><button type="button" id="aceptar" class="aceptar btn btn-success">Aceptar</button></td>
        <td><button type="button" id="cancelar" class="cancelar btn btn-success">Cancelar</button></td>
    </tr>
</table> 
<div id='alerta' align='center'></div>
<div id='cargaarchivos'></div>
<input type='hidden' id='guardaropcion'>
<script>
    
    $('#aceptar').click(function(){
       var guardar = $('#guardaropcion').val();
       if(guardar == ''){
           $('#alerta').append('<b style="color: red">Favor escoger una opción</b>');           
       }
       if(guardar == 1){
           var url = "<?= base_url('index.php/pila/abrirplanilla') ?>";
            $('#cargaarchivos').load(url);   
            
       }
    });
    $('.opcion').click(function(){
       var opcion = $(this).val(); 
       $('#guardaropcion').val(opcion);
       $('#alerta *').remove();
    });
    
    
</script>
<?php
$zip_manager = new Zip_manager();
$archivo_zip = "archivo.zip"; // aqui el nombre del archivo a extraer 
$explode_carpeta = explode(".zip", $archivo_zip);  // Es lo que hace es quitarle el .zip
$carpeta_final = $explode_carpeta[0];  // un simple explode... 
$listado = $zip_manager->listar($archivo_zip); 
print_r($listado);
$resultado = $zip_manager->extraer($archivo_zip, $carpeta_final); // aqui pirmero el nombre del archivo y despues la carpeta del destino final.
if (!$resultado){
echo "Error: no se ha podido extraer el archivo";
}
else{
echo "<br>Archivo extraido con exito";
}
?>
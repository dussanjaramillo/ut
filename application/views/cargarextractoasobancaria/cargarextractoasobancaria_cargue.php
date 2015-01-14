<script type="text/javascript" language="javascript" charset="utf-8">
function ver(){
      document.getElementById('ruta').value = document.getElementById('ruta').value + "-" + document.getElementById('archivo').value;
}
</script>

<?php
if (isset($message)){
    echo $message;
   }
?>


<form method="post" action="cargarextractoasobancaria/cargue" enctype="multipart/form-data">

¡Sube tu Archivo!: <input type="file" name="uploads[]" multiple>
<br></br>
<a onClick="ver();">ver</a>
<br></br>
<br></br>
<input type="submit" name="enviar" value="Enviar" />
 
 <br></br>
<br></br>
<textarea rosw="7" colums="20" readOnly="readOnly" id="ruta" name="ruta" value=""></textarea>
</form>


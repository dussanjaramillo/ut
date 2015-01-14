<?php
if ($titulo_encabezado === '0') {
    $Cabeza = '
        <br>
        <table width="100%" border="0" align="center">
        <tr>
            <td width="23%" rowspan="2"><div align="center"><img src="' . base_url('img/Logotipo_SENA.png') . '" width="200" height="200" /></div></td>
        </tr>
        </table>    
    ';
} else {
    $Cabeza = '
        <br>
        <table width="100%" border="0" align="center">
        <tr>
            <td width="23%" rowspan="2"><img src="' . base_url('img/Logotipo_SENA.png') . '" width="200" height="200" /></td>
        </tr>
        <tr>
          <td height="50" colspan="2"><div align="center">
            <h3 style="color: black">"' . $titulo_encabezado . '"</h3>
          </div></td>
        </tr>
        </table>    
    ';
}

echo $Cabeza;

print_r($documento);
?>

<script>
    $('#resultado').dialog({
        autoOpen: true,
        width: 900,
        height: 570,
        modal: true,
        title: "Expediente Jurisdicción Coactivo",
        close: function() {
            $('#resultado *').remove();
        }
    });
</script>
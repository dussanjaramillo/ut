<?php
if (isset($message)) {
    echo $message;
}
//$self = $_SERVER['PHP_SELF']; //Obtenemos la página en la que nos encontramos
//header("refresh:200; url=$self"); //Refrescamos cada 300 segundos

?>

<span><h4 style="color:#5bb75b; text-align: center;">Procesos Jurídicos</h4></span>
<table id="tabla1">
    <thead>
        <tr>
            <th>Número Proceso</th>
            <th>Identificación Ejecutado</th>
            <th>Nombre Ejecutado</th>
            <th>Regional</th>
            <th>Responsable</th>
            <th>Estados</th>
            <th>En Gestión por </th>
            <th>Trazabilidad</th>
            <th>Documentos</th>
        </tr>
    </thead> 
    <tbody>
        <?php
        if ($consulta) {
            $m = 0;
            foreach ($consulta as $data) {
                ?> 
                <tr> 
                    <td><?php echo $data['COD_PROCESO']; ?></td>
                    <td><?php echo $data['NIT']; ?></td>
                    <td><?php echo $data['NOMBRE_EMPRESA']; ?></td>
                    <td><?php echo $data['NOM_REGIONAL']; ?></td>
                    <td><?php echo strtoupper($data['NOMBRES'] . " " . $data['APELLIDOS']); ?></td>
                    <td>     
                        <?php
                        $cantidad = count($data['ABOGADO']);
                        if ($cantidad > 0):
                            $cod_abogado = $data['ABOGADO'];
                        else:
                            $cod_abogado = 0;
                        endif;
                        ?>
                        <select name="estados" id="estados"  onchange="f_enviar(this.value, '<?php echo $m; ?>', '<?php echo $data['NIT'] ?>', '<?php echo $cod_abogado ?>', '<?php echo $data['COD_FISCALIZACION'] ?>');" >
                            <option value="0">Seleccione el Estado</option>
                            <?php
                            $estados = explode(",", $data['CODIGOS RESPUESTAS']);
                            $nombre = explode(",", $data['RESPUESTAS UNIDAS']);
                            for ($i = 0; $i < count($estados); $i++):
                                ?>
                                <option value="<?php echo $estados[$i]; ?>"><?php echo $nombre[$i]; ?></option>
                                <?php
                            endfor;
                            ?>
                        </select> 
                        <div class="preload" id="preload" style="display:none">
                            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
                        </div>
                    </td>
                    <td>  <div id="<?php echo "responsable_" . $m; ?>"></div></td>
                    <td> <form name="form1" id="form1" method="post" target="_blank" action="<?php echo base_url() . 'index.php/consultarprocesos/actualizatrazajuridico' ?>">
                            <input type="hidden" id="FISCALIZACION" name="FISCALIZACION" value="<?php echo $data['COD_PROCESO']; ?>" />
                            <input type="submit" class="btn btn-info" name="Abrir" id="Abrir"  value="Abrir">
                        </form>
                    <td>
                        <form name="form1" id="form1" method="post" target="_blank" action="<?php echo base_url() . 'index.php/expedientes/documentos_proceso' ?>">
                            <input type="hidden" name="codFiscalizacion" id="codFiscalizacion" value="<?php echo $data['COD_FISCALIZACION'] ?>" />
                            <input type="hidden" name="cod_empresa" id="cod_empresa" value="<?php echo $data['NIT'] ?>" />
                            <input type="hidden" name="codigo_pj" id="codigo_pj" value="<?php echo $data['COD_PROCESO'] ?>" />
                            <button name="boton" type="submit" > <i class="fa fa-folder-open-o" ></i></button>
                        </form>
                    </td>
                </tr>
                <?php
                $m++;
            }
        }
        ?>
    </tbody>      
</table>
<script type="text/javascript" language="javascript" charset="utf-8">
    function f_enviar(cod_respuesta, i, nit, cod_abogado, cod_fiscalizacion)
    {
        $("#preload").show();
        var url = "<?= base_url("index.php/bandejaunificada/responsable") ?>";
        $.post(url, {cod_respuesta: cod_respuesta, nit: nit, cod_abogado: cod_abogado, cod_fiscalizacion: cod_fiscalizacion}, function(data) {
            $("#responsable_" + i).html(data);
            $("#preload").hide();
        })
    }
    $('#tabla1').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "sServerMethod": "POST",
        "oLanguage": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "fnInfoCallback": null,
        },
    });
</script>



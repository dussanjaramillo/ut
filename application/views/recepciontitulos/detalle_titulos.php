<?php
if (isset($message)) {
    echo $message;
}
?>
<span><h4 style="color:#5bb75b; text-align: center;">Titulos Ejecutivos</h4></span>
<div style="vertical-align: center;">
</div>
<form name="form1" method="post" id="form1" action="<?php echo $ruta ?>">
    <table id="tabla1">
        <thead>
            <tr><th>Expediente</th>
                <th>Identificación</th>
                <th>Ejecutado</th>
                <th>Fecha Carga</th>
                <th>Nombre Documento</th>
                <th>Documento</th>

            </tr>
        </thead> 
        <tbody>

            <?php
            foreach ($consulta as $data) {
                ?> 
                <tr>   
                    <td><?php echo $data['COD_PROCESO'] ?> 
                    <td><?php echo $data['IDENTIFICACION'] ?> 
                    </td>
                    <td><?php echo $data['NOMBRE'] ?></td>

                    <td><?php echo $data['FECHA_CARGA'] ?></td>
                    <td><?php echo $data['NOMBRE_DOCUMENTO'] ?></td>
                    <td>
                        <a href="<?php echo $data['RUTA_ARCHIVO'] ?>"  target="_blank" class="linkli">Ver</a> 
                        <input type="hidden" name="id_titulo[]" id="id_titulo" value="<?php echo $data['COD_TITULO'] ?>" />

                    </td>


                </tr>
            <?php } ?>


        </tbody>      
    </table>
    <div style="margin-top:10px;">
        <span style="text-align: center; color:#5bb75b; font-size:14px;">Observaciones</span><br>
        <textarea name="observaciones" id="observaciones" style="width:700px; height:100px;  "></textarea>
        <span><br>
            <select name="estado" id="estado" style="width:120px; ">
                <option value=""></option>    
                <option value="886">Completo</option>
                <option value="171">Incompleto</option><!--Titulo Devuelto -->

            </select>  
            <input type="hidden" name="id_recepcion" id="id_recepcion" value="<?php echo $data['COD_RECEPCIONTITULO'] ?>" />
            <input type="submit" name="Guardar" id="Guardar" value="Guardar" class=" btn btn-success">
        </span>
    </div>
</form>

<div class="Comentarios" style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <?php
    echo form_label('<b><h3>Historial de Comentarios</h3></b><span class="required"></span>', 'lb_comentarios');
    echo "<br>";
    if ($comentarios) {
        for ($i = 0; $i < sizeof($comentarios); $i++) {
//        for ($j = 0; $j < sizeof($cc); $j++) {
            echo '<div class="alert-success" style="border-color: black; border: 1px solid grey;padding: 15px 50px 0">';
            echo '<b>Fecha del Comentario: </b>' . $comentarios[$i]["FECHA_RECIBIDO"] . '<br>';
            //echo '<b>Hecho Por: </b>' . $comentario[$i][$j]["NOMBRES"] . " " . $comentario[$i][$j]["APELLIDOS"] . '<br>';
            echo $comentarios[$i]["OBSERVACIONES"];
            echo "<br><br>";
            echo '</div>';
            echo "<br>";
            // }
        }
    }
    ?>

</div>

<div style="text-align: center;padding: 5px 0px 0px 0px;"   >
    <form id="form2" name="form2"  method="post" action="<?php echo $this->data['ruta_titulos']; ?>">
        <input type="hidden" name="cod_titulo" id="cod_titulo" value="<?php echo $proceso; ?>">
        <input type="submit" name="cancelar" id="cancelar" class="btn btn-warning"value=" Cancelar">
    </form>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
    $('#tabla1').dataTable({
        "bJQueryUI": true,
        "sServerMethod": "POST",
        "sPaginationType": "full_numbers",
        "oLanguage": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ning�n dato disponible en esta tabla",
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
                "sLast": "�ltimo",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        }
    });




</script> 

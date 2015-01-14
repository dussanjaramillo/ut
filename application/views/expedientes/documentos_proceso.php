<?php
if (isset($message)) {
    echo $message;
}
//echo "<pre>";
//print_r($consulta);
//echo "</pre>";
//echo "---------";
//echo "<pre>";
//print_r($cabecera);
//echo "</pre>";
?>

<span><h4 style="text-align: center;">Documentos Procesos Jurídico </h4></span>
<table id="tabla" style="width:auto">
    <tr>
        <td><span  style="color:red"> COD PROCESO</span></td> 
        <td><span> <input type="text" readonly="readonly" style="text-align: left" value="<?php
                if (isset($consulta[0]['COD_PROCESO'])): echo $consulta[0]['COD_PROCESO'];
                else: echo $cabecera['COD_EXPEDIENTE_JURIDICA'];
                endif;
                ?>"></span></td>
        <td>

            <span style="color:red; margin-left: 10px; "> CONCEPTO</span>  </td>
        <td>
            <span  ><input  readonly="readonly" type="text"  value=" <?php echo $cabecera['CONCEPTO'] ?>"></span>
        </td>
    </tr>
    <tr> <td>
            <span style="color:red;  margin-left: 10px; "> NOMBRE EJECUTADO:</span>  </td>
        <td>
            <span  ><input  readonly="readonly"  class="input-xxlarge" type="text" title=""  value=" <?php echo $cabecera['EJECUTADO'] ?>"></span>
        </td>
        <td> <span  style="color:red "> IDENTIFICACIÓN EJECUTADO:</span> </td>
        <td>   <span>    <input type="text" readonly="readonly"  value=" <?php echo $cabecera['IDENTIFICACION'] ?>"></span>
        </td>

    </tr>
    <tr>

         <td>
            <span  style="color:red"> TELÉFONO:</span>  </td>
        <td>
            <span>    <input type="text" readonly="readonly"  value="<?php echo $cabecera['TELEFONO'] ?>"></span>
        </td>
        <td>
            <span style="color:red;  margin-left: 10px; " >DIRECCIÓN:</span>  </td>
        <td>
            <span><input  readonly="readonly" type="text"  value=" <?php echo $cabecera['DIRECCION'] ?>"></span>
        </td>

    </tr>
<!--
    <tr>
        <td><span  style="color:red; "> INSTANCIA</span></td>
        <td><span> <input type="text" class="input-xxlarge" readonly="readonly"  title="" value="<?php echo $cabecera['PROCESO'] ?>"></span></td>
               <td>
            <span style="color:red;  margin-left: 10px; " > ESTADO</span>  </td>
        <td>
            <span  ><input  readonly="readonly" class="input-xxlarge" type="text" title=""  value=" <?php echo $cabecera['RESPUESTA']; ?>"></span>
        </td>

    </tr>-->
</table>
<table id="tabla1">
    <thead>
        <tr>

            <th>proceso</th>
            <th>Estado</th>
            <th>Número Radicado</th>
            <th>Fecha Radicado</th>
            <th>Número Resolución</th>
            <th>Fecha Resolución</th>
            <th>Fecha Notificación Efectiva</th>
            <th>Responsable</th>
            <th>Nombre Documento</th>
            <th>Fecha Documento</th>
            <th>Documento</th>
        </tr>
    </thead> 
    <tbody>
        <?php
        if ($consulta) {
            foreach ($consulta as $data) {
                ?> 
                <tr> 

                    <td><?php echo $data['NOMBRE_PROCESO'] ?></td>
                    <td><?php echo $data['RESPUESTA'] ?></td>
                    <td><?php echo $data['NUMERO_RADICADO'] ?></td>
                    <td><?php echo $data['FECHA_RADICADO'] ?></td>
                    <td><?php echo $data['FECHA_RESOLUCION'] ?></td>
                    <td><?php echo $data['NUMERO_RESOLUCION'] ?></td>
                    <td><?php echo $data['FECHA_NOTIFICACION_EFECTIVA'] ?></td>
                    <td><?php echo $data['NOMBRES'] . " " . $data['APELLIDOS'] ?></td>
                    <td><?php echo $data['NOMBRE_DOCUMENTO'] ?></td>
                    <td><?php echo $data['FECHA_DOCUMENTO'] ?></td>
                    <td>
                        <a href="<?php echo base_url() . $data['RUTA_DOCUMENTO'] ?>" title="ver" target="_blank"> <i class="fa fa-book fa-lg " ></i></a>
                    </td>


                </tr>
                <?php
            }
        }
        ?>


    </tbody>      
</table>


</form>
<script type="text/javascript" language="javascript" charset="utf-8">
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

    function f_buscar()
    {
        var num_documento = $("#num_documento").val();
        var num_proceso = $("#num_proceso").val();

    }
    //  window.history.forward(-1);
</script> <?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




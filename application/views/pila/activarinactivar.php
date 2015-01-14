<div id="activar" align="center"><h3>ACTIVAR / INACTIVAR PERIODOS</h3></div>
<table align="center">
    <tr>
        <td><h5>Identificación:</h5></td>
        <td style="width: 100px;"><?= $nit ?></td>
        <td><h5>Razón Social:</h5></td>
        <td><?= $razonsocial ?></td>
    </tr>
</table>
<table align="center" style="border: 2px solid; border-color: gray">
    <tr>
        <td>Numero radicado de Documento</td>
        <td align="center"><input type="text" name="radicado" id="radicado" class="obligatorio"></td>
    </tr>
    <tr>
        <td >Fecha del documento</td>
        <td align="center"><input type="date" name="fechadocu" id="fechadocu" class="obligatorio"></td>
    </tr>
    <tr>
        <td>Nombre informante</td>
        <td align="center"><input type="text" name="nominformante" id="nominformante" class="obligatorio"></td>
    </tr>
    <tr>
        <td>Cargo del informante</td>
        <td align="center">
            <select name="cargoinformante" id="cargoinformante" class="obligatorio" required="required">
                <option value="">... Seleccione ...</option>
                <option value="Contador">Contador</option>
                <option value="Revisor Fiscal">Revisor Fiscal</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>Regional del informante</td>
        <td align="center">
            <select name="regionalinformante" id="regionalinformante" class="obligatorio" required="required">
                <option value="">... Seleccione ...</option>
                <?php foreach ($regional->result_array() as $value) {
                          echo '<option value="'.$value['COD_REGIONAL'].'">'.$value['NOMBRE_REGIONAL'].'</option>';
                      }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center"><input type="checkbox" name="radio" id="exonerar" class='opcion'>    Exonerado del impuesto sobre la renta CREE<br></td>
    </tr>
    <tr>
        <td colspan="2" align="center" ><button type='button' id="campoobligado" class='btn btn-success'> Guardar</button></td>
    </tr> 
</table>
<br>

<input type="hidden" value="<?= $nit ?>" name="nit" id="nit">

<form  method="post" action="<?= base_url('index.php/pila/periodosactivarinactivar') ?>">  
    <input type='hidden' id='nrad' name='nrad'>    
    <input type='hidden' id='nitempresa' name='nitempresa' value="<?= $nit ?>">    
    <div id='tablaperiodo' >
        <table id="estilo" style="">
            <thead><tr bgcolor="#008000" style="color: #ffffff">
                    <th><b>Año</b></th>
                    <th><b>Enero</b></th>
                    <th><b>Febrero</b></th>
                    <th><b>Marzo</b></th>
                    <th><b>Abril</b></th>
                    <th><b>Mayo</b></th>
                    <th><b>Junio</b></th>
                    <th><b>Julio</b></th>
                    <th><b>Agosto</b></th>
                    <th><b>Septiembre</b></th>
                    <th><b>Octubre</b></th>
                    <th><b>Noviembre</b></th>
                    <th><b>Diciembre</b></th>
                </tr></thead><tbody>
                <?php
                $year = date('Y');

                for ($i = $year - 5; $i < $year + 1; $i++) {
                    if (!empty($anosactivos[$i])) {
                        echo "<tr><td align='center'><b>" . $i . "</b></td>";
                        for ($h = 1; $h <= 12; $h++) {
                            ?>
                        <td align="center">
                            <input type="checkbox" value="<?= $i ?>" <?php if (array_key_exists($h, $anosactivos[$i])) echo $h . "  "; ?><?php if (array_key_exists($h, $anosactivos[$i])) echo "checked"; ?> name="<?= $h ?>[]"  class="indicador" ano="<?= $i ?>">
                        </td>
                        <?php
                    }
                    echo "</tr>";
                }else {
                    echo "<tr><td align='center'><b>" . $i . "</b></td>";
                    for ($h = 1; $h <= 12; $h++) {
                        ?>
                        <td align="center">
                            <input type="checkbox" value="<?= $i ?>"  name="<?= $h ?>[]"  class="indicador" ano="<?= $i ?>">
                        </td>
                        <?php
                    }
                }
            }
//             $anosactivos = $anosactivos;   
//
//                    if (array_key_exists($i, $anosactivos)) {
//                        foreach ($anosactivos as $ano => $meses) {
//                            if ($ano == $year) {
//                                $i = "";
//                                foreach ($meses as $mesesactivos) {
//                                           $i .= $mesesactivos.",";
//                                    }
//                            }
//                        }
//                        echo $i."******"."<br>";
//                    }
            ?> 
<!--                <tr>

<td align="center"><b><?= $i ?></b></td>
<td align="center"><input type="checkbox" value="<?= $i ?>" name="1[]"  class="indicador" ano="<?= $i ?>"></td>
<td align="center"><input type="checkbox" value="<?= $i ?>" name="2[]" class="indicador" ano="<?= $i ?>"></td>
<td align="center"><input type="checkbox" value="<?= $i ?>" name="3[]" class="indicador" ano="<?= $i ?>"></td>
<td align="center"><input type="checkbox" value="<?= $i ?>" name="4[]" class="indicador" ano="<?= $i ?>"></td>
<td align="center"><input type="checkbox" value="<?= $i ?>" name="5[]" class="indicador" ano="<?= $i ?>"></td>
<td align="center"><input type="checkbox" value="<?= $i ?>" name="6[]" class="indicador" ano="<?= $i ?>"></td>
<td align="center"><input type="checkbox" value="<?= $i ?>" name="7[]" class="indicador" ano="<?= $i ?>"></td>
<td align="center"><input type="checkbox" value="<?= $i ?>" name="8[]" class="indicador" ano="<?= $i ?>"></td>
<td align="center"><input type="checkbox" value="<?= $i ?>" name="9[]" class="indicador" ano="<?= $i ?>"></td>
<td align="center"><input type="checkbox" value="<?= $i ?>" name="10[]" class="indicador" ano="<?= $i ?>"></td>
<td align="center"><input type="checkbox" value="<?= $i ?>" name="11[]" class="indicador" ano="<?= $i ?>"></td>
<td align="center"><input type="checkbox" value="<?= $i ?>" name="12[]" class="indicador" ano="<?= $i ?>"></td>
</tr>-->
            <?php
//}
            ?>    
            </tbody>
        </table>
    </div>    
</form>
<br>
<table align="center">
    <tr>
        <td><button type='button' id="guardar1" class="btn btn-success">Guardar</button></td>
    </tr>
</table>
<div id='datosactivar' display='none'  class="modal hide fade modal-body">

</div>
<script type="text/javascript">
    $('#guardar1').hide();
    $('#campoobligado').click(function() {

        var nit = "<?= $nit ?>";
        var radicado = $('#radicado').val();
        var fechadocu = $('#fechadocu').val();
        var nombre = $('#nominformante').val();
        var cargo = $('#cargoinformante').val();
        var regional = $('#regionalinformante').val();
        
        $('#nrad').val(radicado);

        if ($('#exonerar').is(':checked') == true)
        {
            var exonerar = 1;
        }
        else {
            var exonerar = 2;
        }

        var url = "<?= base_url('index.php/pila/guardacampoinactivar') ?>";

        $.post(url, {radicado: radicado, fechadocu: fechadocu, nombre: nombre, cargo: cargo, regional: regional, nit: nit, exonerar: exonerar}, function(data) {
            if (data != 1) {
                var tabla = '<table id="tablainformativa" style="width : 400px">';
                $.each(data, function(key, val) {
                    tabla += '<tr><td style="background-color:red; color: white;" align="center">El Numero de radicado ya ha sido guardado con los siguientes datos</td><tr>'
                    tabla += '<tr><td style="background-color:green; color: white;" align="center">No Radicado</td></tr>';
                    tabla += '<tr><td>' + val.NRO_RADICADO + '</td></tr>';
                    tabla += '<tr><td style="background-color:green; color: white;" align="center">Fecha del Documento</td></tr>';
                    tabla += '<tr><td>' + val.FECHA_DOCUMENTO + '</td></tr>';
                    tabla += '<tr><td style="background-color:green; color: white;" align="center">Nombre Informante</td></tr>';
                    tabla += '<tr><td>' + val.NOMBRE_INFORMANTE + '</td></tr>';

                });
                tabla += '</table>';

                if ($('#tablainformativa').length == 0)
                    $('#datosactivar').append(tabla);

                $('.modal-body').css({
                    'width': '420px',
                    "position": "fixed",
                    "height": 'auto',
                    //                "top" : "20",
                    "left": "70%",
                    "margin-left": "-500px"
                });
                $('.modal-body').modal('show');

            }
            if (data == 1) {
                $('#tablaperiodo').show();
                $('#campoobligado').hide();
                $('#guardar1').show();
            }

        });

//        }
    });

    $('#exonerar').change(function() {
        if ($('#exonerar').is(':checked')) {
            $('.indicador').attr('disabled', false);
        }
        else {
            $('.indicador').attr('disabled', true);
            $('.indicador').attr('checked', false);
        }
    });

    $('#estilo').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"
    });

    $("#fechadocu").datepicker();
    $("#hasta").datepicker();


    $('#guardar1').click(function() {
        var url = "<?= base_url('index.php/pila/periodosactivarinactivar') ?>";
        $.post(url, $('form').serialize());

    });



</script>


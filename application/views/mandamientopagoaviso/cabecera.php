<table align='center'>
    <tr>
        <td>
            <?php 
            echo form_label('Identificación', 'nit');
            ?>
        </td>
        <td>                
            <p>
                <?php
                $dataNIT = array(
                    'name' => 'nit',
                    'id' => 'nit',
                    'value' => $informacion[0]['CODEMPRESA'],
                    'maxlength' => '30',

                    'readonly' => 'readonly'

                );
                echo form_input($dataNIT);
                echo form_error('nit', '<div>', '</div>');
                ?>
            </p>
        </td>
        <td>
            <p>
                <?php
                echo form_label('Ejecutado', 'razon');
                echo "</td><td>";
                $datarazon = array(
                    'name' => 'razon',
                    'id' => 'razon',
                    'value' => $informacion[0]['NOMBRE_EMPRESA'],
                    'maxlength' => '30',
                    'size' => '120',

                    'readonly' => 'readonly'

                );
                echo form_input($datarazon);
                echo form_error('razon', '<div>', '</div>');
                ?>
            </p>
        </td>
    </tr>
    <tr>
        <td>            
            <p>
                <?php
                echo form_label('Concepto', 'concepto');
                echo "</td><td>";
                $dataconcepto = array(
                    'name' => 'concepto',
                    'id' => 'concepto',
                    'value' => 'FIC',
                    'maxlength' => '30',
                    'size' => '120',

                    'readonly' => 'readonly'

                );
                echo form_input($dataconcepto);
                echo form_error('concepto', '<div><b><font color="red">', '</font></b></div>');
                ?>
            </p>
        </td>
        <td>
            <p>
                <?php
                echo form_label('Instancia', 'instancia');
                echo "</td><td>";
                $datainsta = array(
                    'name' => 'instancia',
                    'id' => 'instancia',
                    'value' => $instancia,
                    'maxlength' => '30',
                    'size' => '120',

                    'readonly' => 'readonly'

                );
                echo form_input($datainsta);
                echo form_error('instancia', '<div>', '</div>');
                ?>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <p>
                <?php
                echo form_label('Representante', 'representante');
                echo "</td><td>";
                $datarepresentante = array(
                    'name' => 'representante',
                    'id' => 'representante',
                    'value' => $informacion[0]['REPRESENTANTE_LEGAL'],
                    'maxlength' => '30',
                    'size' => '120',

                    'readonly' => 'readonly'

                );
                echo form_input($datarepresentante);
                echo form_error('representante', '<div>', '</div>');
                ?>
            </p>
        </td>
        <td>
            <p>
                <?php
                echo form_label('Tel&eacute;fono', 'telefono');
                echo "</td><td>";
                $datatelefono = array(
                    'name' => 'telefono',
                    'id' => 'telefono',
                    'value' => $informacion[0]['TELEFONO_FIJO'],
                    'maxlength' => '30',

                    'readonly' => 'readonly'

                );
                echo form_input($datatelefono);
                echo form_error('telefono', '<div>', '</div>');
                ?>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <p>
                <?php
                echo form_label('Estado', 'estado');
                echo "</td><td>";
                $dataestado = array(
                    'name' => 'estado',
                    'id' => 'estado',
                    'value' => @$gestiones->NOMBRE_GESTION,
                    'maxlength' => '30',

                    'readonly' => 'readonly'

                );
                echo form_input($dataestado);
                echo form_error('estado', '<div><b><font color="red">', '</font></b></div>');
                ?>
            </p>
        </td>
        <td>
            <p>
                <?php
                echo form_label('Direcci&oacute;n', 'direccion');
                echo "</td><td>";
                $datatelefono = array(
                    'name' => 'direccion',
                    'id' => 'direccion',
                    'value' => $informacion[0]['DIRECCION'],
                    'maxlength' => '30',

                    'readonly' => 'readonly'

                );
                echo form_input($datatelefono);
                echo form_error('telefono', '<div>', '</div>');
                ?>
            </p>
        </td>
    </tr>

    <?php
    if (count(@$resolucion) > 0) {
        echo "<tr>
                            <td>";
        echo form_label('Resolución', 'resolucion');
        echo "</td><td>";
        $dataresolu = array(
            'name' => 'resolucion',
            'id' => 'resolucion',
            'value' => $resolucion[0]['NUM_RESOLUCION'],
            'maxlength' => '30',

            'readonly' => 'readonly'

        );
        echo form_input($dataresolu);
        echo "</td>
                            <td>";
        echo form_label('Fecha Resolución', 'fechareso');
        echo "</td><td>";
        $datafechares = array(
            'name' => 'fechareso',
            'id' => 'fechareso',
            'value' => $resolucion[0]['FECHA_RESOLUCION'],
            'maxlength' => '30',

            'readonly' => 'readonly'

        );
        echo form_input($datafechares);
        echo "</td></tr>";
    }
    ?>
    <tr><td colspan="4">

               <?php   if ($titulos):?>
    <center><h4>Detalle Titulos</h4>
        <?php // echo "<pre>";print_r($titulos);echo "</pre>";?>
    </center>
 
    <div style="width:610px; ">
        <table  border="1" align="center" class="table table-bordered table-striped">

            <tr>
                <th>Titulo</th>
                <th>Concepto</th>
                <th>Saldo <BR>Deuda</th>
                <th>Saldo <BR> Capital</th>
                <th>Saldo Con<BR> Intereses</th>
                <th>Sanción</th>
                <th>Multa</th>
                <th style="<?php if ($codrespuesta == 204): ?>display:block; <?php endif;  ?>"><?php if ($codrespuesta == 204): ?>Incluir<?php endif; ?><br><br><br></th>
            </tr>
            <?php
                foreach ($titulos as $titulo):
                    ?>
                    <tr>
                        <td><?php echo $titulo['COD_EXPEDIENTE_JURIDICA']; ?></td>
                        <td><?php echo $titulo['CONCEPTO'] ?></td>
                        <td><?php echo number_format($titulo['SALDO_DEUDA']) ?></td>
                        <td><?php echo number_format($titulo['SALDO_CAPITAL']) ?></td>
                        <td><?php echo number_format($titulo['SALDO_INTERES']) ?></td>
                        <td>0</td>
                        <td>0</td>
                        <td style="<?php if ($codrespuesta == 204):?>display: block<?php endif;?>"><?php if ($codrespuesta == 204): ?> <input type="checkbox" name="titulos[]" id="titulos" value="<?php echo $titulo['NO_EXPEDIENTE']; ?>"><?php endif; ?><br><br> </td>
                    </tr>
                <?php endforeach;
           

            ?>
        </table>
    </div>

</td></tr>
</table>

<?php  endif;?>

<?php
/* * Lista los titulos que se encuentran con el proceso coactivo* */
?>

<p><h3>Informacion de la Empresa</h3></p>
<div class="controls controls-row">
<div class="span4" align="left">
<?php
 echo form_label('Nit', 'NIT_EMPRESA');
   $data = array(
              'name'        => 'NIT_EMPRESA',
              'id'          => 'NIT_EMPRESA',
              'value'       => $avaluo->CODEMPRESA,
              'class'      => 'span3',
              'readonly'   => 'readonly'
            );

   echo form_input($data);
?>
</div>
<div class="span4" align="left">
<?php
echo form_label('Razon Social', 'RAZON_SOCIAL');
$data = array(
           'name'        => 'RAZON_SOCIAL',
           'id'          => 'RAZON_SOCIAL',
           'value'       => $avaluo->RAZON_SOCIAL,
           'class'       => 'span3',
           'readonly'    => 'readonly'
         );

   echo form_input($data);
?>
</div>
</div>
<div class="controls controls-row">
<div class="span4" align="left">
<?php
echo form_label('Concepto', 'consepto');
$data = array(
           'name'        => 'consepto',
           'id'          => 'consepto',
           'value'       => $avaluo->NOMBRE_CONCEPTO,
           'class'       => 'span3',
           'readonly'    => 'readonly'
         );

   echo form_input($data);
?>
</div>    

<div class="span4" align="left">
<?php
echo form_label('Instancia', 'instancia');
$data = array(
           'name'        => 'instancia',
           'id'          => 'instancia',
           'value'       => $instancia,
           'class'       => 'span3',
           'readonly'    => 'readonly'
         );

   echo form_input($data);
?>
</div>
</div>
<div class="controls controls-row">
<div class="span4" align="left">
<?php
echo form_label('Representante Legal', 'representante_legal');
$data = array(
           'name'        => 'representante_legal',
           'id'          => 'representante_legal',
           'value'       => $avaluo->REPRESENTANTE_LEGAL,
           'class'       => 'span3',
           'readonly'    => 'readonly'
         );

   echo form_input($data);
?>
</div>

<div class="span4" align="left">
<?php
echo form_label('Telefono', 'telefono');
$data = array(
           'name'        => 'telefono',
           'id'          => 'telefono',
           'value'       => $avaluo->TELEFONO_FIJO,
           'class'       => 'span3',
           'readonly'    => 'readonly'
         );

   echo form_input($data);
?>
</div>
</div>
<p><h3>Propiedades del avaluo</h3></p>
<div class="controls controls-row">
    <div class="span4" align="left">
        <?php
        echo form_label('Identificacion del Avaluador', 'ident_avaluador');
        $data = array(
                   'name'        => 'ident_avaluador',
                   'id'          => 'ident_avaluador',
                   'value'       => $avaluo->LICENCIA_NRO,
                   'class'       => 'span3',
                   'readonly'    => 'readonly'
                 );

           echo form_input($data);
        ?>
    </div>
     <div class="span4" align="left">
        <?php
        echo form_label('Tipo de bien', 'tip_bien');
        $data = array(
                   'name'        => 'tip_bien',
                   'id'          => 'tip_bien',
                   'value'       => $prelacionTitulo->NOMBRE_TIPO,
                   'class'       => 'span3',
                   'readonly'    => 'readonly'
                 );

           echo form_input($data);
        ?>
    </div>
</div>
<?php 
switch ($prelacionTitulo->COD_TIPOBIEN) {
case 1://Mueble
    $count = 0;
    if(count($propiedadesAval) > 0){
    ?>
    <p><h3>Bienes retenidos</h3></p>  
    <?php
    }
    foreach ($propiedadesAval as $propiedad) {
        $count++;
        $valor_total =+  $propiedad->COSTO_UNITARIO;
        ?>
            <p><h3>Bien numero <?php echo $count?></h3></p> 
            <div class="controls controls-row">
                <div class="span4" align="left">
                    <?php
                    echo form_label('Observaciones', 'observaciones');
                    $data = array(
                               'name'        => 'observaciones',
                               'id'          => 'observaciones',
                               'value'       => $propiedad->OBSERVACIONES,
                               'class'       => 'span3',
                               'readonly'    => 'readonly'
                             );


                       echo form_textarea($data);
                    ?>
                </div>
                <div class="span4" align="left">
                    <?php
                    echo form_label('Valor', 'costo_unitario');
                    $data = array(
                               'name'        => 'costo_total',
                               'id'          => 'costo_total',
                               'value'       => $propiedad->COSTO_UNITARIO,
                               'class'       => 'span3',
                               'readonly'    => 'readonly'
                             );

                       echo form_input($data);
                    ?>
                </div>
            </div>        
        <?php
    }
    break;
    case 2://Inmueble
        if(count($propiedadesAval) > 0){
        $propiedad = $propiedadesAval[0];
        $valor_total = $propiedad->COSTO_MUEBLE;
        ?>
        <p><h3>Caracteriaticas del Inmueble</h3></p>
        <div class="controls controls-row">
            <div class="controls controls-row">
            <div class="span4" align="left">
                <?php
                    echo form_label('Ubicacion del bien', 'ubi_bien');
                    $data = array(
                               'name'        => 'ubi_bien',
                               'id'          => 'ubi_bien',
                               'value'       => $avaluo->UBICACION_TOTAL,
                               'class'       => 'span3',
                               'readonly'    => 'readonly'
                             );

                       echo form_input($data);
                    ?>
            </div>
            <div class="span4" align="left">
                <?php
                    echo form_label('Direccion del bien', 'dir_bien');
                    $data = array(
                               'name'        => 'dir_bien',
                               'id'          => 'dir_bien',
                               'value'       => $avaluo->UBICACION_TOTAL,
                               'class'       => 'span3',
                               'readonly'    => 'readonly'
                             );

                       echo form_input($data);
                    ?>
            </div>
        </div>
            <div class="span4" align="left">
                <?php
                    echo form_label('Tipo de inmueble', 'nombre_inmueble');
                    $data = array(
                               'name'        => 'nombre_inmueble',
                               'id'          => 'nombre_inmueble',
                               'value'       => $propiedad->NOMBRE_INMUEBLE,
                               'class'       => 'span3',
                               'readonly'    => 'readonly'
                             );

                       echo form_input($data);
                    ?>
            </div>
            <div class="span4" align="left">
                <?php
                    echo form_label('Tipo de propiedad', 'tip_propiedad');
                    $data = array(
                               'name'        => 'tip_propiedad',
                               'id'          => 'tip_propiedad',
                               'value'       => $propiedad->NOM_TIPOPROP,
                               'class'       => 'span3',
                               'readonly'    => 'readonly'
                             );

                       echo form_input($data);
                    ?>
            </div>
        </div>
        <div class="controls controls-row">
            <div class="span4" align="left">
                <?php
                    echo form_label('Area Total', 'area_total');
                    $data = array(
                               'name'        => 'area_total',
                               'id'          => 'area_total',
                               'value'       => $propiedad->AREA_TOTAL,
                               'class'       => 'span3',
                               'readonly'    => 'readonly'
                             );

                       echo form_input($data);
                    ?>
            </div>
            <div class="span4" align="left">
                <?php
                echo form_label('Costo total', 'cos_total');
                $data = array(
                           'name'        => 'cos_total',
                           'id'          => 'cos_total',
                           'value'       => $propiedad->COSTO_TOTAL,
                           'class'       => 'span3',
                           'readonly'    => 'readonly'
                         );

                echo form_input($data);
                ?>
            </div>
        </div>
        <?php
        }
    break;
    case 3://Vehiculo
        if(count($propiedadesAval) > 0){
            $propiedad = $propiedadesAval[0];
            $valor_total = $propiedad->COSTO_MUEBLE;
            ?>
            <p><h3>Caracteriaticas del vehiculo</h3></p>
            <div class="controls controls-row">
                <div class="span4" align="left">
                    <?php
                        echo form_label('Placa Vehiculo', 'placa_vehiculo');
                        $data = array(
                                   'name'        => 'placa_vehiculo',
                                   'id'          => 'placa_vehiculo',
                                   'value'       => $propiedad->PLACA_VEHICULO,
                                   'class'       => 'span3',
                                   'readonly'    => 'readonly'
                                 );

                        echo form_input($data);
                    ?>
                </div>
                <div class="span4" align="left">
                    <?php
                        echo form_label('Marca Vehiculo', 'marca_vehiculo');
                        $data = array(
                                   'name'        => 'marca_vehiculo',
                                   'id'          => 'marca_vehiculo',
                                   'value'       => $propiedad->MARCA_VEHICULO,
                                   'class'       => 'span3',
                                   'readonly'    => 'readonly'
                                 );

                        echo form_input($data);
                    ?>
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span4" align="left">
                    <?php
                        echo form_label('Numero de chasis', 'numero_chasis');
                        $data = array(
                                   'name'        => 'numero_chasis',
                                   'id'          => 'numero_chasis',
                                   'value'       => $propiedad->NUMERO_CHASIS,
                                   'class'       => 'span3',
                                   'readonly'    => 'readonly'
                                 );

                        echo form_input($data);
                    ?>
                </div>
                <div class="span4" align="left">
                    <?php
                        echo form_label('Modelo Vehiculo', 'modelo_vehiculo');
                        $data = array(
                                   'name'        => 'modelo_vehiculo',
                                   'id'          => 'modelo_vehiculo',
                                   'value'       => $propiedad->MODELO_VEHICULO,
                                   'class'       => 'span3',
                                   'readonly'    => 'readonly'
                                 );

                        echo form_input($data);
                    ?>
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span4" align="left">
                    <?php
                        echo form_label('Servicio Vehiculo', 'servicio_vehiculo');
                        $data = array(
                                   'name'        => 'servicio_vehiculo',
                                   'id'          => 'servicio_vehiculo',
                                   'value'       => $propiedad->SERVICIO_VEHICULO,
                                   'class'       => 'span3',
                                   'readonly'    => 'readonly'
                                 );

                        echo form_input($data);
                    ?>
                </div>
                <div class="span4" align="left">
                    <?php
                        echo form_label('Color Vehiculo', 'color_vehiculo');
                        $data = array(
                                   'name'        => 'modelo_vehiculo',
                                   'id'          => 'modelo_vehiculo',
                                   'value'       => $propiedad->MODELO_VEHICULO,
                                   'class'       => 'span3',
                                   'readonly'    => 'readonly'
                                 );

                        echo form_input($data);
                    ?>
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span4" align="left">
                    <?php
                        echo form_label('Observaciones', 'observaciones');
                        $data = array(
                                   'name'        => 'observaciones',
                                   'id'          => 'observaciones',
                                   'value'       => $propiedad->OBSERVACIONES,
                                   'class'       => 'span3',
                                   'readonly'    => 'readonly'
                                 );

                        echo form_textarea($data);
                    ?>
                </div>
                <div class="span4" align="left">
                    <?php
                        echo form_label('Color Vehiculo', 'color_vehiculo');
                        $data = array(
                                   'name'        => 'modelo_vehiculo',
                                   'id'          => 'modelo_vehiculo',
                                   'value'       => $propiedad->MODELO_VEHICULO,
                                   'class'       => 'span3',
                                   'readonly'    => 'readonly'
                                 );

                        echo form_input($data);
                    ?>
                </div>
            </div>
            <?php
        }
    break;    
} 
?>
<div class="controls controls-row">
        <div class="span4" align="left">
            <?php
                echo form_label('Valor total', 'valor_total');
                $data = array(
                           'name'        => 'valor_total',
                           'id'          => 'valor_total',
                           'value'       => $valor_total,
                           'class'       => 'span3',
                           'readonly'    => 'readonly'
                         );

                echo form_input($data);
            ?>
        </div>
</div>

<?php 
if(isset($certificado) && ($certificado !== null)){
    ?>
    <p><h3>Certificado de tradicion y libertad</h3></p>
    <div class="controls controls-row">
        <div class="span4" align="left">
            <?php
                echo form_label('Fecha de solicitud', 'fech_solicert');
                $data = array(
                           'name'        => 'fech_solicert',
                           'id'          => 'fech_solicert',
                           'value'       => $certificado->FECHA_SOLICITUD,
                           'class'       => 'span3',
                           'readonly'    => 'readonly'
                         );

                echo form_input($data);
            ?>
        </div>
        <div class="span4" align="left">
            <?php
                echo form_label('Comentarios', 'coment_cert');
                $data = array(
                           'name'        => 'coment_cert',
                           'id'          => 'coment_cert',
                           'value'       => $certificado->COMENTARIOS,
                           'class'       => 'span3',
                           'readonly'    => 'readonly'
                         );

                echo form_textarea($data);
            ?>
        </div>
    </div>
    <div class="controls controls-row">
        <div class="span4" align="left">
            <?php
                echo form_label('Num Radicado Onbase', 'num_onbase');
                $data = array(
                           'name'        => 'fech_solicert',
                           'id'          => 'fech_solicert',
                           'value'       => $certificado->NUM_RADICADO_ONBASE,
                           'class'       => 'span3',
                           'readonly'    => 'readonly'
                         );

                echo form_input($data);
            ?>
        </div>
        <div class="span4" align="left">
            <?php
                echo form_label('Persona que reviso', 'persona_reviso');
                $data = array(
                           'name'        => 'persona_reviso',
                           'id'          => 'persona_reviso',
                           'value'       => ($certificado->NOMBRE_REVISO . '' . $certificado->APELLIDOS_REVISO),
                           'class'       => 'span3',
                           'readonly'    => 'readonly'
                         );

                echo form_input($data);
            ?>
        </div>
    </div>
    <div class="controls controls-row">
        <div class="span4" align="left">
            <?php
                echo form_label('Fecha de revision', 'fech_revision');
                $data = array(
                           'name'        => 'fech_revision',
                           'id'          => 'fech_revision',
                           'value'       => $certificado->FECHA_REVISION,
                           'class'       => 'span3',
                           'readonly'    => 'readonly'
                         );

                echo form_input($data);
            ?>
        </div>
        <div class="span4" align="left">
            <div class="span4" align="left">
                <?php
                    echo form_label('Observaciones de revision', 'observa_revi');
                    $data = array(
                               'name'        => 'observa_revi',
                               'id'          => 'observa_revi',
                               'value'       => $certificado->OBSERVACIONES_REVISION,
                               'class'       => 'span3',
                               'readonly'    => 'readonly'
                             );

                    echo form_textarea($data);
                ?>
            </div>
        </div>
    </div>
    <div class="span4" align="left">
        <div class="span4" align="left">
            <?php 
            $data = array(
                'name' => 'Descargar Oficio ',
                'id' => 'desc_ofic_certi',
                'value' => 'Aceptar',
                'type' => 'button',
                'content' => 'Aceptar',
                'onclick' => 'window.open("")',
                'disabled' => 'true'
            );
            echo form_button($data);
            ?>
        </div>
    </div>
    <?php 
}
?>
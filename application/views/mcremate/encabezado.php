<div class="caja_negra" id="caja_negra" style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <br>
    <span class="span2"><label for="nit"><b>Identificación</b></label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'nit',
            'id' => 'nit',
            'value' => $encabezado['IDENTIFICACION'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?> 
    </span>
    <span class="span2"><label for="razonsocial"><b>Ejecutado</b></label></span>
    <span class="span3">
        <?php
        $data = array(
            'name' => 'razonsocial',
            'id' => 'razonsocial',
            'class' => 'input-xlarge',
            'value' => $encabezado['EJECUTADO'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?>       
    </span><br><br>
    <span class="span2"><label for="nit"><b>Concepto</b></label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'identificacion',
            'id' => 'nit',
            'value' => $encabezado['CONCEPTO'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?> 
    </span>
    <span class="span2"><label for="razonsocial"><b>Proceso</b></label></span>
    <span class="span3">
        <?php
        $data = array(
            'name' => 'razonsocial',
            'id' => 'razonsocial',
            'class' => 'input-xlarge',
            'value' => $encabezado['PROCESO'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?>       
    </span><br><br>
    <span class="span2"><label for="nit"><b>Representante</b></label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'identificacion',
            'id' => 'nit',
            'value' => $encabezado['REPRESENTANTE'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?> 
    </span>
    <span class="span2"><label for="razonsocial"><b>Teléfono</b></label></span>
    <span class="span3">
        <?php
        $data = array(
            'name' => 'razonsocial',
            'id' => 'razonsocial',
            'class' => 'input-xlarge',
            'value' => $encabezado['TELEFONO'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?>       
    </span><br><br>
    <span class="span2"><label for="nit"><b>Estado</b></label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'identificacion',
            'id' => 'nit',
            'value' => $encabezado['RESPUESTA'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?> 
    </span>
    <span class="span2"><label for="razonsocial"><b>Dirección</b></label></span>
    <span class="span3">
        <?php
        $data = array(
            'name' => 'razonsocial',
            'id' => 'razonsocial',
            'class' => 'input-xlarge',
            'value' => $encabezado['DIRECCION'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?>       
    </span><br><br><br>
</div>
<br>
<div class="avaluo" id="avaluo" style="background: white;width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 10px">
    <div style="color:#FC7323"><center><label for="cuenta_contable"><h2><b>Avalúo</b></h2></label></center></div><br>    
    <span class="span2"><label for="nit"><b>Id Avaluador:</b></label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'nit',
            'id' => 'nit',
            'value' => $info_avaluo['COD_AVALUADOR'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?> 
    </span>
    <span class="span2"><label for="razonsocial"><b>Nombre:</b></label></span>
    <span class="span3">
        <?php
        $data = array(
            'name' => 'razonsocial',
            'id' => 'razonsocial',
            'class' => 'input-xlarge',
            'value' => $info_avaluo['ELABORO'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?>       
    </span><br><br>
    <span class="span2"><label for="nit"><b>Ubicación del Bien:</b></label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'identificacion',
            'id' => 'nit',
            'value' => $info_avaluo['UBICACION_BIEN'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?> 
    </span>
    <span class="span2"><label for="razonsocial"><b>Dirección:</b></label></span>
    <span class="span3">
        <?php
        $data = array(
            'name' => 'razonsocial',
            'id' => 'razonsocial',
            'class' => 'input-xlarge',
            'value' => $info_avaluo['DIRECCION_BIEN'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?>       
    </span><br><br>
    <span class="span2"><label for="nit"><b>Localización:</b></label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'identificacion',
            'id' => 'nit',
            'value' => $info_avaluo['LOCALIZACION'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?> 
    </span>
    <span class="span2"><label for="razonsocial"><b>Uso:</b></label></span>
    <span class="span3">
        <?php
        $data = array(
            'name' => 'razonsocial',
            'id' => 'razonsocial',
            'class' => 'input-xlarge',
            'value' => $info_avaluo['USO'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?>       
    </span><br><br>
    <span class="span2"><label for="nit"><b>Tipo de Inmueble:</b></label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'identificacion',
            'id' => 'nit',
            'value' => $info_avaluo['NOMBRE_TIPO'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?> 
    </span>
    <span class="span2"><label for="razonsocial"><b>Área Total:</b></label></span>
    <span class="span3">
        <?php
        $data = array(
            'name' => 'razonsocial',
            'id' => 'razonsocial',
            'class' => 'input-xlarge',
            'value' => $info_avaluo['AREA_TOTAL'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?>       
    </span><br><br>
     <span class="span2"><label for="nit"><b>Valor del Bien:</b></label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'identificacion',
            'id' => 'nit',
            'value' => $info_avaluo['COSTO_TOTAL'],
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?> 
    </span><br><br>
</div>
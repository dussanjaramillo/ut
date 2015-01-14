<div class="caja_negra" id="caja_negra" style="width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <br>
    <span class="span2"><label for="nit"><b>Identificación</b></label></span>
    <span class="span3"><?php
        $data = array(
            'name' => 'nit',
            'id' => 'nit',
            'value' => $encabezado[0]->CODEMPRESA,
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
            'value' => $encabezado[0]->NOMBRE_EMPRESA,
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
            'value' => $encabezado[0]->NOMBRE_CONCEPTO,
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
            'value' => $encabezado[0]->PROCESO,
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
            'value' => $encabezado[0]->REPRESENTANTE_LEGAL,
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
            'value' => $encabezado[0]->TELEFONO_FIJO,
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
            'value' => $encabezado[0]->RESPUESTA,
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
            'value' => $encabezado[0]->DIRECCION,
            'readonly' => 'readonly'
        );
        echo form_input($data);
        ?>       
    </span><br><br><br>
</div>
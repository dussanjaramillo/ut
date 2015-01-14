<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<?php
/*
El nombrado de archivos es mu importante:

se debe seguir las sieguientes reglas básicas

el controlador debe tener solo caracteres alfabénicos, NO usar nombres estilo "identificacion_reclasificacion_pagos.php"
en lugar de eso se debe nombrar "Identificacionreclasificacionpagos.php", igual  para el nombre de la clase, debe llamarse exactamente igual
al archivo, para el ejemplo sería "class Identificacionreclasificacionpagos extends MY_Controller".

las vistas se deben alojar en una carpeta que se llame exactamente al controlador ejemplo: "identificacionreclasificacionpagos"
para nombrarlas se debe usar SIEMPRE! el mismo nombre del controlador seguido de un underscore (_) y una palabra alusiva al contenido,
ejemplos: "identificacionreclasificacionpagos_add.php", "identificacionreclasificacionpagos_edit.php" , "identificacionreclasificacionpagos_list.php".
No usar nombres genéricos como: "home.php"

En las vistas ni en ninguna parte se deben poner estilos directamente en las capas, NO hacer cosas tipo: "<div style="border: 1px solid grey; overflow: hidden; padding: 9px 0 0">"
para el diseño se debe usar bootstrap 2.3 y en otros casos se debe ver el diseño en páginas similares y usar las mismas clases, ejemplo: "<div class="formulario">
en algún caso muy específico usar clases (.)  propias y escribirlas en el archivo css/styles.css ejemplo; "<div class="miclase">""
todos los formularios deben tener en la parte superior la variable que muestran los errores, 
todos los campos de formulario  deben tener un label y si es campo obligatorio un asterisco (*) entre etiqutas span con la clase required de la forma:
 echo form_label('Nombre<span class="required">*</span>', 'nombre');
 se dbe setear el value.
 también deben llevar un id llamado igual al name, deben imprimir posibles errores al final, ejemplo de campo de formulario:

 <?php
         echo form_label('Nombre<span class="required">*</span>', 'nombre');
           $datanombre = array(
                      'name'        => 'nombre',
                      'id'          => 'nombre',
                      'value'       => set_value('nombre'),
                      'maxlength'   => '128',
                      'required'    => 'required'
                    );

           echo form_input($datanombre);
           echo form_error('nombre','<div>','</div>');
 ?>



para javascript usar identificadores id (#)

NO declarar estilos en las vistas tipo
NUNCA llamar archivos externos desde las vistas ejemplo: "<script language="javascript" src="../../js/dhtmlgoodies_calendar.js?random=20060118" type="text/javascript"></script>"
est se debe hacer cuando se requiera desde el controlador




<style type="text/css">

    .columna_derecha {
        float:right;  
        width:610px;
        border:solid lightblue 1px;

Nuevamente sólo se debn usar los estilos indicados


Importante!En todos los archivos se debe iniciar con "if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>"
Antes de eso no deben ir <scripts> ni ningun tipo de otra información 




Para las tablas se usa el plugin datatables y SIEMPRE! se debe usar trabajando con Ajax


IMPORTANTE! NO!  maquetar nunca con tablas de esta forma:
   <table cellspacing="0" cellspading="0" border="0" align="center">
        <tr>
            <td>
            <p>
Para maquetar sólo se deben usar capas con bootstrap 2.3

*/
?>











<div class="center-form">
        <?php     

        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <h2>Nuevo Banco</h2>
        <p>
        <?php
         echo form_label('IdBanco<span class="required">*</span>', 'idBanco');
           $dataid = array(
                      'name'        => 'idBanco',
                      'id'          => 'idBanco',
                      'value'       => set_value('idBanco'),
                      'required'    => 'required'
                    );

           echo form_input($dataid);
           echo form_error('idBanco','<div>','</div>');
        ?>
        </p>
        <p>
        <?php
         echo form_label('Nombre<span class="required">*</span>', 'nombre');
           $datanombre = array(
                      'name'        => 'nombre',
                      'id'          => 'nombre',
                      'value'       => set_value('nombre'),
                      'maxlength'   => '30',
                      'required'    => 'required',
                      'class'       => 'span3'
                    );

           echo form_input($datanombre);
           echo form_error('nombre','<div>','</div>');
        ?>
        </p>
        
        <p>
        <?php
         echo form_label('Estado<span class="required">*</span>', 'estado_id');  
              foreach($estados as $row) {
                  $select[$row->IDESTADO] = $row->NOMBREESTADO;
               }
          echo form_dropdown('estado_id', $select,set_value('estado_id'),'id="estado" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('estado_id','<div>','</div>');
        ?>
        </span>
        </p>
        <br>
        <p>
                <?php  echo anchor('bancos', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
                <?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit-button',
                       'value' => 'Guardar',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar',
                       'class' => 'btn btn-success'
                       );

                echo form_button($data);    
                ?>
                
        </p>

        <?php echo form_close(); ?>

</div>
  <script type="text/javascript">
  //style selects
     function format(state) {
    if (!state.id) return state.text; // optgroup
         return "<i class='fa fa-home fa-fw fa-lg'></i>" + state.id.toLowerCase() + " " + state.text;
    }
    $(".chosen0").select2({
    formatResult: format,
    formatSelection: format,
    escapeMarkup: function(m) { return m; }
    });


    $(document).ready(function() { $(".chosen").select2();

     });

  



  </script>
   
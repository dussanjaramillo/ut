<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
if (isset($message)) {
    echo $message;
}
?>

<h2>Nueva Empresa</h2>
<div class="center-form-large"> <?php echo form_open(current_url(), 'id="form"'); ?> <?php echo $custom_error; ?>
  <div id="resultado"></div>
  
  <!--// |:::::INICIO Datos de la empresa -->
  <div class="controls controls-row">
    <h2>Datos De La Empresa</h2>
    <div class="span8">
      <?php
            echo form_label('Nombre de la empresa<span class="required">*</span>', 'nombreempresa');
            $data = array(
                'name' => 'nombreempresa',
                'id' => 'nombreempresa',
                'value' => set_value('nombreempresa'),
                'maxlength' => '255',
                'required' => 'required',
                'class' => 'span8'
            );

            echo form_input($data);
            echo form_error('nombreempresa', '<div style="color: red">', '</div>');
            ?>
    </div>
  </div>
  <div class="controls controls-row">
    <div class="span4">
      <?php
            echo form_label('Dirección<span class="required">*</span>', 'direccion');
            $data = array(
                'name' => 'direccion',
                'id' => 'direccion',
                'value' => set_value('direccion'),
                'maxlength' => '50',
                'required' => 'required',
                'class' => 'span4'
            );

            echo form_input($data);
            echo form_error('direccion', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span2">
      <?php
            echo form_label('Teléfono fijo<span class="required">*</span>', 'telefonofijo');
            $data = array(
                'name' => 'telefonofijo',
                'id' => 'telefonofijo',
                'value' => set_value('telefonofijo'),
                'maxlength' => '15',
                'required' => 'required',
                'class' => 'span2'
            );

            echo form_input($data);
            echo form_error('telefonofijo', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span2">
      <?php
            echo form_label('Teléfono movil<span class="required">*</span>', 'telefonomovil');
            $data = array(
                'name' => 'telefonomovil',
                'id' => 'telefonomovil',
                'value' => set_value('telefonomovil'),
                'maxlength' => '15',
                'required' => 'required',
                'class' => 'span2'
            );

            echo form_input($data);
            echo form_error('telefonomovil', '<div style="color: red">', '</div>');
            ?>
    </div>
  </div>
  <div class="controls controls-row">
    <div class="span5">
      <?php
            echo form_label('Razón social<span class="required">*</span>', 'razon');
            $data = array(
                'name' => 'razon',
                'id' => 'razon',
                'value' => set_value('razon'),
                'maxlength' => '200',
                'required' => 'required',
                'class' => 'span5'
            );

            echo form_input($data);
            echo form_error('razon', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span3">
      <?php
            echo form_label('Documento<span class="required">*</span>', 'documento');
            $data = array(
                'name' => 'documento',
                'id' => 'documento',
                'value' => set_value('documento'),
                'maxlength' => '15',
                'required' => 'required',
                'class' => 'span3'
            );

            echo form_input($data);
            echo form_error('documento', '<div style="color: red">', '</div>');
            ?>
    </div>
  </div>
  <div class="controls controls-row">
  	<div class="span4">
      <?php
            echo form_label('Representante legal<span class="required">*</span>', 'representante');
            $data = array(
                'name' => 'representante',
                'id' => 'representante',
                'value' => set_value('representante'),
                'maxlength' => '255',
                'required' => 'required',
                'class' => 'span4'
            );

            echo form_input($data);
            echo form_error('representante', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span2">
      <?php
            echo form_label('Tipo de documento<span class="required">*</span>', 'tipodocumento_id');
            //$select1 = array('' => 'Seleccione...' );
            foreach ($tiposdocumento as $row) {
							if($row->NOMBRETIPODOC != "NIT") :
                $select1[$row->CODTIPODOCUMENTO] = $row->NOMBRETIPODOC;
							endif;
            }
            echo form_dropdown('tipodocumento_id', $select1, set_value('tipodocumento_id'), 'id="tipodocumento_id" required class="span2" placeholder="seleccione..." ');


            echo form_error('tipodocumento_id', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span2">
      <?php
            echo form_label('Documento<span class="required">*</span>', 'documentoRl');
            $data = array(
                'name' => 'documentoRl',
                'id' => 'documentoRl',
                'value' => set_value('documentoRl'),
                'maxlength' => '15',
                'required' => 'required',
                'class' => 'span2'
            );

            echo form_input($data);
            echo form_error('documento', '<div style="color: red">', '</div>');
            ?>
    </div>
  </div>
  <div class="controls controls-row">
    <div class="span3">
      <?php
            echo form_label('Regional<span class="required">*</span>', 'regional_id');
            $select2 = array('' => 'Seleccione...');
            foreach ($regionales as $row) {
                $select2[$row->COD_REGIONAL] = $row->NOMBRE_REGIONAL;
            }
            echo form_dropdown('regional_id', $select2, set_value('regional_id'), 'id="regional_id" required class="span3" placeholder="seleccione..." ');


            echo form_error('regional_id', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span3">
      <?php
            echo form_label('CIIU<span class="required">*</span>', 'ciu');

            foreach ($ciu as $row) {
                $select3[$row->CLASE] = $row->CLASE . ' - ' . $row->DESCRIPCION;
            }
            echo form_dropdown('ciu', $select3, set_value('ciu'), 'id="ciu" required class="chosen span5" data-placeholder="seleccione..." ');


            echo form_error('ciu', '<div style="color: red">', '</div>');
            ?>
    </div>
  </div>
  <div class="controls controls-row">
    <div class="span3">
      <?php
            echo form_label('Sector Económico<span class="required">*</span>', 'actividad');
            $data = array(
                'name' => 'actividad',
                'id' => 'actividad',
                'value' => set_value('actividad'),
                'maxlength' => '200',
                'required' => 'required',
                'class' => 'span3'
            );

            echo form_input($data);
            echo form_error('actividad', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span3">
      <?php
            echo form_label('Correo electrónico<span class="required">*</span>', 'email_emp');
            $data = array(
                'name' => 'email_emp',
                'id' => 'email_emp',
                'value' => set_value('email_emp'),
                'maxlength' => '200',
                'required' => 'required',
                'type' => 'email',
                'class' => 'span3'
            );

            echo form_input($data);
            echo form_error('email_emp', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span2">
      <?php
            echo form_label('Fax<span class="required">*</span>', 'fax');
            $data = array(
                'name' => 'fax',
                'id' => 'fax',
                'value' => set_value('fax'),
                'maxlength' => '50',
                'required' => 'required',
                'class' => 'span2'
            );

            echo form_input($data);
            echo form_error('fax', '<div style="color: red">', '</div>');
            ?>
    </div>
  </div>
  <div class="controls controls-row">
    <div class="span3">
      <label class="">¿Afiliado a caja de compensación?</label>
      <label class="radio inline">
        <input type="radio" id="inlineCheckbox1" value="0" onchange="habilitar(this.value);" name="afiliados" >
        No </label>
      <label class="radio inline">
        <input type="radio" id="inlineCheckbox2" value="1" onchange="habilitar(this.value);" name="afiliados" checked>
        Si </label>
    </div>
    <div class="span3">
      <?php
            echo form_label('Caja de compensación', 'nombrecaja');
            $data = array(
                'name' => 'nombrecaja',
                'id' => 'nombrecaja',
                'value' => set_value('nombrecaja'),
                'maxlength' => '200',
                'class' => 'span3'
            );

            echo form_input($data);
            echo form_error('nombrecaja', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span2">
      <label class="">¿Empresa nueva?</label>
      <label class="radio inline">
        <input type="radio" id="inlineCheckbox1" value="0" name="nueva" checked>
        No </label>
      <label class="radio inline">
        <input type="radio" id="inlineCheckbox2" value="1" name="nueva">
        Si </label>
    </div>
  </div>
  <div class="controls controls-row">
    <div class="span3">
      <?php
            echo form_label('Número de escritura pública', 'escritura');
            $data = array(
                'name' => 'escritura',
                'id' => 'escritura',
                'value' => set_value('escritura'),
                'maxlength' => '100',
                'class' => 'span3'
            );

            echo form_input($data);
            echo form_error('escritura', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span3">
      <?php
            echo form_label('Notaría', 'notaria');
            $data = array(
                'name' => 'notaria',
                'id' => 'notaria',
                'value' => set_value('notaria'),
                'maxlength' => '50',
                'class' => 'span3'
            );

            echo form_input($data);
            echo form_error('notaria', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span2">
      <?php
            echo form_label('Cuota aprendiz', 'cuota');
            $data = array(
                'name' => 'cuota',
                'id' => 'cuota',
                'value' => set_value('cuota'),
                'maxlength' => '5',
                'class' => 'span2'
            );

            echo form_input($data);
            echo form_error('cuota', '<div style="color: red">', '</div>');
            ?>
    </div>
  </div>
  <div class="controls controls-row">
    <div class="span3">
      <?php
            echo form_label('Resolución de Regulación', 'resolucion');
            $data = array(
                'name' => 'resolucion',
                'id' => 'resolucion',
                'value' => set_value('resolucion'),
                'maxlength' => '50',
                'class' => 'span3'
            );

            echo form_input($data);
            echo form_error('resolucion', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span2">
      <?php
            echo form_label('Planta personal', 'planta');
            $data = array(
                'name' => 'planta',
                'id' => 'planta',
                'value' => set_value('planta'),
                'maxlength' => '6',
                'class' => 'span2'
            );

            echo form_input($data);
            echo form_error('planta', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span3">
      <label class="">¿Autoriza notificaciones por E-mail?</label>
      <p align="center">
        <label class="radio inline">
          <input type="radio" id="inlineCheckbox1" value="0" name="autoriza" checked>
          No </label>
        <label class="radio inline">
          <input type="radio" id="inlineCheckbox2" value="1" name="autoriza">
          Si </label>
      </p>
    </div>
  </div>
  <div class="controls controls-row">
    <div class="span3">
      <?php
            echo form_label('Nombre de empresa principal<span class="required">*</span>', 'principal');
            $data = array(
                'name' => 'principal',
                'id' => 'principal',
                'value' => set_value('principal'),
                'maxlength' => '200',
                'required' => 'required',
                'class' => 'span3'
            );

            echo form_input($data);
            echo form_error('principal', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span3">
      <?php
            echo form_label('Registro cámara y comercio <span class="required">*</span>', 'registrocam');
            $data = array(
                'name' => 'registrocam',
                'id' => 'registrocam',
                'value' => set_value('registrocam'),
                'maxlength' => '50',
                'required' => 'required',
                'class' => 'span3'
            );

            echo form_input($data);
            echo form_error('registrocam', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span1">
      <?php
            echo form_label('N. sedes', 'sedes');
            $data = array(
                'name' => 'sedes',
                'id' => 'sedes',
                'value' => set_value('sedes'),
                'maxlength' => '2',
                'class' => 'span1',
                'onpaste' => 'return false'
            );

            echo form_input($data);
            echo form_error('sedes', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span1">
      <?php
            echo form_label('N.empleados', 'empleados');
            $data = array(
                'name' => 'empleados',
                'id' => 'empleados',
                'value' => set_value('empleados'),
                'maxlength' => '5',
                'class' => 'span1',
                'onpaste' => 'return false'
            );

            echo form_input($data);
            echo form_error('empleados', '<div style="color: red">', '</div>');
            ?>
    </div>
  </div>
  <div class="controls controls-row">
    <div class="span3">
      <?php
echo form_label('Tipo de empresa<span class="required">*</span>', 'tipoempresa_id');
//$select4 = array('' => 'Seleccione...');
foreach ($tiposempresa as $row) {
    $select4[$row->CODTIPOEMPRESA] = $row->NOM_TIPO_EMP;
}
echo form_dropdown('tipoempresa_id', $select4, set_value('tipoempresa_id'), 'id="tipoempresa" required class="span3" placeholder="seleccione..." ');


echo form_error('tipoempresa_id', '<div style="color: red">', '</div>');
?>
    </div>
    <div class="span2">
      <?php
echo form_label('Departamento<span class="required">*</span>', 'departamento_id');
$select5 = array('' => '');
foreach ($departamentos as $row) {
    $select5[$row->COD_DEPARTAMENTO] = $row->NOM_DEPARTAMENTO;
}

echo form_dropdown('departamento_id', $select5, set_value('departamento_id'), 'id="departamento_id" disabled required class="span2" placeholder="seleccione..." ');


echo form_error('departamento_id', '<div style="color: red">', '</div>');
?>
    </div>
    <div class="span3">
      <?php
echo form_label('Ciudad<span class="required">*</span>', 'ciudad');
$select0 = array('' => 'Seleccione...');
// foreach($municipios as $row) {
//     $select0[$row->CODMUNICIPIO] = $row->NOMBREMUNICIPIO;
//  }

echo form_dropdown('ciudad', $select0, set_value('ciudad'), 'id="ciudad" required class="span3" placeholder="seleccione..." ');


echo form_error('ciudad', '<div style="color: red">', '</div>');
?>
    </div>
  </div>
  <div class="controls controls-row">
    <div class="span2">
      <?php
echo form_label('Zona <span class="required">*</span>', 'zona');
$data = array(
    'name' => 'zona',
    'id' => 'zona',
    'value' => set_value('zona'),
    'maxlength' => '50',
    'required' => 'required',
    'class' => 'span2'
);

echo form_input($data);
echo form_error('zona', '<div style="color: red">', '</div>');
?>
    </div>
    <div class="span2">
      <?php
echo form_label('Barrio <span class="required">*</span>', 'barrio');
$data = array(
    'name' => 'barrio',
    'id' => 'barrio',
    'value' => set_value('barrio'),
    'maxlength' => '50',
    'required' => 'required',
    'class' => 'span2'
);

echo form_input($data);
echo form_error('barrio', '<div style="color: red">', '</div>');
?>
    </div>
    <div class="span2">
      <?php
echo form_label('Apartado aéreo', 'apartado');
$data = array(
    'name' => 'apartado',
    'id' => 'apartado',
    'value' => set_value('apartado'),
    'maxlength' => '10',
    'class' => 'span2'
);

echo form_input($data);
echo form_error('apartado', '<div style="color: red">', '</div>');
?>
    </div>
    <div class="span2">
      <?php
echo form_label('Código postal', 'postal');
$data = array(
    'name' => 'postal',
    'id' => 'postal',
    'value' => set_value('postal'),
    'maxlength' => '10',
    'class' => 'span2'
);

echo form_input($data);
echo form_error('postal', '<div style="color: red">', '</div>');
?>
    </div>
  </div>
  <div class="controls controls-row">
    <div class="span3">
      <?php
echo form_label('Página web', 'web');
$data = array(
    'name' => 'web',
    'id' => 'web',
    'value' => set_value('web'),
    'maxlength' => '200',
    'type' => 'url',
    'class' => 'span3'
);

echo form_input($data);
echo form_error('web', '<div style="color: red">', '</div>');
?>
    </div>
    <div class="span2">
      <?php
echo form_label('Teléfono alternativo', 'telefono_a');
$data = array(
    'name' => 'telefono_a',
    'id' => 'telefono_a',
    'value' => set_value('telefono_a'),
    'maxlength' => '15',
    'class' => 'span2'
);

echo form_input($data);
echo form_error('telefono_a', '<div style="color: red">', '</div>');
?>
    </div>
  </div>
  <!--// |:::::FIN datos de la empresa --> 
  
  <!--// |:::::INICIO Datos del Contacto --> 
  <br>
  <br>
  <div class="controls controls-row">
    <div id="resultadoc"></div>
    <h2>Datos Del Contacto</h2>
    <div class="span4">
      <?php
echo form_label('Nombres Contacto<span class="required">*</span>', 'n_contacto');
$datacontacto = array(
    'name' => 'n_contacto',
    'id' => 'n_contacto',
    'value' => set_value('n_contacto'),
    'maxlength' => '200',
    'class' => 'span4',
    'required' => 'required'
);

echo form_input($datacontacto);
echo form_error('n_contacto', '<div style="color: red">', '</div>');
?>
    </div>
    <div class="span2">
      <?php
            echo form_label('Primer Apellido<span class="required">*</span>', 'pa_contacto');
            $datacontacto = array(
                'name' => 'pa_contacto',
                'id' => 'pa_contacto',
                'value' => set_value('pa_contacto'),
                'maxlength' => '50',
                'class' => 'span2',
                'required' => 'required'
            );

            echo form_input($datacontacto);
            echo form_error('pa_contacto', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span2">
      <?php
            echo form_label('Segundo Apellido<span class="required">*</span>', 'sa_contacto');
            $datacontacto = array(
                'name' => 'sa_contacto',
                'id' => 'sa_contacto',
                'value' => set_value('sa_contacto'),
                'maxlength' => '50',
                'class' => 'span2'
                    //'required'    => 'required'
            );

            echo form_input($datacontacto);
            echo form_error('sa_contacto', '<div style="color: red">', '</div>');
            ?>
    </div>
  </div>
  <div class="controls controls-row">
    <div class="span2">
      <?php
            echo form_label('Tipo de documento<span class="required">*</span>', 'tipodocumento_cont');
            //$select1 = array('' => 'Seleccione...' );
            foreach ($tiposdocumento as $row) {
							if($row->NOMBRETIPODOC != "NIT") :
                $select1[$row->CODTIPODOCUMENTO] = $row->NOMBRETIPODOC;
							endif;
            }
            echo form_dropdown('tipodocumento_cont', $select1, set_value('tipodocumento_cont'), 'id="tipodocumento_cont" required class="span2" placeholder="seleccione..." ');


            echo form_error('tipodocumento_cont', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span2">
      <?php
            echo form_label('Identificación<span class="required">*</span>', 'id_cont');
            $dataid_cont = array(
                'name' => 'id_cont',
                'id' => 'id_cont',
                'value' => set_value('id_cont'),
                'maxlength' => '15',
                'class' => 'span2',
                'required' => 'required'
            );

            echo form_input($dataid_cont);
            echo form_error('id_cont', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span4">
      <?php
            echo form_label('Cargo<span class="required">*</span>', 'cargo_id_con');
            $datacar_cont = array(
                'name' => 'cargo_id_con',
                'id' => 'cargo_id_con',
                'value' => set_value('cargo_id_con'),
                'maxlength' => '200',
                'class' => 'span4',
                'required' => 'required'
            );

            echo form_input($datacar_cont);
            echo form_error('cargo_id_con', '<div style="color: red">', '</div>');
            ?>
    </div>
  </div>
  <div class="controls controls-row">
    <div class="span2">
      <?php
            echo form_label('Metodo de Contacto<span class="required">*</span>', 'm_contacto');
            //$select7 = array('' => 'Seleccione...');
            foreach ($metodo as $row) {
                $select7[$row->COD_METODO_CONTACTO] = $row->DESCRIPCION;
            }
            echo form_dropdown('m_contacto', $select7, set_value('m_contacto'), 'id="m_contacto" required class="span2" placeholder="seleccione..." ');


            echo form_error('m_contacto', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span3">
      <?php
            echo form_label('E-mail Principal<span class="required">*</span>', 'email_pri_cont');
            $datacontacto = array(
                'name' => 'email_pri_cont',
                'id' => 'email_pri_cont',
                'value' => set_value('email_pri_cont'),
                'maxlength' => '50',
                'class' => 'span3',
                'type' => 'email',
                'required' => 'required'
            );

            echo form_input($datacontacto);
            echo form_error('email_pri_cont', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span3">
      <?php
            echo form_label('E-mail Alternativo', 'email_alt_cont');
            $datacontacto = array(
                'name' => 'email_alt_cont',
                'id' => 'email_alt_cont',
                'value' => set_value('email_alt_cont'),
                'maxlength' => '200',
                'type' => 'email',
                'class' => 'span3',
            );

            echo form_input($datacontacto);
            echo form_error('email_alt_cont', '<div style="color: red">', '</div>');
            ?>
    </div>
  </div>
  <div class="controls controls-row">
    <div class="span2">
      <?php
            echo form_label('Teléfono Movil', 'tel_mov_con');
            $data = array(
                'name' => 'tel_mov_con',
                'id' => 'tel_mov_con',
                'value' => set_value('tel_mov_con'),
                'maxlength' => '15',
                'class' => 'span2'
            );

            echo form_input($data);
            echo form_error('tel_mov_con', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span3">
      <?php
            echo form_label('Teléfono Contacto Empresa<span class="required">*</span>', 'tel_conemp');
            $data = array(
                'name' => 'tel_conemp',
                'id' => 'tel_conemp',
                'value' => set_value('tel_conemp'),
                'maxlength' => '15',
                'required' => 'required',
                'class' => 'span3'
            );

            echo form_input($data);
            echo form_error('tel_conemp', '<div style="color: red">', '</div>');
            ?>
    </div>
    <div class="span3">
      <?php
            echo form_label('Fax', 'fax_con');
            $data = array(
                'name' => 'fax_con',
                'id' => 'fax_con',
                'value' => set_value('fax_con'),
                'maxlength' => '15',
                'class' => 'span3'
            );

            echo form_input($data);
            echo form_error('fax_con', '<div style="color: red">', '</div>');
            ?>
    </div>
  </div>
  
  <!--// |:::::FIN datos del contacto --> 
  <br>
  <br>
  <div class="controls controls-row">
    <p class="pull-right"> <?php echo anchor('crearempresa', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
  </div>
  <?php echo form_close(); ?> </div>
<script type="text/javascript">
    //style selects
    var config = {
        '.chosen': {disable_search_threshold: 10}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

</script> 

<!--// |::::: Values min y max de los Spinner --> 
<script>
    $(function() {
        $("#empleados").spinner({
            min: 1,
            max: 99999
        });
        $("#empleados").spinner("value", 1);
        $("#sedes").spinner({
            min: 1,
            max: 99
        });
        $("#sedes").spinner("value", 1);
    });
</script> 
<script>
    $(function() {
        $("#radio").buttonset();
    });
</script> 

<!--// |:::::Script para deshabilitar Inputs mediante la funcion Onchange en los Input Radio --> 
<script>
    function habilitar(value)
    {
        console.log(value);
        if (value == "1")
        {
            // |:::::habilitamos
            document.getElementById("nombrecaja").disabled = false;
        } else if (value == "0") {
            // |:::::deshabilitamos
            document.getElementById("nombrecaja").disabled = true;
        }
    }
    $('#submit-button').click(function() {
        if ($('#ciu').val() == '') {
            alert('El Campo CIIU es Obligatorio');
            $('#ciu').focus();
        }
    });
</script> 

<!--// |:::::Script para select dependientes--> 
<script type="text/javascript">
    $(document).ready(function() {
        $("#departamento_id").on('change', function() {
            $("#departamento_id option:selected").each(function() {
                departamento_id = $('#departamento_id').val();
                alert(departamento_id);
                $.post("<?php echo base_url(); ?>index.php/crearempresa/llenarCiudades", {
                    departamento_id: departamento_id
                }, function(data) {
                    $("#ciudad").html(data);
                });
            });
        });
    });
</script> 

<!-- // |:::::Script para consultar el contenido de un campo a la DB con ajax --> 
<!-- // |:::::Consulta el contenido del campo "codigo" --> 
<script>
    $(document).ready(function() {

        var consulta;

        //hacemos focus
        //$("#cedula").focus();

        //comprobamos si se pulsa una tecla
        $("#documento").focusout(function(e) {
            //obtenemos el texto introducido en el campo
            consulta = $("#documento").val();

            //hace la búsqueda
            $("#resultado").delay(1).queue(function(n) {

                //$("#resultado").html('<img src="ajax-loader.gif" />');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/crearempresa/id_check",
                    data: "c=" + consulta,
                    dataType: "html",
                    error: function() {
                        alert("error petición ajax");
                    },
                    success: function(data) {
                        $("#resultado").html(data);
                        n();
                    }
                });

            });

        });

    });
</script> 

<!-- // |:::::Script para consultar el contenido de un campo a la DB con ajax --> 
<!-- // |:::::Consulta el contenido del campo "id_cont" --> 
<script>
    $(document).ready(function() {

        var consulta;

        //hacemos focus
        //$("#cedula").focus();

        //comprobamos si se pulsa una tecla
        $("#id_cont").focusout(function(e) {
            //obtenemos el texto introducido en el campo
            consulta = $("#id_cont").val();

            //hace la búsqueda
            $("#resultadoc").delay(1).queue(function(n) {

                //$("#resultado").html('<img src="ajax-loader.gif" />');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/crearempresa/contacto_check",
                    data: "c=" + consulta,
                    dataType: "html",
                    error: function() {
                        alert("error petición ajax");
                    },
                    success: function(data) {
                        $("#resultadoc").html(data);
                        n();
                    }
                });

            });

        });

    });
</script> 

<!-- Validar Campos --> 
<script type="text/javascript">
    $(function() {
        //Para escribir solo letras
        $('#nombreempresa').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
        $('#representante').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou');
        $('#nombrecaja').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou');
        $('#principal').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou');
        $('#n_contacto').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou');
        $('#pa_contacto').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou');
        $('#sa_contacto').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou');


        //Para escribir solo numeros
        $('#telefonofijo').validCampoFranz('0123456789');
        $('#telefonomovil').validCampoFranz('0123456789');
        $('#documento').validCampoFranz('0123456789');
        $('#fax').validCampoFranz('0123456789');
        $('#escritura').validCampoFranz('0123456789');
        $('#cuota').validCampoFranz('0123456789');
        $('#sedes').validCampoFranz('0123456789');
        $('#empleados').validCampoFranz('0123456789');
        $('#telefono_a').validCampoFranz('0123456789');
        $('#tel_mov_con').validCampoFranz('0123456789');
        $('#tel_conemp').validCampoFranz('0123456789');
        $('#fax_con').validCampoFranz('0123456789');
        $('#apartado').validCampoFranz('0123456789');
        $('#postal').validCampoFranz('0123456789');
        $('#id_cont').validCampoFranz('0123456789');
        $('#planta').validCampoFranz('0123456789');
    });
</script> 
<script type="text/javascript">
    $('#regional_id').on('change', function() {
        var valor = $(this).val();
        if (valor == 1) {

            valor = 11;
        }
        $('#departamento_id').val(valor);
        $("#departamento_id option:selected").each(function() {
            departamento_id = $('#departamento_id').val();

            $.post("<?php echo base_url(); ?>index.php/crearempresa/llenarCiudades", {
                departamento_id: departamento_id

            }, function(data) {
                $("#ciudad").html(data);
            });
        });
        //alert(valor);
    });
</script> 
<script type="text/javascript">
    jQuery(function($) {

        $('form').bind('submit', function() {
            $(this).find('#departamento_id').removeAttr('disabled');
        });

    });
</script> 

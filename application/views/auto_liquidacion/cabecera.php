<?php
$dataNIT            = array('name'=> 'nit','id'=>'nit','value'=> $informacion[0]['CODEMPRESA'],'maxlength'=>'30','disabled'=>'disabled');
$datarazon          = array('name'=> 'razon','id'=>'razon','value'=>$informacion[0]['NOMBRE_EMPRESA'],'maxlength'=>'30','size'=>'120','disabled'=>'disabled');
$dataconcepto       = array('name'=>'concepto','id'=>'concepto','value'=>'APORTES PARAFISCALES','maxlength'=>'30','size'=>'70','disabled'=>'disabled');
$datainsta          = array('name'=>'instancia','id'=>'instancia','value'=>$instancia,'maxlength'=>'30','size'=>'70','disabled'=>'disabled');
$datarepresentante  = array('name'=>'representante','id'=>'representante','value'=>$informacion[0]['REPRESENTANTE_LEGAL'],'maxlength'=>'30','size'=>'120','disabled'=>'disabled');
$datatelefono       = array('name'=>'telefono','id'=>'telefono','value'=>$informacion[0]['TELEFONO_FIJO'],'maxlength'=>'30','disabled'=>'disabled');
$dataestado         = array('name'=>'estado','id'=>'estado','value'=>@$gestion->NOMBRE_GESTION,'maxlength'=>'30','disabled'=>'disabled');
$datadireccion      = array('name'=>'direccion','id'=>'direccion','value'=>$informacion[0]['DIRECCION'],'maxlength'=>'30','disabled'=>'disabled');
@$dataresolu         = array('name'=>'resolucion','id'=>'resolucion','value'=>$resolucion[0]['NUM_RESOLUCION'],'maxlength'=>'30','disabled'=>'disabled');                
@$datafechares       = array('name'=>'fechareso','id'=>'fechareso','value'=>$resolucion[0]['FECHA_RESOLUCION'],'maxlength'=>'30','disabled'=>'disabled');
                         
?>
<table align='center'>
    <tr>
        <td colspan='4'><?php echo "<center>".$titulo."</center>";   ?></td>
    </tr><tr><td><br><br><br></td></tr>
    <tr>
        <td><p><?php echo form_label('<b>Identificación:</b>','nit');?></p></td>
        <td><p><?php echo form_label('<b>Ejecutado:</b>', 'razon');?></p></td>
        <td><p><?php echo form_label('<b>Concepto:</b>', 'concepto');?></p></td>
        <td><p><?php echo form_label('<b>Instancia:</b>', 'instancia');?></p></td>
    </tr>
    <tr>
        <td><p><?php echo form_input($dataNIT);?></p></td>
        <td><p><?php echo form_input($datarazon);?></p></td>
        <td><p><?php echo form_input($dataconcepto);?></p></td>    
        <td><p><?php echo form_input($datainsta);?></p></td>
    </tr>
    <tr>
        <td><p><?php echo form_label('<b>Representante:</b>', 'representante');?></p></td>
        <td><p><?php echo form_label('<b>Tel&eacute;fono:</b>', 'telefono');?></p></td>
        <td><p><?php echo form_label('<b>Estado:</b>', 'estado');?></p></td>
        <td><p><?php echo form_label('<b>Direcci&oacute;n:</b>','direccion');?></p></td>
    </tr>
    <tr>    
        <td><p><?php echo form_input($datarepresentante);?></p></td>        
        <td><p><?php echo form_input($datatelefono);?></p></td>
        <td><p><?php echo form_input($dataestado);?></p></td>
        <td><p><?php echo form_input($datadireccion);?></p></td>
    </tr><tr><td><br><br><br></td></tr>
<?php
 if (count(@$resolucion)>0){
            echo "<tr>
                    <td>";
                        echo form_label('Resolución', 'resolucion');
                        echo "</td><td>";                                
                        echo form_input($dataresolu);
            echo "</td>
                    <td>";
                        echo form_label('Fecha Resolución', 'fechareso');
                        echo "</td><td>";                                
                        echo form_input($datafechares);
             echo "</td></tr>";
        } 
?>
</table>
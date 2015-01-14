<div align="center"><h3>Documentos</h3></div>
<table id="tablaCreate" align="center" class="table table-striped">
    <thead>      
       <tr align="center">
        <th align="center">Proceso</th>
        <th align="center">Instancia</th>
        <th align='center'>Fecha</th>
        <th align="center">Archivo</th>        
      </tr>
    </thead>
    <tbody>
            <?php        
           $this->load->helper(array('form','url','codegen_helper','template_helper','traza_fecha_helper','file'));
           if(!empty($documentos)) { 
            foreach (@$documentos->result_array as $values) { 
                $instancia = $values['COD_TIPONOTIFICACION'];
                $excepcion = $values['EXCEPCION'];
                $recurso   = $values['RECURSO'];
                switch ($instancia){
                    case 1:                        
                        if ($excepcion == 0 && $recurso == 0)
                            $instancia = 'Notificacion Personal';
                        else if ($excepcion == 1 && $recurso == 0)
                            $instancia = 'Notificacion Personal de Excepcion';
                        else if ($recurso == 1)
                            $instancia = 'Notificacion Personal de Recurso';
                        break;
                    case 2:
                        if ($excepcion == 0 && $recurso == 0)
                            $instancia = 'Notificacion Por Correo';
                        else if ($excepcion == 1 && $recurso == 0)
                            $instancia = 'Notificacion Por Correo de Excepcion';
                        else if ($recurso == 1)
                            $instancia = 'Notificacion Por Correo de Recurso';
                        break;
                    case 3:
                            $instancia = 'Notificacion En Diario';
                        break;
                    case 4:
                        if ($excepcion == 0 && $recurso == 0)
                            $instancia = 'Notificacion Pagina Web';
                        else if ($excepcion == 1 && $recurso == 0)
                            $instancia = 'Notificacion Pagina Web de Excepcion';
                        else if ($recurso == 1)
                            $instancia = 'Notificacion Pagina Web de Recurso';
                        break;
                    case 5:
                        $instancia = 'Notificacion Acta';
                        if ($excepcion == 0 && $recurso == 0)
                            $instancia = 'Notificacion Acta';
                        else if ($excepcion == 1 && $recurso == 0)
                            $instancia = 'Notificacion Acta de Excepcion';
                        else if ($recurso == 1)
                            $instancia = 'Notificacion Acta de Recurso';
                        break;
                    case 6:
                        $instancia = 'Notificacion Medida Cautelar';                        
                        break;
                    case 7:
                        $instancia = 'Notificacion Seguir Adelante';                        
                        break;
                    case 8:
                        $instancia = 'Notificacion Por Aviso';                        
                        break;
                    case 9:
                        $instancia = 'Notificacion Por Edicto';                        
                        break;
                }
                ?>
        <tr align="center">
            <td align="center"><?= @$fiscalizacion ?></td>
            <td align="center"><?= @$instancia ?></td>
            <td align='center'><?= @$values['FECHA_MODIFICA_NOTIFICACION'] ?></td>
            <td align="center"><?= "<a href='".base_url().$values['DOC_FIRMADO'].$values['NOMBRE_DOC_CARGADO']."' target=_blank>&nbsp;<i class='print fa fa-print' style='cursor : pointer'></i></a>"; ?></td>                                                                                            
        </tr>        
                <?php                
            }}else {
                echo "<tr><td align='center'>";
                echo "No Hay Documentos</td></tr>";
            }
            ?>
        <?php              
            $rutaArchivo = "./uploads/mandamientos/".$fiscalizacion."/pdf/excepcion/";
            if (is_dir($rutaArchivo)) {
                 $handle = opendir($rutaArchivo);
                    while ($file = readdir($handle)) {
                        if (is_file($rutaArchivo.$file)) {
                            if (@$rutaArchivo.$file == @$rutaArchivo.$excepcionDoc->PLANTILLA){    
                                $FechaexcepcionDoc = date("d/m/y", filectime($rutaArchivo.$excepcionDoc->PLANTILLA)); 
                                echo "<tr><td>".$fiscalizacion."</td>";
                                echo "<td>Resolución de Excepcion</td>";
                                echo "<td>$FechaexcepcionDoc</td >";
                                echo "<td>
                                    <a href='".base_url().$rutaArchivo.$excepcionDoc->PLANTILLA."' target=_blank>&nbsp;<i class='print fa fa-print' style='cursor : pointer'></i></a>
                                </td></tr>";
                            }
                        }
                    }
                }
            $rutaArchivo1 = "./uploads/mandamientos/".$fiscalizacion."/pdf/resolrecurso/";
            if (is_dir($rutaArchivo1)) {
                 $handle1 = opendir($rutaArchivo1);
                    while ($file1 = readdir($handle1)) {
                        if (is_file($rutaArchivo1.$file1)) {
                            if (@$rutaArchivo1.$file1 == @$rutaArchivo1.$recursoDoc->PLANTILLA){  
                                $FecharecursoDoc = date("d/m/y", filectime($rutaArchivo1.$recursoDoc->PLANTILLA)); 
                                echo "<tr><td>".$fiscalizacion."</td>";
                                echo "<td>Resolucion de Recurso</td>";
                                echo "<td>$FecharecursoDoc</td >";
                                echo "<td>
                                    <a href='".base_url().$rutaArchivo1.$recursoDoc->PLANTILLA."' target=_blank>&nbsp;<i class='print fa fa-print' style='cursor : pointer'></i></a>
                                </td></tr>";
                            }
                        }
                    }
                }
            $rutaExcep = "./uploads/mandamientos/".$fiscalizacion."/pdf/pago/";
            $rutaRec = "./uploads/mandamientos/".$fiscalizacion."/pdf/recurso/";
            if (is_dir($rutaExcep)) {
                 $handle2 = opendir($rutaExcep);
                    while ($file2 = readdir($handle2)) {
                        if (is_file($rutaExcep.$file2)) {
                            if ($rutaExcep.$file2 == $rutaExcep.$pago->NOM_DOCUMENTO){  
                                $Fechapago = date("d/m/y", filectime($rutaExcep.$pago->NOM_DOCUMENTO)); 
                                echo "<tr><td>".$fiscalizacion."</td>";                                
                                echo "<td>Folio de Excepcion</td>";
                                echo "<td>$Fechapago</td >";
                                echo "<td>
                                    <a href='".base_url().$rutaExcep.$pago->NOM_DOCUMENTO."' target=_blank>&nbsp;<i class='print fa fa-print' style='cursor : pointer'></i></a>
                                </td></tr>";
                            }
                        }
                    }
                }
            if (is_dir($rutaRec)) {
                 $handle3 = opendir($rutaRec);
                    while ($file3 = readdir($handle3)) {
                        if (is_file($rutaRec.$file3)) {
                            if (@$rutaRec.$file3 == @$rutaRec.$pruebaExcep->NOM_DOCUMENTO){ 
                                $FechapruebaExcep = date("d/m/y", filectime($rutaRec.$pruebaExcep->NOM_DOCUMENTO)); 
                                echo "<tr><td>".$fiscalizacion."</td>";
                                echo "<td>Folio de Recurso</td>";
                                echo "<td>$FechapruebaExcep</td >";
                                echo "<td>
                                    <a href='".base_url().$rutaRec.$pruebaExcep->NOM_DOCUMENTO."' target=_blank>&nbsp;<i class='print fa fa-print' style='cursor : pointer'></i></a>
                                </td></tr>";
                            }
                        }
                    }
                }
        ?>
            
    </tbody>     
</table>
        <div id="ajax_load" class="ajax_load" style="display: none">
            <div class="preload" id="preload" >
                <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
            </div>
        </div>   


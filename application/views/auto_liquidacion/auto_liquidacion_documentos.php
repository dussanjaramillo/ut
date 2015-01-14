<div align="center"><h3>Documentos</h3></div>
<table class="table" id="tablaCreate">
        <tbody>
        <tr class="warning">
          <th colspan='3'>Autos</th>          
        </tr>   
        <tr align="center">
            <th align="center">Proceso</th>
            <th align="center">Instancia</th>
            <th align="center">Archivo</th>        
        </tr>
         <?php       
           if(!empty($autos->NOMBRE_DOC_FIRMADO)){
                   $instanciaAuto = $autos->COD_ESTADOAUTO;
                   $tipo_auto = $autos->COD_TIPO_AUTO;
                   if ($tipo_auto == '3')
                       $tipo_auto = 'autoliquidacion';
                   if ($tipo_auto == '24')
                       $tipo_auto = 'objecion';
                   @$rutaAuto = base_url().'uploads/liquidacion/'.$fiscalizacion.'/pdf/'.$tipo_auto.'/'.$autos->NOMBRE_DOC_FIRMADO
               ?>
                    <tr align="center">
                        <td align="center"><?= @$fiscalizacion ?></td>
                        <td align="center"><?= $instanciaAuto ?></td>
                        <td align="center"><?= "<a href='".@$rutaAuto."' target=_blank>&nbsp;<i class='print fa fa-print' style='cursor : pointer'></i></a>"; ?></td>                                                                                            
                    </tr>        
                <?php                                   
               }else {
                echo "<tr><td align='center' colspan='3'>";
                echo "No Hay Autos</td></tr>";
            }
        ?>
        <tr class="success">
          <th colspan='3'>Notificaciones</th>          
        </tr>
        <tr align="center">
            <th align="center">Proceso</th>
            <th align="center">Instancia</th>
            <th align="center">Archivo</th>        
        </tr>
            <?php     
                foreach (@$documentos->result_array as $valores){                                        
                    $instancia = $valores['COD_TIPONOTIFICACION'];
                    switch ($instancia){                    
                        case '2':
                            $instancia = 'Notificacion Personal';
                            @$ruta = base_url().$valores['DOC_FIRMADO'].$valores['NOMBRE_DOC_CARGADO'];
                        break;
                        case '4':
                            $instancia = 'Notificacion Página Web';
                            @$ruta = base_url().$valores['DOC_FIRMADO'].$valores['NOMBRE_DOC_CARGADO'];
                        break;
                    }
                    if ($valores['COD_ESTADO'] == 7 || $valores['COD_ESTADO']== 8){
                        ?>
                        <tr align="center">
                            <td align="center"><?= @$fiscalizacion ?></td>
                            <td align="center"><?= @$instancia ?></td>
                            <td align="center"><?= "<a href='".@$ruta."' target=_blank>&nbsp;<i class='print fa fa-print' style='cursor : pointer'></i></a>"; ?></td>                                                                                            
                        </tr>        
                    <?php 
                    }else {
                        echo "<tr><td align='center' colspan='3'>";
                        echo "No Hay Notificaciones</td></tr>";
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


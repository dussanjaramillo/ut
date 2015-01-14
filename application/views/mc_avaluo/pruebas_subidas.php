<table style="margin-top: 0px;">
    <?php
    for ($i = 0; $i < count($_FILES); $i++) {
if($this->data['info'][$i]['data']['orig_name']){
        $ruta_archivo = $this->data['ruta'] . "/";
        $nombre_archivo = $this->data['info'][$i]['data']['orig_name'];
        $ruta = $ruta_archivo . $nombre_archivo;
    if (file_exists($ruta)) {
            ?><tr  id="<?php echo "prueba_".$prueba."_".$i; ?>">
                <td style="text-align: left;">
                    <a href="<?php echo base_url() . $ruta ?>"  target="_blank" class="linkli" style="text-align: left;">
                        <i class="fa fa-file-text-o fa-lg"  title=" <?php echo $nombre_archivo; ?>">Ver</i>
                    </a>
                    <input type="hidden" name="<?php echo "nombre_archivo[]";?>" id="nombre_archivo" value="<?php echo $ruta;?>">
                 </td>
                <td>
                    <button type="button"  style="background-color: transparent;border:0px;"onclick="eliminarDD('<?php echo $ruta_eliminar; ?>', '<?php echo $nombre_archivo; ?>','<?php echo "prueba_".$prueba."_".$i; ?>')" ><li class="icon-remove" style="background: "></li>
                </td>
            </tr>

            <?php
        }
}
    }
    ?>
            <tr><td><input type="hidden" name="ruta_carpeta[]" id="ruta_carpeta" value="<?php echo $ruta_archivo;?>"></td><tr>
                
</table>
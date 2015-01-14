<?php
class Calendario_festivo extends MY_Controller{
    private $objFecha;
    private $arrMeses = array("","Ene","Feb","Mar","Abr","Mayo","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
    
    function __construct() {
        parent::__construct();
        $this->load->model("calendariofestivo_model");
        $this->data['javascripts'] = array('js/editarCal.js');
    }

    //function index2($aaaa,$mm){
    function index(){	//$this->data['style_sheets'] = array('css/bootstrap.css' => 'screen');        //$this->ponerFecha(2014,6,30);        //$this->data['calendario'] = $this->festivobd();
      if ($this->ion_auth->logged_in()){
        $this->data['calendario'] = $this;
        $ahora = new DateTime();
        $aaaa=$this->input->post('anno');
        
        if( empty($aaaa) ){
            $aaaa = intval($ahora->format("Y"));
        }else{
            $aaaa = $this->input->post('anno');
        }
        $mm = $this->input->post('mes');
        if( !empty($mm) ){
            $mm = $this->input->post('mes');
        }else{
            $mm = 0;
        }
        $this->data['aaaa'] = $aaaa;
        $this->data['mm'] = $mm;
        $this->data['mostrarmes'] = $this->desplegarCalendario($aaaa,$mm);  //$this->data['templatefile'] = $this->template_file;   ///$this->data['ahora'] = new DateTime();
        $this->data['admin'] = 1;
        $this->template->set("title", "Calendario festivo");    //$this->template->load($this->template_file, "calendario_festivo/calendario_festivo_home", $this->data);
        $this->template->load($this->template_file, "calendario_festivo/calendario_festivo_home", $this->data);
//        $this->load->view( "calendario_festivo/calendario_festivo_home", $this->data);
      }else{
         redirect(base_url().'index.php/auth/login');
      }  
    }
    
    function datePicker($aaaa=0){
      if ($this->ion_auth->logged_in() ){
        $this->data['javascripts'] = array('js/jquery-1.10.2.js');
        $this->data['aaaa'] = $aaaa;
        $this->data['arrfestivos'] = json_encode($this->arrfestivos($aaaa));
        $this->load->view( "calendario_festivo/datepicker", $this->data);
      }else{
          redirect(base_url().'index.php/auth/login');
      }
    }
    
    //PARA CODEIGNITER
    function desplegarCalendario($aaaa,$mm){
      if ($this->ion_auth->logged_in() ){
        $festivosanuales = $this->traerfestivoarreglo($aaaa);
        $mos = "";
        if($mm==0){
            for($i=1;$i<=12;$i++){
                if($i==5){ $mos.="</td></tr><tr><td>";}    //$mos.= $this->mostrarMes($aaaa,$i);
                    $mos.= $this->mostrarMesArr($festivosanuales,$aaaa,$i);
            }
        }else{  //echo $ahora->format("Y-m-d H:i:s");   //$mos.= $this->mostrarMes($aaaa,$mm);
            $mos.= $this->mostrarMesArr($festivosanuales,$aaaa,$mm);
        }
        return $mos;
      }else{
          redirect(base_url().'index.php/auth/login');
      }  
    }

    //PARA CODEIGNITER    
    //function forzar($v,$o){
    /**
     * Función forzar
     * 
     * 
     */
    function forzar(){
      if ($this->ion_auth->logged_in() ){  
        $v = $this->input->post('v');
        $o = $this->input->post('o');
        $f = explode("-",$v);
        
        $num_rows = $this->calendariofestivo_model->festivo3fechas($f[0],$f[1],$f[2]);

        if($num_rows>0){
            $num = 1; //Existe, entonces no hay que insertar sino actualizarlo 
        }else{
            $num = 0;
        }
		
	switch($o){
            case "opc1":
                $this->calendariofestivo_model->ponerfestivo($num,$f[0],$f[1],$f[2],1);                    /*// if($num==1){$sql = " UPDATE festivo SET estado=1 WHERE anno=".$f[0]." AND mes=".$f[1]." AND dia=".$f[2];}elseif($num==0){$sql = " INSERT INTO festivo(anno,mes,dia,estado)values('".$f[0]."','".$f[1]."','".$f[2]."','1')";}//*/
            break;
            case "opc2":
                $this->calendariofestivo_model->ponerfestivo($num,$f[0],$f[1],$f[2],2);/*//if($num==1){  $sql = " UPDATE festivo SET estado=2 WHERE anno=".$f[0]." AND mes=".$f[1]." AND dia=".$f[2];}elseif($num==0){  $sql = " INSERT INTO festivo(anno,mes,dia,estado)values('".$f[0]."','".$f[1]."','".$f[2]."','2')";}//*/
            break;
            case "opc3":
                    $this->calendariofestivo_model->borrarfestivo($num,$f[0],$f[1],$f[2]);/*//if($num==1){ $sql = " DELETE FROM festivo WHERE anno=".$f[0]." AND mes=".$f[1]." AND dia=".$f[2];}else{ //no existe}//*/
            break;
	}
        echo $o;
      }else{
          redirect(base_url().'index.php/auth/login');
      }
    }
    
    
    /* M�todo ponerFecha: establece la fecha con la que se va a trabajar con la instancia del objeto
    @author F. Ricardo Puerto :: Thomas MTI
    @param $aaaa: a�o de 4 d�gitos
    @param $mm n�mero del mes
    @param $dd d�a de la fecha    	*/
    function ponerFecha($aaaa=NULL,$mm=NULL,$dd=NULL){
      if ($this->ion_auth->logged_in() ){
        if(empty($aaaa)) $aaaa = date("Y");
        if(empty($mm)) $mm = date("m");
        if(empty($dd)) $dd = date("d");
        $this->objFecha = new DateTime($aaaa."-".$mm."-".$dd);  //echo "<br>{".$aaaa."-".$mm."-".$dd."} ";
      }else{
          redirect(base_url().'index.php/auth/login');
      }  
    }

    
    /* Método diaFestivo: verifica si un día dado es festivo en Colombia
     
    @author F. Ricardo Puerto :: Thomas MTI	
    @since 30 I 2014
    @return boolean Retorna true: sí es festivo false: si es día laboral 
    */
    function diaFestivo($aaaa='',$mm='',$dd=''){
      if ($this->ion_auth->logged_in() ){  
        if(!empty($aaaa) && !empty($mm) &&!empty($dd)){
            $this->ponerFecha($aaaa,$mm,$dd);
        }
        if($this->esFestivo()===1 || $this->esFestivo()===true ){
            return true;
        }else{
            return false;
        }
      }else{
          redirect(base_url().'index.php/auth/login');
      }  
    }
    
    /* Método esFestivo: verifica si un d�a dado es festivo en Colombia
    @author F. Ricardo Puerto :: Thomas MTI	
    @return Retorna 1: sí es festivo forzado por BD,  
     * 2: sí no es festivo forzado por BD, 
     * true: si es normalmente es festivo
     */
    function esFestivo(){
      if ($this->ion_auth->logged_in() ){  
        $festivoBD = $this->festivobd();
        if($festivoBD==1){//return true;
            return 1;
        }else if($festivoBD==2 ){//return false;
                return 2;
        }
        $festivo = 0;

        if($this->objFecha->format('N')==7){ return true; }//domingo

        //SÁBADO SENA
        if($this->objFecha->format('N')==6){ return true; }//sábado

        //festivos fijos sin importar el d�a de la semana:
        else if($this->objFecha->format('m')==1 && $this->objFecha->format('d')==1){ return true; }
        else if($this->objFecha->format('m')==5 && $this->objFecha->format('d')==1){ return true; }
        else if($this->objFecha->format('m')==7 && $this->objFecha->format('d')==20){ return true; }
        else if($this->objFecha->format('m')==8 && $this->objFecha->format('d')==7){ return true; }
        else if($this->objFecha->format('m')==12 && $this->objFecha->format('d')==8){ return true; }
        else if($this->objFecha->format('m')==12 && $this->objFecha->format('d')==25){ return true; }
        //Lunes festivos
        else if($this->objFecha->format('N')==1){ //if($this->emiliani($this->objFecha->format('Y'),"01","06")){ return true;}
            if($this->emiliani($this->objFecha->format('Y'),1,6)){ return true;}
            if($this->emiliani($this->objFecha->format('Y'),3,19)){ return true;}
            if($this->emiliani($this->objFecha->format('Y'),6,29)){ return true;}
            if($this->emiliani($this->objFecha->format('Y'),8,15)){ return true;}
            if($this->emiliani($this->objFecha->format('Y'),10,12)){ return true;}
            if($this->emiliani($this->objFecha->format('Y'),11,1)){ return true;}
            if($this->emiliani($this->objFecha->format('Y'),11,11)){ return true;}
            //lunes festivos dependientes de semana santa
            if((intval($this->objFecha->format('m'))==5 || intval($this->objFecha->format('m'))==6 || intval($this->objFecha->format('m'))==7)){
                $festivo = $this->butcher($this->objFecha->format('Y'));

                $festivo->modify("+43 day");//asc //print("<br>assscccccccc: ".$festivo->format("Y-m-d"));
                $intervalo = $this->objFecha->diff($festivo);
                if(intval($intervalo->format('%Y'))==0 && intval($intervalo->format('%m'))==0 && intval($intervalo->format('%r%d'))==0){return true;}

                $festivo->modify("+21 day");//cc //print("<br>CCCCCCCC: ".$festivo->format("Y-m-d"));
                $intervalo = $this->objFecha->diff($festivo);
                if(intval($intervalo->format('%Y'))==0 && intval($intervalo->format('%m'))==0 && intval($intervalo->format('%r%d'))==0){return true;}

                $festivo->modify("+7 day");//sc //print("<br>SCSCSCscscsc: ".$festivo->format("Y-m-d"));
                $intervalo = $this->objFecha->diff($festivo);
                if(intval($intervalo->format('%Y'))==0 && intval($intervalo->format('%m'))==0 && intval($intervalo->format('%r%d'))==0){return true;}
            }
        }else if(($this->objFecha->format('N')==4 || $this->objFecha->format('N')==5) && (intval($this->objFecha->format('m'))==3 || intval($this->objFecha->format('m'))==4)){
            //Jueves y Viernes Santo
            $festivo = $this->butcher($this->objFecha->format('Y'));
            $festivo->modify("-3 day");//domingo de pascua -3 = jueves santo
            $intervalo = $this->objFecha->diff($festivo);
            if(intval($intervalo->format('%Y'))==0 && intval($intervalo->format('%m'))==0 && (intval($intervalo->format('%r%d'))==-1 || intval($intervalo->format('%r%d'))==0)){ 
            return true;}
        }
      }else{
          redirect(base_url().'index.php/auth/login');
      }    
    }


    /* Método esFestivoArreglo: 
     * Réplica de esFestivo
     * verifica si un día dado es festivo en Colombia, consulta la BD una sola vez
    @author F. Ricardo Puerto :: Thomas MTI
    @param $aaaa: año de 4 dígitos
    @param $mm n�mero del mes
    @param $dd d�a de la fecha
    @param $H Horas
    @param $i minutos
    @param $s segundos	*/
    function esFestivoArreglo($arreglodefestivos){
      if ($this->ion_auth->logged_in() ){ 
        $festivoArr = $this->festivocoincide($arreglodefestivos);
        if($festivoArr==1 ){
            //return true;
            return 1;
        }else if($festivoArr==2 ){
                //return false;
                return 2;
        }
        $festivo = 0;

        if($this->objFecha->format('N')==7){ return true; }//domingo

        //SÁBADO SENA
        if($this->objFecha->format('N')==6){ return true; }//sábado
 
        //festivos fijos sin importar el d�a de la semana:
        else if($this->objFecha->format('m')==1 && $this->objFecha->format('d')==1){ return true; }
        else if($this->objFecha->format('m')==5 && $this->objFecha->format('d')==1){ return true; }
        else if($this->objFecha->format('m')==7 && $this->objFecha->format('d')==20){ return true; }
        else if($this->objFecha->format('m')==8 && $this->objFecha->format('d')==7){ return true; }
        else if($this->objFecha->format('m')==12 && $this->objFecha->format('d')==8){ return true; }
        else if($this->objFecha->format('m')==12 && $this->objFecha->format('d')==25){ return true; }
        //Lunes festivos
        else if($this->objFecha->format('N')==1){ //if($this->emiliani($this->objFecha->format('Y'),"01","06")){ return true;}
            if($this->emiliani($this->objFecha->format('Y'),1,6)){ return true;}
            if($this->emiliani($this->objFecha->format('Y'),3,19)){ return true;}
            if($this->emiliani($this->objFecha->format('Y'),6,29)){ return true;}
            if($this->emiliani($this->objFecha->format('Y'),8,15)){ return true;}
            if($this->emiliani($this->objFecha->format('Y'),10,12)){ return true;}
            if($this->emiliani($this->objFecha->format('Y'),11,1)){ return true;}
            if($this->emiliani($this->objFecha->format('Y'),11,11)){ return true;}
            //lunes festivos dependientes de semana santa
            if((intval($this->objFecha->format('m'))==5 || intval($this->objFecha->format('m'))==6 || intval($this->objFecha->format('m'))==7)){
                $festivo = $this->butcher($this->objFecha->format('Y'));

                $festivo->modify("+43 day");//asc //print("<br>assscccccccc: ".$festivo->format("Y-m-d"));
                $intervalo = $this->objFecha->diff($festivo);
                if(intval($intervalo->format('%Y'))==0 && intval($intervalo->format('%m'))==0 && intval($intervalo->format('%r%d'))==0){return true;}

                $festivo->modify("+21 day");//cc //print("<br>CCCCCCCC: ".$festivo->format("Y-m-d"));
                $intervalo = $this->objFecha->diff($festivo);
                if(intval($intervalo->format('%Y'))==0 && intval($intervalo->format('%m'))==0 && intval($intervalo->format('%r%d'))==0){return true;}

                $festivo->modify("+7 day");//sc //print("<br>SCSCSCscscsc: ".$festivo->format("Y-m-d"));
                $intervalo = $this->objFecha->diff($festivo);
                if(intval($intervalo->format('%Y'))==0 && intval($intervalo->format('%m'))==0 && intval($intervalo->format('%r%d'))==0){return true;}
            }
        }else if(($this->objFecha->format('N')==4 || $this->objFecha->format('N')==5) && (intval($this->objFecha->format('m'))==3 || intval($this->objFecha->format('m'))==4)){
            //Jueves y Viernes Santo
            $festivo = $this->butcher($this->objFecha->format('Y'));
            $festivo->modify("-3 day");//domingo de pascua -3 = jueves santo
            $intervalo = $this->objFecha->diff($festivo);
            if(intval($intervalo->format('%Y'))==0 && intval($intervalo->format('%m'))==0 && (intval($intervalo->format('%r%d'))==-1 || intval($intervalo->format('%r%d'))==0)){ 
            return true;}
        }
      }else{
          redirect(base_url().'index.php/auth/login');
      }      
    }

    /*	seis de enero 6, marzo 19, junio 29, agosto 15, octubre 12, noviembre 1, noviembre 11 */
    /*
    * M�todo emiliani
    * Consulta los d�as del a�o que se trasladan a lunes festivo por la ley Emiliani
    * @author Felipe R. Puerto 
    * @param $aaaa: A�o de la fecha original que se debe pasar a festivo.
    * @param $mm: Mes de la fecha original que se debe pasar a festivo
    * @param $dd: D�a de la fecha d�a original que se debe pasar a festivo
    * @return boolean: Verdadero si el d�a instancia es lunes festivo 'emiliani'
* @since 2013-11-15 	*/
    function emiliani($aaaa,$mm,$dd){
      if ($this->ion_auth->logged_in() ){  
        $fec = $aaaa."-".$mm."-".$dd;
        $festivo  = new DateTime($fec);
        $intervalo = $this->objFecha->diff($festivo);
        if(intval($intervalo->format('%r%d'))>=-6 && intval($intervalo->format('%r%d'))<=0 && $intervalo->format('%m')==0 && $intervalo->format('%y')==0){ return true;}
      }else{
          redirect(base_url().'index.php/auth/login');
      }    
    }

    /*
    * M�todo mostrarMes
    * Muestra el calendario del mes indicado o los meses del a�o
    * @author Felipe R. Puerto 
    * @param $aaaa: A�o del que se consulta el calendario.
    * @param $mm: Mes del que se consulta el calendario.
* @since 2013-11-15	*/
    function mostrarMes($aaaa,$mm=0){
      if ($this->ion_auth->logged_in() ){
        $objMes = new DateTime($aaaa."-".$mm."-1");
        //print " (".$this->arrMeses[intval($objMes->format("m"))]." ".$objMes->format("Y").") ";
        $tm = intval($objMes->format("m"));
        $tY = intval($objMes->format("Y"));
        $diasInicioSemana = ($objMes->format("N"))%7;
        $objMes->modify("-".$diasInicioSemana." day");
        $claseDia = "diaHabil";
        $tablaMes = "";
        $tablaMes .="<TABLE align=left border=1><TR><TD>";
        $tablaMes .= " (".$this->arrMeses[$tm]." ".$tY.") ";
        $tablaMes .="</TD></TR><TR><TD>";
        $tablaMes .="<table class=calendario>";
        $tablaMes .="<tr class=cabeza><td></td><td>D</td><td>L</td><td>M</td><td>M</td><td>J</td><td>V</td><td>S</td></tr>";
        for($i=0;(($objMes->format("m")<=$mm && $objMes->format("Y")==$aaaa) || ($objMes->format("Y")<$aaaa) );$i++){        //print " (".$objMes->format("N")." ";
            if(($objMes->format("N"))==7){
                $objMes->modify("+4 day");
                $tablaMes .="<tr><td class=semana>".$objMes->format("W")."</td>";
                $objMes->modify("-4 day");
            }
            if(intval($objMes->format("m"))<$mm || ($objMes->format("m")==12 && $mm==1)){
                $claseDia="diaGris"; //dias finales del mes anterior
            }else{
                $this->ponerFecha($objMes->format("Y"),$objMes->format("m"),$objMes->format("d"));                //if($objMes->format("N")==7){
                $esFestivo = $this->esFestivo();
                if($esFestivo===true){
                        $claseDia="diaFestivo";
                }elseif($esFestivo===1){
                        $claseDia="diaFestivo style='font-size:75%;text-decoration:overline;font-style:italic' ";
                }elseif($esFestivo===2){
                        $claseDia="diaHabil style='font-size:75%;text-decoration:overline;font-style:italic' ";
                }else{
                        $claseDia="diaHabil";
                }
            }
            $tablaMes .="<td class=".$claseDia." content='".$objMes->format("Y-m-d")."'>".$objMes->format("d")."</td>";
            if($objMes->format("N")==6){
                $tablaMes .="</tr>";
            }
            $objMes->modify("+1 day");
        }
        $tablaMes .="</table>";
        $tablaMes .="</TD></TR></TABLE>";
        //$this->data['calendario'] = $tablaMes;//$this->template->load($this->template_file, "calendario_festivo/calendario", $this->data);
        return $tablaMes;
      }else{
          redirect(base_url().'index.php/auth/login');
      } 
    }

    /*
    * Método mostrarMesArr
    * Réplica de mostrarMes, que consulta solo una vez la BD
    * Muestra el calendario del mes indicado o los meses del año
    * @author Felipe R. Puerto 
    * @param $festivosanuales Arreglo de los festivos del mes según BD
    * @param $aaaa: Año del que se consulta el calendario.
    * @param $mm: Mes del que se consulta el calendario.
* @since 2013-11-15	*/
    function mostrarMesArr($festivosanuales,$aaaa,$mm=0){
      if ($this->ion_auth->logged_in() ){
        $objMes = new DateTime($aaaa."-".$mm."-1"); //print " (".$this->arrMeses[intval($objMes->format("m"))]." ".$objMes->format("Y").") ";
        $tm = intval($objMes->format("m"));
        $tY = intval($objMes->format("Y"));
        $diasInicioSemana = ($objMes->format("N"))%7;
        $objMes->modify("-".$diasInicioSemana." day");
        $claseDia = "diaHabil";
        $tablaMes = "";
        $tablaMes .="<TABLE align=left border=1><TR><TD>";
        $tablaMes .=" (".$this->arrMeses[$tm]." ".$tY.") ";
        $tablaMes .="</TD></TR><TR><TD>";
        $tablaMes .="<table class=calendario>";
        $tablaMes .="<tr class=cabeza><td></td><td>D</td><td>L</td><td>M</td><td>M</td><td>J</td><td>V</td><td>S</td></tr>";
        for($i=0;(($objMes->format("m")<=$mm && $objMes->format("Y")==$aaaa) || ($objMes->format("Y")<$aaaa) );$i++){ //print " (".$objMes->format("N")." ";
            if(($objMes->format("N"))==7){
                $objMes->modify("+4 day");
                $tablaMes .="<tr><td class=semana>".$objMes->format("W")."</td>";
                $objMes->modify("-4 day");
            }
            if(intval($objMes->format("m"))<$mm || ($objMes->format("m")==12 && $mm==1)){
                $claseDia="diaGris"; //dias finales del mes anterior
            }else{
                $this->ponerFecha($objMes->format("Y"),$objMes->format("m"),$objMes->format("d"));    //if($objMes->format("N")==7){    //echo " ".__LINE__.": "; print_r($festivosanuales);
                $esFestivo = $this->esFestivoArreglo($festivosanuales);
                if($esFestivo===true){
                        $claseDia="diaFestivo";
                }elseif($esFestivo===1){
                        $claseDia="diaFestivo style='font-size:75%;text-decoration:overline;font-style:italic' ";
                }elseif($esFestivo===2){
                        $claseDia="diaHabil style='font-size:75%;text-decoration:overline;font-style:italic' ";
                }else{
                        $claseDia="diaHabil";
                }
            }
            $tablaMes .="<td class=".$claseDia." content='".$objMes->format("Y-m-d")."'>".$objMes->format("d")."</td>";
            if($objMes->format("N")==6){
                $tablaMes .="</tr>";
            }
            $objMes->modify("+1 day");
        }
        $tablaMes .="</table>";
        $tablaMes .="</TD></TR></TABLE>";
        
        //$this->data['calendario'] = $tablaMes;    //$this->template->load($this->template_file, "calendario_festivo/calendario", $this->data);
        return $tablaMes;
      }else{
          redirect(base_url().'index.php/auth/login');
      }  
    }


    /*
    * Método arrFestivos
    * Genera un arreglo con los días no hábiles
    * @author Felipe R. Puerto :: Thomas MTI
    * @since 3 II 2014
    * @param $aaaa: Año del que se consulta el calendario.
    */
    function arrfestivos($aaaa=0){
      if ($this->ion_auth->logged_in() ){  
        $arrFestivos= array();
        if($aaaa==0){
            $aaaa = date("Y");
        }
        $festivosanuales = $this->traerfestivoarreglo($aaaa);
        $objMes = new DateTime(($aaaa-1)."-1-1");        //echo " oMM:".$objMes->format("Y"),$objMes->format("m"),$objMes->format("d")." != ".($aaaa+2);  //for($i=0;(($objMes->format("m")<=$mm && $objMes->format("Y")==$aaaa) || ($objMes->format("Y")<$aaaa));$i++){  //for($i=0;($objMes->format("Y")<($aaaa+2) && $objMes->format("m")!=1 && $objMes->format("d")!=1);$i++){
        for($i=0;($objMes->format("Y")<($aaaa+2));$i++){ //echo " a:".$aaaa;  //echo " oM:".$objMes->format("Y"),$objMes->format("m"),$objMes->format("d");
                $this->ponerFecha($objMes->format("Y"),$objMes->format("m"),$objMes->format("d"));
                $esFestivo = $this->esFestivoArreglo($festivosanuales);
                if($esFestivo===true || $esFestivo===1){//echo " esF"; //$arrFestivos[]=$this->objFecha->format("Y-m-d");
                    $arrFestivos[]=$this->objFecha->format("Y-n-j");
                }
            $objMes->modify("+1 day");//echo " <br>";
        }//echo " <p>";//print_r($arrFestivos);
        return $arrFestivos;
      }else{
          redirect(base_url().'index.php/auth/login');
      }  
    }
    
    
    /*
    * M�todo butcher
    * F�rmula desarrollada por algoritmo de Butcher para obtener el domingo de resurecci�n a partir de esto obtener jueves, viernes santo y otros 3 festivos
    * @author Felipe R. Puerto 
    * @param $anno: A�o del que se averiguan estas fechas.
    * @return boolean: Verdadero si la fecha de la instancia coincide con el jueves o viernes santo
    * @since 2013-11-15  	
    */
    function butcher($anno){
      if ($this->ion_auth->logged_in() ){  
        $a = $anno%19;  
        $b = ($anno - ($anno%100))/100;
        $c = $anno%100;
        $d = ($b - ($b%4))/4;
        $e = $b%4;
        $f = (($b+8)-(($b+8)%25))/25;
        $g = (($b-$f+1)-(($b-$f+1)%3))/3;
        $h = ((19*$a)+$b-$d-$g+15)%30;
        $i = ($c-($c%4))/4;
        $k = $c%4;
        $l = (32+2*$e+2*$i-$h-$k)%7;
        $m = (($a+(11*$h)+(22*$l)) - (($a+(11*$h)+(22*$l)) % 451) )/451;
        $n = $h + $l - (7*$m) + 114;
        $mmes = ($n - ($n%31))/31; 
        $ddia = 1 + ($n%31);
        //echo" Domingo de resurecci�n: a:".$anno." m:".$mmes." d:".$ddia;
        $pascua  = new DateTime($anno."-".$mmes."-".$ddia);
        return $pascua;
      }else{
          redirect(base_url().'index.php/auth/login');
      }  
    }

    /*
    * Método festivobd
    * Consulta los d�as festivos o h�biles predefinidos por el usuario por BD
    * @author Felipe R. Puerto 
    * @param $aaaa: A�o a consultar.
    * @param $mm: Mes a consultar.
    * @param $dd: D�a a consultar.
    * @return Integer: 1 si se defini� como festivo 2 si se defini� como h�bil
    * @since 2013-11-15 	*/
    function festivobd(){
      if ($this->ion_auth->logged_in() ){  
        $matriz = $this->calendariofestivo_model->festivo($this->objFecha);        //echo " :".$this->objFecha->format("Y-m-d"). " >>".$matriz[0]['ESTADO']; //. print_r($matriz[0]);
        foreach($matriz as $row)
        {
           return $row['ESTADO'];
        }   //return $matriz[0]['ESTADO'];        //return $matriz;
      }else{
          redirect(base_url().'index.php/auth/login');
      }  
    }

    
    /*
    * Método traerfestivoarreglo
    * Réplica de festivobd para consultar todo el año en vez de día por día
    * Consulta los días festivos o hábiles predefinidos por el usuario por BD
    * @author Felipe R. Puerto 
    * @param $aaaa: Año a consultar.
    * @param $mm: Mes a consultar.
    * @param $dd: Día a consultar.
    * @return Integer: 1 si se definió como festivo 2 si se definió como hábil
    * @since 2013-11-15 	
    */
    function traerfestivoarreglo($aaaa){
      if ($this->ion_auth->logged_in() ){  
        $matriz = $this->calendariofestivo_model->festivosanuales($aaaa);
        return $matriz;
      }else{
          redirect(base_url().'index.php/auth/login');
      }  
    }

    /*
    * Método festivocoincide
    * Verifica si el objFecha coincide con alguna fecha del arreglo.
    * @author Felipe R. Puerto 
    * @param array $arrFestivos Arreglo de festivos Arreglo de días festivos del año establecidos por el usuario
    * @return Integer: 1 si se definió como festivo 2 si se definió como hábil
    * @since 2013-11-15 	*/
    function festivocoincide($arrFestivos){
      if ($this->ion_auth->logged_in() ){  
        foreach($arrFestivos as $row)
        {
           if($row['DIA'] == $this->objFecha->format('d') && $row['MES'] == $this->objFecha->format('m') && $row['ANNO'] == $this->objFecha->format('Y') ){
               return $row['ESTADO'];
           }
        }
      }else{
          redirect(base_url().'index.php/auth/login');
      }  
    }    
    
    /**
     * Método sumarDiasHabiles
     * Encuentra el día hábil n posterior a la fecha dada
     * @author Felipe R. Puerto :: Thomas MTI
     * @since 2013-11-15
     * 
     * @param String $fecha Cadena de fecha en formato Y-m-d
     * @param Integer $sumarCuantos Número de días hábiles a sumar.
     * @param String $sma Letra que indica si suman días (hábiles) o semanas, meses y años hábiles.
     * @return String Cadena con la fecha del día hábil requerido.
     */
    function sumarDiasHabiles($fecha,$sumarCuantos,$sma="d"){
      if ($this->ion_auth->logged_in() ){  
        $f = explode("-",$fecha);
        $d=0;
        $objDiaH = new DateTime($f[0]."-".$f[1]."-".$f[2]);
        switch($sma){
            case "d":
            case "dias":
            for($i=1;$d<$sumarCuantos;$i++){
                $objDiaH->modify("+1 day");
                $this->ponerFecha($objDiaH->format("Y"),$objDiaH->format("m"),$objDiaH->format("d"));
                if(!$this->diaFestivo()){
                    $d++;
                }
            }
            break;

            case "s":
            case "semanas":
                $objDiaH->modify("+$sumarCuantos weeks");    //echo " GG:".$objDiaH->format("Y").", ".$objDiaH->format("m").", ".$objDiaH->format("d");
                $this->ponerFecha($objDiaH->format("Y"),$objDiaH->format("m"),$objDiaH->format("d"));
                while($this->diaFestivo()){
                    $objDiaH->modify("+1 day");
                    $this->ponerFecha($objDiaH->format("Y"),$objDiaH->format("m"),$objDiaH->format("d"));
                }
            break;

            case "m":
            case "month":
                $objDiaH->modify("+$sumarCuantos month");
                $this->ponerFecha($objDiaH->format("Y"),$objDiaH->format("m"),$objDiaH->format("d"));
                while($this->diaFestivo()){
                    $objDiaH->modify("+1 day");
                    $this->ponerFecha($objDiaH->format("Y"),$objDiaH->format("m"),$objDiaH->format("d"));
                }

            break;

            case "a":
            case "año":
                $objDiaH->modify("+$sumarCuantos year");
                $this->ponerFecha($objDiaH->format("Y"),$objDiaH->format("m"),$objDiaH->format("d"));
                while($this->diaFestivo()){
                    $objDiaH->modify("+1 day");
                    $this->ponerFecha($objDiaH->format("Y"),$objDiaH->format("m"),$objDiaH->format("d"));
                }
            break;
        }
        $cadenaFecha = $objDiaH->format("Y")."-".$objDiaH->format("m")."-".$objDiaH->format("d");
        return $cadenaFecha;
      }else{
          redirect(base_url().'index.php/auth/login');
      }  
    }
}
?>
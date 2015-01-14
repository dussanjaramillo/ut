<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * @author      Iván viña
 * @Ajustes	:		Felipe Camacho [www.cogroupsas.com]
 *
 **/
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title><?php echo ( isset( $title ) ) ? $title.' || '.WEBSITE_NAME.' || Sistema de Información de Recaudo, Cartera y Cobro' : WEBSITE_NAME.' || Sistema de Información de Recaudo, Cartera y Cobro'; ?></title>
<?php
	// Add any keywords
	echo ( isset( $keywords ) )     ? meta('keywords', $keywords) : '';
	// Add a discription
	echo ( isset( $description ) )  ? meta('description', $description) : '';
	// Add a robots exclusion
	echo ( isset( $no_robots ) )    ? meta('robots', 'noindex,nofollow') : '';
	
	// Favicon
	echo link_tag( array( 'href' => 'img/favicon.ico', 'media' => 'all', 'rel' => 'shortcut icon' ) ) . "\n";
	
	// Always add the main stylesheet
	echo link_tag( array( 'href' => 'css/bootstrap.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";
	echo link_tag( array( 'href' => 'css/font-awesome.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";
	echo link_tag( array( 'href' => 'css/gris/jquery-ui-1.10.3.custom.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";
	
	// Add any additional stylesheets
	if( isset( $style_sheets ) ) {
			foreach( $style_sheets as $href => $media ) {
					echo link_tag( array( 'href' => $href, 'media' => $media, 'rel' => 'stylesheet' ) ) . "\n";
			}
	}
	
	echo link_tag( array( 'href' => 'css/style.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";
	
	// jQuery  always loaded
	echo script_tag( 'js/jquery-1.9.1.js' ) . "\n";
		echo script_tag( 'js/jquery.ui.datepicker-es.min.js' ) . "\n";
	echo script_tag( 'js/jquery-ui-1.10.2.custom.min.js' ) . "\n";
	echo script_tag( 'js/bootstrap.min.js' ) . "\n";
	
	// Add any additional javascript
	if( isset( $javascripts ) ) {
			for( $x=0; $x<=count( $javascripts )-1; $x++ ) {
					echo script_tag( $javascripts["$x"] ) . "\n";
			}
	}
	
	// Add anything else to the head
	echo ( isset( $extra_head ) ) ? $extra_head : '';
?>
</head>

<body>
<?php if ($this->ion_auth->logged_in()) { ?>
<!--
    |
    | ::::::: Header logo and title
    |
-->
<div class="masthead">
  <div class="header"> <?php echo anchor('', img( array( 'src' => 'img/logo.png', 'alt' => WEBSITE_NAME ) ) ) . "\n"; ?>
    <div class="logo-separator"></div>
    <div class="large">Sistema de Información de Recaudo, Cartera y Cobro</div>
  </div>
</div>
<!-- /.masterhead --> 
<!--
    |
    | ::::::: Menu General
    |
-->
<div class="navbar navbar-default navbar-static-top">
  <div class="container">
    <div class="navbar-header">
    
    <!-- Primer Nivel -->
    <ul class="nav">
      <li class="button-inicio"> <?php echo  anchor('/', '<i class="fa fa-home fa-fw fa-lg"></i><span> Inicio</span>'); ?> </li>
      <?php  if ($nav_menus ) : ?>
      <?php  $i = 1; ?>
      <?php foreach($nav_macroprocesos as $key_macroproceso => $value_macroproceso): ?>
      <li class="dropdown menu-large"> 
        
        <!-- <b class="caret"></b> --> 
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-<?php echo $value_macroproceso['ICONOMACROPROCESO']; ?>"></i><span><?php echo $value_macroproceso['NOMBREMACROPROCESO']; ?></span></a> 
        
        <!-- Segundo nivel  -->
        <ul class="dropdown-menu megamenu row">
          <?php foreach($nav_aplicaciones as $key_aplicacion => $value_aplicacion): ?>
          <?php if ($value_aplicacion['CODPROCESO']==$key_macroproceso): ?>
          <!-- <li class="dropdown-submenu"> -->
          <div class="span<?php echo (count($nav_macroprocesos) > 5)?2:3; ?>"> 
            
            <!-- Columnas Titulos--> 
            <!-- <li><i class="fa fa-<?php //echo $value_aplicacion['ICONOAPLICACION']; ?>"></i> <?php //echo $value_aplicacion['NOMBREAPLICACION']; ?></li> -->
            <li class="colum_title"><i class="fa fa-asterisk "></i> <?php echo $value_aplicacion['NOMBREAPLICACION']; ?></li>
            <?php foreach($nav_modulos as $key_modulo => $value_modulo): ?>
            <?php if ($value_modulo['APLICACIONID']==$key_aplicacion): ?>
            <li class="dropdown-submenu"> 
              
              <!-- Columnas Links --> 
              <a tabindex="-1" href="<?php echo base_url().'index.php/'.$value_modulo['MODULOURL'];  ?>"><i class="fa xfa-<?php echo $value_modulo['ICONOMODULO']; ?>"></i> <?php echo $value_modulo['NOMBREMODULO']; ?></a> 
              
              <!-- Tercer Nivel   -->
              <ul class="dropdown-menu arrow_box">
                <?php foreach($nav_menus as $key_menu => $value_menu): ?>
                <?php if ($key_modulo==$value_menu['MODULOID']): ?>
                <li> 
                  <!-- <a href="<?php //echo base_url().'index.php/'.$value_menu['MODULOURL'].'/'.$value_menu['URL'];  ?>"><i class="fa fa-<?php //echo $value_menu['ICONOMENU']; ?>"></i> <?php //echo $value_menu['NOMBREMENU'];  ?></a> --> 
                  <a href="<?php echo base_url().'index.php/'.$value_menu['MODULOURL'].'/'.$value_menu['URL'];  ?>"><i class="fa fa-asterisk"></i> <?php echo $value_menu['NOMBREMENU'];  ?></a> </li>
                <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            </li>
            <?php endif; ?>
            <?php endforeach; ?>
          </div>
          <!-- </li> -->
          <?php endif; ?>
          <?php endforeach; ?>
        </ul>
      </li>
      <?php endforeach; ?>
      <?php endif ?>
      <!--
            |
            | ::::::: Menu Admin
            |
        -->
      <?php if ($this->ion_auth->is_admin()) { ?>
      <li class="dropdown menu-large"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gear"></i> <span>Administración </span></a>
        <ul class="dropdown-menu megamenu row">
          <div class="span2">
            <li> <?php echo  anchor('macroprocesos', '<i class="fa fa-hdd-o"></i> Macroprocesos'); ?> </li>
            <li> <?php echo  anchor('aplicaciones', '<i class="fa fa-pencil"></i> Aplicaciones'); ?> </li>
            <li> <?php echo  anchor('modulos', '<i class="fa fa-th"></i> Módulos' ); ?></li>
            <li> <?php echo  anchor('menus', '<i class="fa fa-bars"></i> Menús'); ?> </li>
            <li> <?php echo  anchor('iconos', '<i class="fa fa-info-circle"></i> Íconos'); ?> </li>
			<li> <?php echo  anchor('regionales', '<i class="fa fa-university"></i> Regionales'); ?> </li>
            <li class="divider"></li>
            <li> <?php echo  anchor('usuarios', '<i class="fa fa-user"></i> Usuarios'); ?> </li>
            <li> <?php echo  anchor('cargos', '<i class="fa fa-user-md"></i>  Cargos'); ?> </li>
            <li> <?php echo  anchor('grupos', '<i class="fa fa-users"></i>  Grupos'); ?> </li>
            <li class="dropdown-submenu"><a tabindex="-1" href="#"><i class="fa fa-book"></i> Parametrizar</a>
              <ul class="dropdown-menu arrow_box">
                <li><?php echo  anchor('tiposempresa', '<i class="fa fa-suitcase"></i>  Tipos de empresa'); ?></li>
                <li><?php echo  anchor('tiposdocumento', '<i class="fa fa-barcode"></i> Tipos de documento'); ?></li>
                <li><?php echo  anchor('ciiu', '<i class="fa fa-keyboard-o"></i> CIIU'); ?></li>
                <li class="divider"></li>
                <li> <?php echo  anchor('calendario_festivo', '<i class="fa fa-asterisk"></i> Calendario Festivo'); ?> </li>
                <li> <?php echo  anchor('correo/parametros/', '<i class="fa fa-asterisk"></i> Correo'); ?> </li>
              </ul>
            </li>
            <li class="dropdown-submenu"> <a href="#" class=""><i class="fa fa-user"></i> <?php echo $this->session->userdata('username'); ?> </a>
              <ul class="dropdown-menu arrow_box">
                <li> <?php echo  anchor('usuarios/edit_user/'.$this->session->userdata('user_id'), '<i class="fa fa-edit"></i> Editar mis datos'); ?></li>
                <li> <?php echo  anchor('usuarios/change_password/'.$this->session->userdata('user_id'), '<i class="fa fa-key"></i> Cambiar contraseña'); ?> </li>
              </ul>
            </li>
            <li id="button-logout"> <?php echo  anchor('auth/logout', '<i class="fa fa-sign-out"></i> Desconectarse'); ?> </li>
          </div>
        </ul>
      </li>
      <?php } else { ?>
      <li class="dropdown menu-large"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gear"></i> <span>Administración </span></a>
        <ul class="dropdown-menu megamenu">
        	<div class="span3">
          <li class="dropdown-submenu"> <a href="#" class=""><i class="fa fa-user"></i> <?php echo $this->session->userdata('username'); ?> </a>
            <ul class="dropdown-menu arrow_box">
              <li> <?php echo  anchor('usuarios/edit_user/'.$this->session->userdata('user_id'), '<i class="fa fa-edit"></i> Editar mis datos'); ?></li>
              <li> <?php echo  anchor('usuarios/change_password/'.$this->session->userdata('user_id'), '<i class="fa fa-key"></i> Cambiar contraseña'); ?> </li>
            </ul>
          </li>
          <li id="button-logout"> <?php echo  anchor('auth/logout', '<i class="fa fa-sign-out"></i> Desconectarse'); ?> </li>
          </div>
        </ul>
        
      </li>
      </div>
      <?php } ?>
    </ul>
    <!-- /.nav -->
  </div>
  <!-- Colapsable--> 
</div>
</div>
<?php } else { ?>
<div class="navbar  navbar-fixed-top">
  <div class="navbar-inner">
    <div class="header">
      <div class="container"> <?php echo anchor('', img( array( 'src' => 'img/logo.png', 'alt' => WEBSITE_NAME ) ) )  . "\n"; ?>
        <div class="logo-separator"></div>
        <div class="large">Sistema gestión de recaudo y cobro</div>
      </div>
    </div>
    <div class="container">
      <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <div class="nav-collapse collapse">
        <ul class="nav">
        </ul>
      </div>
      <!--/.nav-collapse --> 
    </div>
  </div>
</div>
<!--/.fixed navbar -->
<?php } ?>
<!--
        |
        | ::::::: Content
        |
    -->
<div class="container">
  <div id="contents">
    <?php  if ($this->ion_auth->logged_in()){  ?>
    <p> <?php echo $contents ?></p>
    <?php } ?>
  </div>
</div>
<!-- /container --> 
<!--
        |
        | ::::::: Foooter
        |
    -->
<div class="container">
  <div class="footer">
    <p>Copyright (c) <?php echo date('Y') . ' &bull; SENA. &bull; '; ?></p>
  </div>
</div>
<?php
	// Insert any HTML before the closing body tag if desired
	if( isset( $final_html ) ) {
			echo $final_html;
	}
	// Add the cookie checker
	if( isset( $cookie_checker ) ) {
			echo $cookie_checker;
	}
	
	// Add any javascript before the closing body tag
	if( isset( $dynamic_extras ) ) {
			echo '<script>';
			echo $dynamic_extras;
			echo '</script>';
	}
?>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
	window.location.hash="no";
	window.location.hash="Again-No" //chrome
	window.onhashchange=function(){
		window.location.hash="no";
	}
});
</script>
</body>
</html>
<?php
/* End of file main.php */
/* Location: /application/views/templates/main.php */



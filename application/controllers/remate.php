<?php

class Remate extends MY_Controller {
    
    function certificadolibertad(){
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('aplicaciondepago/manage')) {

                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                   $this->data['javascripts']= array(
                        'js/tinymce/tinymce.jquery.min.js',
                );
                //template data
                $this->data[] = array();
                $this->template->set('title', 'Remate');
                $this->data['title'] = 'Certificado de Tradicion y Libertad';
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'remate/certificadolibertad', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
}


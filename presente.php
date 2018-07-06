<?php

class Presente extends CI_Controller {
 
    function __construct()
    {

        parent::__construct();
        $this->load->model('presentes');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');  

        $this->_error_prefix = '<p class="lead">';
        $this->_error_suffix = '</p>';
/*
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
*/

    }

    public function testeController()
    {

        $this->load->model('loginx', 'login');
        $this->login->logged();

        $this->load->model('menu');
        $this->load->model('pedidox');       

        $idade    = $this->uri->segment(2);
        $sexo     = $this->uri->segment(3);
        $idPedido = $this->session->userdata('idPedido');
        $pedido   = $this->pedidox->getPedido($idPedido);

        $this->db->select();
        $this->db->from('presentes');
        $this->db->where(array('idPedido' => $idPedido));
        $this->db->where(array('filho_num' => 1));
        $this->db->order_by('nProduto', "desc");
        $this->db->group_by('idProduto');
        $d = $this->db->get()->result_array();

        if (array_key_exists(0, $d)) {

            $max = count($d);
            for($i=0; $i<$max; $i++) {

                $idProduto  = str_replace("'", "", $d[$i]['idProduto']);
                $idProduto  = str_replace(" ", "", $idProduto);              

                if (strlen($idProduto) > 1) {
                
                    $dado2  = $this->presentes->pegaListaUnitario($idProduto);             

                    if (is_array($dado2) && array_key_exists('img', $dado2)) {                

                        if (array_key_exists(0, $dado2['img'])) {

                            $dado['img'][]         = $dado2['img'][0];
                            $dado['label'][]       = utf8_encode($dado2['label'][0]);
                            $dado['desc'][]        = $dado2['desc'][0];
                            $dado['finalPrice'][]  = $dado2['finalPrice'][0];
                            $dentity_id            = str_replace("'", "", $dado2['entity_id'][0]);
                            $dado['entity_id'][]   = str_replace(' ', '', $dentity_id);
                                $x++;

                        }                    

                    }

                }

            }            

        } else {

            if ($idade == 0) {

                if ($sexo == 'fem') {

                    $categoria = '12 meses Feminino';

                } else {

                    $categoria = '12 meses Masculino';

                }

            } else if ($idade > 0 && $idade < 3) {

                if ($sexo == 'fem') {
                
                    $categoria = '1 a 2 anos Feminino';

                } else {
                
                    $categoria = '1 a 2 anos Masculino';

                }

            } else if ($idade > 2 && $idade < 5) {

                if ($sexo == 'fem') {

                    $categoria = '3 a 4 anos Feminino';

                } else {

                    $categoria = '3 a 4 anos Masculino';

                }                

            } else if ($idade > 4 && $idade < 7) {

                if ($sexo == 'fem') {

                    $categoria = '5 a 6 anos Feminino';

                } else {

                    $categoria = '5 a 6 anos Masculino';

                }

            } else if ($idade > 6 && $idade < 9) {

                if ($sexo == 'fem') {

                    $categoria = '7 a 8 anos Feminino';

                } else {

                    $categoria = '7 a 8 anos Masculino';

                }

            } else if ($idade > 8 && $idade < 11) {

                if ($sexo == 'fem') {

                    $categoria = '9 a 10 anos Feminino';

                } else {

                    $categoria = '9 a 10 anos Masculino';

                }

            } else if ($idade > 10) {

                if ($sexo == 'fem') {

                    $categoria = '11 ou mais Feminino';

                } else {

                    $categoria = '11 ou mais Masculino';

                }

            }

            $dado           = array();            
            $dado           = $this->presentes->pegaLista($categoria);           
            $max            = count($dado['label']);

            for($i=0; $i<$max; $i++) {

                $dado['label'][$i] = utf8_encode($dado['label'][$i]);                
                $dado['desc'][$i]  = $dado['desc'][$i];

            }
            
        }

        $dado['aniversariante'] = $pedido[0]['aniversariante'];
        $dado["headerx"] = $this->menu->header();
        $dado["footerx"] = $this->menu->footer();
        
        $this->load->view('lista-presentes', $dado);

    }


    public function testeController2()
    {

        $this->load->model('loginx', 'login');
        $this->login->logged();

        $this->load->model('menu');
        $this->load->model('pedidox');

        $idade    = $this->uri->segment(2);
        $sexo     = $this->uri->segment(3);
        $idPedido = $this->session->userdata('idPedido');
        $pedido   = $this->pedidox->getPedido($idPedido);

        $this->db->select();
        $this->db->from('presentes');
        $this->db->where(array('idPedido' => $idPedido));
        $this->db->where(array('filho_num' => 2));
        $this->db->order_by('nProduto', "desc");
        $this->db->group_by('idProduto');
        $d = $this->db->get()->result_array();

        if (array_key_exists(0, $d)) {

            $max = count($d);
            for($i=0; $i<$max; $i++) {

                $idProduto  = str_replace("'", "", $d[$i]['idProduto']);
                $idProduto  = str_replace(" ", "", $idProduto);              

                if (strlen($idProduto) > 1) {
                
                    $dado2  = $this->presentes->pegaListaUnitario($idProduto);          

                    if (is_array($dado2) && array_key_exists('img', $dado2)) {                

                        if (array_key_exists(0, $dado2['img'])) {

                            $dado['img'][]         = $dado2['img'][0];
                            $dado['label'][]       = utf8_encode($dado2['label'][0]);
                            $dado['desc'][]        = $dado2['desc'][0];
                            $dado['finalPrice'][]  = $dado2['finalPrice'][0];
                            $dentity_id            = str_replace("'", "", $dado2['entity_id'][0]);
                            $dado['entity_id'][]   = str_replace(' ', '', $dentity_id);
                                $x++;

                        }                    

                    }

                }

            }            

        } else {

            if ($idade == 0) {

                if ($sexo == 'fem') {

                    $categoria = '12 meses Feminino';

                } else {

                    $categoria = '12 meses Masculino';

                }

            } else if ($idade > 0 && $idade < 3) {

                if ($sexo == 'fem') {
                
                    $categoria = '1 a 2 anos Feminino';

                } else {
                
                    $categoria = '1 a 2 anos Masculino';

                }

            } else if ($idade > 2 && $idade < 5) {

                if ($sexo == 'fem') {

                    $categoria = '3 a 4 anos Feminino';

                } else {

                    $categoria = '3 a 4 anos Masculino';

                }                

            } else if ($idade > 4 && $idade < 7) {

                if ($sexo == 'fem') {

                    $categoria = '5 a 6 anos Feminino';

                } else {

                    $categoria = '5 a 6 anos Masculino';

                }

            } else if ($idade > 6 && $idade < 9) {

                if ($sexo == 'fem') {

                    $categoria = '7 a 8 anos Feminino';

                } else {

                    $categoria = '7 a 8 anos Masculino';

                }

            } else if ($idade > 8 && $idade < 11) {

                if ($sexo == 'fem') {

                    $categoria = '9 a 10 anos Feminino';

                } else {

                    $categoria = '9 a 10 anos Masculino';

                }

            } else if ($idade > 10) {

                if ($sexo == 'fem') {

                    $categoria = '11 ou mais Feminino';

                } else {

                    $categoria = '11 ou mais Masculino';

                }

            }

            $dado           = array();            
            $dado           = $this->presentes->pegaLista($categoria);           
            $max            = count($dado['label']);

            for($i=0; $i<$max; $i++) {

                $dado['label'][$i] = utf8_encode($dado['label'][$i]);                
                $dado['desc'][$i]  = $dado['desc'][$i];

            }
            
        }

        $dado['aniversariante'] = $pedido[0]['aniversariante2'];
        $dado["headerx"] = $this->menu->header();
        $dado["footerx"] = $this->menu->footer();
        
        $this->load->view('lista-presentes2', $dado);

    }

    //function edicaoController($categoria = 'All')
    public function edicaoController()
    {

        $this->load->model('loginx', 'login');
        $this->login->logged();

        $this->load->model('menu');

        $dado = $this->presentes->pegaLista('12 meses Masculino');        
        $dado = $this->enxugarArray('12 meses Feminino', $dado);
        $dado = $this->enxugarArray('1 a 2 anos Masculino', $dado);
        $dado = $this->enxugarArray('1 a 2 anos Feminino', $dado);
        $dado = $this->enxugarArray('3 a 4 anos Masculino', $dado);        
        $dado = $this->enxugarArray('3 a 4 anos Feminino', $dado);        
        $dado = $this->enxugarArray('5 a 6 anos Masculino', $dado);        
        $dado = $this->enxugarArray('5 a 6 anos Feminino', $dado);
        $dado = $this->enxugarArray('7 a 8 anos Masculino', $dado);        
        $dado = $this->enxugarArray('7 a 8 anos Feminino', $dado);        
        $dado = $this->enxugarArray('9 a 10 anos Masculino', $dado);        
        $dado = $this->enxugarArray('9 a 10 anos Feminino', $dado);        
        $dado = $this->enxugarArray('11 ou mais Masculino', $dado);        
        $dado = $this->enxugarArray('11 ou mais Feminino', $dado);

        $dado["headerx"] = $this->menu->header();
        $dado["footerx"] = $this->menu->footer();

        $this->load->view('lista-presentes-edicao', $dado);

    }


    public function edicaoController2()
    {

        $this->load->model('loginx', 'login');
        $this->login->logged();

        $this->load->model('menu');

        $dado = $this->presentes->pegaLista('12 meses Masculino');        
        $dado = $this->enxugarArray('12 meses Feminino', $dado);
        $dado = $this->enxugarArray('1 a 2 anos Masculino', $dado);
        $dado = $this->enxugarArray('1 a 2 anos Feminino', $dado);
        $dado = $this->enxugarArray('3 a 4 anos Masculino', $dado);        
        $dado = $this->enxugarArray('3 a 4 anos Feminino', $dado);        
        $dado = $this->enxugarArray('5 a 6 anos Masculino', $dado);        
        $dado = $this->enxugarArray('5 a 6 anos Feminino', $dado);
        $dado = $this->enxugarArray('7 a 8 anos Masculino', $dado);        
        $dado = $this->enxugarArray('7 a 8 anos Feminino', $dado);        
        $dado = $this->enxugarArray('9 a 10 anos Masculino', $dado);        
        $dado = $this->enxugarArray('9 a 10 anos Feminino', $dado);        
        $dado = $this->enxugarArray('11 ou mais Masculino', $dado);        
        $dado = $this->enxugarArray('11 ou mais Feminino', $dado);

        $dado["headerx"] = $this->menu->header();
        $dado["footerx"] = $this->menu->footer();

        $this->load->view('lista-presentes-edicao2', $dado);

    }


    public function enxugarArray($categoria, $dado2)
    {

        $d                   = $this->presentes->pegaLista($categoria);
        $dado2['entity_id']  = array_merge($dado2['entity_id'], $d['entity_id']);
        $dado2['img']        = array_merge($dado2['img'], $d['img']);        
        $dado2['label']      = array_merge($dado2['label'], $d['label']);
        $dado2['desc']       = array_merge($dado2['desc'], $d['desc']);
        $dado2['categoria']  = array_merge($dado2['categoria'], $d['categoria']);
        $dado2['switch']     = array_merge($dado2['switch'], $d['switch']);
        $dado2['finalPrice'] = array_merge($dado2['finalPrice'], $d['finalPrice']);

        return $dado2;

    }


    public function incluir_presente()
    {

        $this->load->model('loginx', 'login');
        $this->login->logged();

        if ($this->input->post('formdata', true)) {

          $presentes  = explode('&',$this->input->post('formdata'));
          $max        = count($presentes);
          $idPresente = $campos = array();       

          for($i=0; $i<$max; $i++) {

            $param_convitesx = explode('=',$presentes[$i]);

            if (strstr($param_convitesx[0], '%5B')) {

                $undr               = explode('_',$param_convitesx[0]);
                $param_convitesx[0] = $undr[0].'_'.$undr[1].'_';

                if ($i % 3 == 0) {
                    
                    $idPresente[] = $undr[2];

                }             
                
                $part               = explode('%5B', $param_convitesx[0]);
                $indice             = $part[0];
                $part               = null;
                $campos[$indice][]  = base64_decode(urldecode($param_convitesx[1]));

            } else {

                $undr               = explode('_',$param_convitesx[0]);                
                $param_convitesx[0] = $undr[0].'_'.$undr[1].'_';

                if ($i % 3 == 0) {
                  
                    $idPresente[] = $undr[2];

                }

                $campos[$param_convitesx[0]][] = base64_decode($param_convitesx[1]);

            }            
            
            $param_convitesx = null;

          }             

          $_POST = $campos;        

        }

        $config =   array(

                        array(
                           'field' => 'p_label_[]',
                           'label' => 'Label',
                           'rules' => 'required|xss_clean'     
                        ), 
                        
                        array(
                           'field' => 'p_price_[]',
                           'label' => 'Preço',
                           'rules' => 'required|xss_clean'     
                        ),

                        array(
                           'field' => 'p_desc_[]',
                           'label' => 'Desc',
                           'rules' => 'required|xss_clean'     
                        ),

                        array(
                           'field' => 'p_prod_[]',
                           'label' => 'Produto',
                           'rules' => 'required|xss_clean'     
                        )
                    );

        $this->form_validation->set_rules($config); 
        $this->form_validation->set_error_delimiters('<p style="font-weight: bold;color: #5d1613;">', '</p>');           
     
        if ($this->form_validation->run() === false) {} else {

            $this->load->model('aniversario');
            $this->load->model('pedidox');
            $this->load->model('cliente');

            $idPedido    = $this->session->userdata('idPedido');
            $dadosPedido = $this->pedidox->getPedido($idPedido);


            $car       = 'abcdefghijlmnopqrstuvxywzABCDEFGHIJLMNOPQRSTUVXZYW0123456789'; 
            $num_car   = 8;
            $max       = strlen($car)-1;
            $hash      = null;

            for($i=0; $i < $num_car; $i++) { 
                $hash .= $car[mt_rand(0, $max)];
            }

            $hash    = urlencode($hash);
            $hashUrl = base_url() . '????/'.$hash;

            $this->db->select();
            $this->db->from('lista_presente_comprador');
            $this->db->where(array('idPedido' => $idPedido));
            $this->db->order_by('idPresenteComprador', "desc");
            $d = $this->db->get()->result_array();

            if (array_key_exists(0, $d)) {} else {

                $data = array(
                                'idPedido'  => $idPedido,
                                'hashUrl'   => $hashUrl
                        );

                $this->db->insert('lista_presente_comprador', $data);

            }

            $nome         = urlencode($dadosPedido[0]['aniversariante']);
            $email        = $this->session->userdata('username');
            $usuario      = $this->cliente->getUsuario($email);            
            $senha        = base64_decode($usuario[0]['password']);
            $senha        = md5('Lg'.$senha) .':Lg';            
            $data_aniver  = $dadosPedido[0]['dt_aniversario'];
            $hora         = $dadosPedido[0]['hora'];
            $e            = explode(',', $dadosPedido[0]['endereco_festa']);

            if (array_key_exists(0, $e) && array_key_exists(1, $e)) {

                $ender   = $e[0].' '.$e[1];

            } else {

                $ender   = $dadosPedido[0]['endereco_festa'].' '.$dadosPedido[0]['numero'];

            }

            $ender = urlencode($ender);

            $this->criar_lista_presentes($senha, $email, $nome, $data_aniver, $hora, $ender, $idPedido);

            $this->aniversario->addListaPresentes($idPedido);

            $label      = $this->input->post('p_label_');
            $max        = count($label);

            // Pega o id da lista
            $urlLista   = $this->presentes->pegaIdLista($idPedido);
            $ur         = explode('http://', $urlLista['url']);
            $ur2        = explode('/', $ur[1]);
            $idLista    = $ur2[5];
            $idProduto  = array();

            $this->db->where('idPedido', $idPedido);
            $this->db->where('filho_num', 1);
            $this->db->delete('presentes');

            for($x=0; $x<=$max; $x++) {

                if (array_key_exists($x, $label)) {

                    $idProduto[$x] = $this->input->post('p_prod_')[$x];

                    $data = array(                                
                                'idPedido'      => $idPedido,
                                'nome'          => $this->input->post('p_label_')[$x],
                                'preco'         => $this->input->post('p_price_')[$x],
                                'detalhes'      => $this->input->post('p_desc_')[$x],
                                'img'           => $this->input->post('p_img_')[$x],
                                'nProduto'      => $idPresente[$x],
                                'idProduto'     => $idProduto[$x]
                            );

                    $this->db->insert('presentes', $data);                   

                }

            }

            $st = $this->aniversario->addItemListaPresentes($idProduto, $idLista);

            if (array_key_exists(0, $dadosPedido)) {

                if ($dadosPedido[0]['is2nomes'] == 1) {

                    $dado['idade2'] = $dadosPedido[0]['idade2'];
                    $dado['sexo2']  = $dadosPedido[0]['sexo2'];
                    $urlPresente2   = '????/'. $dado['idade2'] .'/'. $dado['sexo2'];

                    redirect($urlPresente2);

                }            

            } else if ($st === 'OK') {

                $data = array(
                            'lista_presente'          => 1, 
                            'lista_presente_disparo'  => 1,
                            'status_disparo_lembrete' => 1
                    );

                $this->db->where('idPedido', $idPedido);
                $this->db->update('pedido', $data);

                redirect('modos-envio');

            }

        }

    }


    public function incluir_presente2()
    {

        $this->load->model('loginx', 'login');
        $this->login->logged();

        if ($this->input->post('formdata', true)) {

          $presentes  = explode('&',$this->input->post('formdata'));
          $max        = count($presentes);
          $idPresente = $campos = array();       

          for($i=0; $i<$max; $i++) {

            $param_convitesx = explode('=',$presentes[$i]);

            if (strstr($param_convitesx[0], '%5B')) {

                $undr               = explode('_',$param_convitesx[0]);
                $param_convitesx[0] = $undr[0].'_'.$undr[1].'_';

                if ($i % 3 == 0) {
                    
                    $idPresente[] = $undr[2];

                }             
                
                $part               = explode('%5B', $param_convitesx[0]);
                $indice             = $part[0];
                $part               = null;
                $campos[$indice][]  = base64_decode(urldecode($param_convitesx[1]));

            } else {

                $undr               = explode('_',$param_convitesx[0]);                
                $param_convitesx[0] = $undr[0].'_'.$undr[1].'_';

                if ($i % 3 == 0) {
                  
                    $idPresente[] = $undr[2];

                }

                $campos[$param_convitesx[0]][] = base64_decode($param_convitesx[1]);

            }            
            
            $param_convitesx = null;

          }             

          $_POST = $campos;        

        }


        $config =   array(

                        array(
                           'field' => 'p_label_[]',
                           'label' => 'Label',
                           'rules' => 'required|xss_clean'     
                        ), 
                        
                        array(
                           'field' => 'p_price_[]',
                           'label' => 'Preço',
                           'rules' => 'required|xss_clean'     
                        ),

                        array(
                           'field' => 'p_desc_[]',
                           'label' => 'Desc',
                           'rules' => 'required|xss_clean'     
                        ),

                        array(
                           'field' => 'p_prod_[]',
                           'label' => 'Produto',
                           'rules' => 'required|xss_clean'     
                        )
                    );

        $this->form_validation->set_rules($config); 
        $this->form_validation->set_error_delimiters('<p style="font-weight: bold;color: #5d1613;">', '</p>');           
     
        if ($this->form_validation->run() === false) {} else {

            $this->load->model('aniversario');
            $this->load->model('pedidox');
            $this->load->model('cliente');

            $idPedido    = $this->session->userdata('idPedido');
            $dadosPedido = $this->pedidox->getPedido($idPedido);

            $car       = 'abcdefghijlmnopqrstuvxywzABCDEFGHIJLMNOPQRSTUVXZYW0123456789'; 
            $num_car   = 8;
            $max       = strlen($car)-1;
            $hash      = null;

            for($i=0; $i < $num_car; $i++) { 
                $hash .= $car[mt_rand(0, $max)];
            }

            $hash    = urlencode($hash);
            $hashUrl = base_url() . '????/'.$hash;

            $this->db->select();
            $this->db->from('lista_presente_comprador');
            $this->db->where(array('idPedido' => $idPedido));
            $this->db->order_by('idPresenteComprador', "desc");
            $d = $this->db->get()->result_array();

            if (array_key_exists(0, $d)) {} else {

                $data = array(
                                'idPedido'  => $idPedido,
                                'hashUrl'   => $hashUrl
                        );

                $this->db->insert('lista_presente_comprador', $data);

            }

            $nome         = urlencode($dadosPedido[0]['aniversariante2']);
            $email        = $this->session->userdata('username');
            $usuario      = $this->cliente->getUsuario($email);            
            $senha        = base64_decode($usuario[0]['password']);
            $senha        = md5('Lg'.$senha) .':Lg';            
            $data_aniver  = $dadosPedido[0]['dt_aniversario'];
            $hora         = $dadosPedido[0]['hora'];
            $e            = explode(',', $dadosPedido[0]['endereco_festa']);

            if (array_key_exists(0, $e) && array_key_exists(1, $e)) {

                $ender   = $e[0].' '.$e[1];

            } else {

                $ender   = $dadosPedido[0]['endereco_festa'].' '.$dadosPedido[0]['numero'];

            }

            $ender = urlencode($ender);

            $this->criar_lista_presentes($senha, $email, $nome, $data_aniver, $hora, $ender, $idPedido);

            $this->aniversario->addListaPresentes($idPedido);

            $label      = $this->input->post('p_label_');
            $max        = count($label);

            // Pega o id da lista
            $urlLista   = $this->presentes->pegaIdLista($idPedido);
            $ur         = explode('http://', $urlLista['url']);
            $ur2        = explode('/', $ur[1]);
            $idLista    = $ur2[5];
            $idProduto  = array();

            $this->db->where('idPedido', $idPedido);
            $this->db->where('filho_num', 2);
            $this->db->delete('presentes');

            for($x=0; $x<=$max; $x++) {

                if (array_key_exists($x, $label)) {

                    $idProduto[$x] = $this->input->post('p_prod_')[$x];

                    $data = array(                                
                                'idPedido'  => $idPedido,
                                'nome'      => $this->input->post('p_label_')[$x],
                                'preco'     => $this->input->post('p_price_')[$x],
                                'detalhes'  => $this->input->post('p_desc_')[$x],
                                'img'       => $this->input->post('p_img_')[$x],
                                'nProduto'  => $idPresente[$x],
                                'idProduto' => $idProduto[$x],
                                'filho_num' => 2
                            );

                    $this->db->insert('presentes', $data);                   

                }

            }

            $st = $this->aniversario->addItemListaPresentes($idProduto, $idLista);

            if ($st === 'OK') {

                $data = array(
                            'lista_presente'          => 1, 
                            'lista_presente_disparo'  => 1,
                            'status_disparo_lembrete' => 1
                    );

                $this->db->where('idPedido', $idPedido);
                $this->db->update('pedido', $data);

                redirect('modos-envio');

            }

        }

    }


    public function proximo()
    {

        $this->load->model('pedidox');

        $idPedido    = $this->session->userdata('idPedido');
        $dadosPedido = $this->pedidox->getPedido($idPedido);

        if (array_key_exists(0, $dadosPedido)) {

            if ($dadosPedido[0]['is2nomes'] == 1) {

                $dado['idade2'] = $dadosPedido[0]['idade2'];
                $dado['sexo2']  = $dadosPedido[0]['sexo2'];

                redirect('lista-presente2/'. $dado['idade2'] .'/'. $dado['sexo2']);

            }

        } else {

            redirect('modos-envio');

        }
        
    }

    public function excluir_lista()
    {        

        $this->load->model('loginx', 'login');
        $this->login->logged();

        $idPedido = $this->session->userdata('idPedido');

        $this->db->where('idPedido', $idPedido);
        $this->db->delete('presentes');

        $data = array(
                            'lista_presente'          => 0, 
                            'lista_presente_disparo'  => 0,
                            'status_disparo_lembrete' => 0
                    );

        $this->db->where('idPedido', $idPedido);
        $this->db->update('pedido', $data);

        die('ok');

    }


    public function excluir_presente()
    {

        $this->load->model('loginx', 'login');
        $this->login->logged();

        if ($_POST) {

            $this->load->model('aniversario');

            $idPresentex = $this->input->post('ps', true);

            if (array_key_exists('bs', $_POST)) {

                $idProd = base64_decode($this->input->post('bs', true));
                $idProd = str_replace("'", "", $idProd);
                $idProd = trim($idProd);
                $idProd = intval($idProd);               

            }

            $idPedido   = $this->session->userdata('idPedido');

             // Pega o id da lista
            $urlLista   = $this->presentes->pegaIdLista($idPedido);
            $ur         = explode('http://', $urlLista['url']);
            $ur2        = explode('/', $ur[1]);
            $idLista    = $ur2[5];
            $idProduto  = array();
            
            $this->db->where('idPedido', $idPedido);

            if (array_key_exists('bs', $_POST)) {

                $this->db->where('idProduto', $idProd);

            } else {

                $this->db->where('nProduto', $idPresentex);

            }

            $this->db->delete('presentes');            

            $idProduto[0] = $idProd;

            $st = $this->aniversario->deleteListaPresentes($idProduto, $idLista);

            if ($st == 'OK') {
                die('ok');
            }

        }        

    }

    public function incluir_presente_unitario()
    {

        $this->load->model('loginx', 'login');
        $this->login->logged();

        if ($_POST) {


            $config =   array(

                            array(
                               'field' => 'label',
                               'label' => 'Label',
                               'rules' => 'required|xss_clean'     
                            ), 
                            
                            array(
                               'field' => 'price',
                               'label' => 'Preço',
                               'rules' => 'required|xss_clean'     
                            ),

                            array(
                               'field' => 'desc',
                               'label' => 'Desc',
                               'rules' => 'required|xss_clean'     
                            )

                        );

            $this->form_validation->set_rules($config); 
            $this->form_validation->set_error_delimiters('<p style="font-weight: bold;color: #5d1613;">', '</p>');           
         
            if ($this->form_validation->run() === false) {} else {

                $this->load->model('pedidox');
                $this->load->model('aniversario');
                $this->load->model('cliente');

                $label        = $this->input->post('label');
                $max          = count($label);
                $idPedido     = $this->session->userdata('idPedido');
                $dadosPedido  = $this->pedidox->getPedido($idPedido);
                $nome         = urlencode($dadosPedido[0]['aniversariante']);
                $email        = $this->session->userdata('username');
                $usuario      = $this->cliente->getUsuario($email);            
                $senha        = base64_decode($usuario[0]['password']);
                $senha        = md5('Lg'.$senha) .':Lg';            
                $data_aniver  = $dadosPedido[0]['dt_aniversario'];
                $hora         = $dadosPedido[0]['hora'];
                $e            = explode(',', $dadosPedido[0]['endereco_festa']);                

                $this->db->select();
                $this->db->from('lista_presente_comprador');
                $this->db->where(array('idPedido' => $idPedido));
                $this->db->order_by('idPresenteComprador', "desc");
                $d = $this->db->get()->result_array();

                if (array_key_exists(0, $d)) {} else {

                    $car    ='abcdefghijlmnopqrstuvxywzABCDEFGHIJLMNOPQRSTUVXZYW0123456789';
                    $num_car= 8;
                    $max    = strlen($car)-1;
                    $hash   = null;

                    for($i=0; $i < $num_car; $i++) { 
                        $hash .= $car[mt_rand(0, $max)];
                    }

                    $hash   = urlencode($hash);
                    $hashUrl= base_url() . 'lista-presente-compra/'.$hash;

                    $data   = array(
                                    'idPedido'  => $idPedido,
                                    'hashUrl'   => $hashUrl
                            );

                    $this->db->insert('lista_presente_comprador', $data);

                }


                if (array_key_exists(0, $e) && array_key_exists(1, $e)) {

                    $ender   = $e[0].' '.$e[1];

                } else {

                    $ender   = $dadosPedido[0]['endereco_festa'].' '.$dadosPedido[0]['numero'];

                }

                $ender = urlencode($ender);
                $this->criar_lista_presentes($senha, $email, $nome, $data_aniver, $hora, $ender, $idPedido);

                for($x=0; $x<=$max; $x++) {

                    $this->db->select();
                    $this->db->from('presentes');
                    $this->db->where(array('idPedido' => $idPedido));
                    $this->db->order_by('nProduto', "desc");
                    $this->db->limit(1);
                    $d = $this->db->get()->result_array();

                    if (array_key_exists(0, $d)) {

                        $idPresente = $d[0]['nProduto'];
                        $idPresente++;

                    } else {

                        $idPresente = 1;                       

                    }

                    $label  = base64_decode($this->input->post('label'));
                    $label  = str_replace('"','', $label);
                    $price  = base64_decode($this->input->post('price'));
                    $desc   = base64_decode($this->input->post('desc'));
                    $desc   = str_replace('"','', $desc);
                    $idProd = stripslashes(base64_decode($this->input->post('iP_')));
                    $idProd = str_replace('\'','', $idProd);
                    $img    = base64_decode($this->input->post('img'));

                    $data   = array(                                
                                    'idPedido' =>  $idPedido,
                                    'nome'     =>  $label,
                                    'preco'    =>  $price,
                                    'detalhes' =>  $desc,
                                    'img'      =>  $img,
                                    'nProduto' =>  $idPresente,
                                    'filho_num'=>  1,
                                    'idProduto'=>  $idProd
                            );

                    $this->db->insert('presentes', $data);

                    $this->aniversario->addListaPresentes($idPedido);

                    // Pega o id da lista
                    $urlLista       = $this->presentes->pegaIdLista($idPedido);
                    $ur             = explode('http://', $urlLista['url']);
                    $ur2            = explode('/', $ur[1]);
                    $idLista        = $ur2[5];
                    $idProduto[0]   = $idProd;

                    $st = $this->aniversario->addItemListaPresentes($idProduto, $idLista);                    

                    if ($st == 'OK') {

                        $data = array(
                                        'lista_presente'          => 1, 
                                        'lista_presente_disparo'  => 1,
                                        'status_disparo_lembrete' => 1
                                );

                        $this->db->where('idPedido', $idPedido);
                        $this->db->update('pedido', $data);

                        die('OK');

                    }

                }

                die();

            }

        }

    }


    public function incluir_presente_unitario2()
    {

        $this->load->model('loginx', 'login');
        $this->login->logged();

        if ($_POST) {


            $config =   array(

                            array(
                               'field' => 'label',
                               'label' => 'Label',
                               'rules' => 'required|xss_clean'     
                            ), 
                            
                            array(
                               'field' => 'price',
                               'label' => 'Preço',
                               'rules' => 'required|xss_clean'     
                            ),

                            array(
                               'field' => 'desc',
                               'label' => 'Desc',
                               'rules' => 'required|xss_clean'     
                            )

                        );

            $this->form_validation->set_rules($config); 
            $this->form_validation->set_error_delimiters('<p style="font-weight: bold;color: #5d1613;">', '</p>');           
         
            if ($this->form_validation->run() === false) {} else {

                $this->load->model('pedidox');
                $this->load->model('aniversario');
                $this->load->model('cliente');

                $label        = $this->input->post('label');
                $max          = count($label);
                $idPedido     = $this->session->userdata('idPedido');
                $dadosPedido  = $this->pedidox->getPedido($idPedido);
                $nome         = urlencode($dadosPedido[0]['aniversariante2']);
                $email        = $this->session->userdata('username');
                $usuario      = $this->cliente->getUsuario($email);            
                $senha        = base64_decode($usuario[0]['password']);
                $senha        = md5('Lg'.$senha) .':Lg';            
                $data_aniver  = $dadosPedido[0]['dt_aniversario'];
                $hora         = $dadosPedido[0]['hora'];
                $e            = explode(',', $dadosPedido[0]['endereco_festa']);                

                $this->db->select();
                $this->db->from('lista_presente_comprador');
                $this->db->where(array('idPedido' => $idPedido));
                $this->db->order_by('idPresenteComprador', "desc");
                $d = $this->db->get()->result_array();

                if (array_key_exists(0, $d)) {} else {

                    $car    ='abcdefghijlmnopqrstuvxywzABCDEFGHIJLMNOPQRSTUVXZYW0123456789';
                    $num_car= 8;
                    $max    = strlen($car)-1;
                    $hash   = null;

                    for($i=0; $i < $num_car; $i++) { 
                        $hash .= $car[mt_rand(0, $max)];
                    }

                    $hash   = urlencode($hash);
                    $hashUrl= base_url() . 'lista-presente-compra/'.$hash;

                    $data   = array(
                                    'idPedido'  => $idPedido,
                                    'hashUrl'   => $hashUrl
                            );

                    $this->db->insert('lista_presente_comprador', $data);

                }


                if (array_key_exists(0, $e) && array_key_exists(1, $e)) {

                    $ender   = $e[0].' '.$e[1];

                } else {

                    $ender   = $dadosPedido[0]['endereco_festa'].' '.$dadosPedido[0]['numero'];

                }

                $ender = urlencode($ender);
                $this->criar_lista_presentes($senha, $email, $nome, $data_aniver, $hora, $ender, $idPedido);

                for($x=0; $x<=$max; $x++) {

                    $this->db->select();
                    $this->db->from('presentes');
                    $this->db->where(array('idPedido' => $idPedido));
                    $this->db->order_by('nProduto', "desc");
                    $this->db->limit(1);
                    $d = $this->db->get()->result_array();

                    if (array_key_exists(0, $d)) {

                        $idPresente = $d[0]['nProduto'];
                        $idPresente++;

                    } else {

                        $idPresente = 1;                       

                    }

                    $label  = base64_decode($this->input->post('label'));
                    $label  = str_replace('"','', $label);
                    $price  = base64_decode($this->input->post('price'));
                    $desc   = base64_decode($this->input->post('desc'));
                    $desc   = str_replace('"','', $desc);
                    $idProd = stripslashes(base64_decode($this->input->post('iP_')));
                    $idProd = str_replace('\'','', $idProd);
                    $img    = base64_decode($this->input->post('img'));

                    $data   = array(                                
                                    'idPedido' =>  $idPedido,
                                    'nome'     =>  $label,
                                    'preco'    =>  $price,
                                    'detalhes' =>  $desc,
                                    'img'      =>  $img,
                                    'nProduto' =>  $idPresente,
                                    'filho_num'=>  2,
                                    'idProduto'=>  $idProd
                            );

                    $this->db->insert('presentes', $data);

                    $this->aniversario->addListaPresentes($idPedido);

                    // Pega o id da lista
                    $urlLista       = $this->presentes->pegaIdLista($idPedido);
                    $ur             = explode('http://', $urlLista['url']);
                    $ur2            = explode('/', $ur[1]);
                    $idLista        = $ur2[5];
                    $idProduto[0]   = $idProd;

                    $st = $this->aniversario->addItemListaPresentes($idProduto, $idLista);                    

                    if ($st == 'OK') {

                        $data = array(
                                        'lista_presente'          => 1, 
                                        'lista_presente_disparo'  => 1,
                                        'status_disparo_lembrete' => 1
                                );

                        $this->db->where('idPedido', $idPedido);
                        $this->db->update('pedido', $data);

                        die('OK');

                    }

                }

                die();

            }

        }

    }


    public function lista_presente_compra()
    {

        $this->load->model('menu');

        $dado["headerx"] = $this->menu->header();
        $dado["footerx"] = $this->menu->footer();

        $this->load->view('lista-presentes-compra', $dado);

    }

    public function reordenarArray($dado2)
    {

        $maxEnt = count($dado2);       
        $i = $z = 0;

        while($i<$maxEnt) {            

            if (array_key_exists($z, $dado2)) {

                $dx[] = $dado2[$z];
                $i++;

            }   

            $z++;         

        }      

        return $dx;

    }

    public function reordenarFinalPriceArray($dao, $dado)
    {

        $maxF   = count($dao);
        $i = $z = 0;

        while($i<$maxF) {            

            if (array_key_exists($z, $dao)) {

                $df[] = $dado[$z];
                $i++;

            }   

            $z++;         

        }

        return $df;

    }

    public function ver_lista_presentes()
    {

        $this->load->model('menu');        

        $urlShortner = "http://" . $_SERVER['HTTP_HOST'].'/????/'.$this->uri->segment(2);        

        $filhoNum = $this->uri->segment(3);

        if ($dado = $this->presentes->pegaHashUrl($urlShortner, $filhoNum)) {

            $dado["headerx"] = $this->menu->header();
            $dado["footerx"] = $this->menu->footer();
            $urlLista        = $this->presentes->pegaIdLista($dado[0]['idPedido']);         
            $ur              = explode('http://', $urlLista['url']);
            $ur2             = explode('/', $ur[1]);
            $dado['listaId'] = $ur2[5];         

            $max = count($dado);

            for($i=0; $i<$max; $i++) {

                $idProduto  = str_replace("'", "", $dado[$i]['idProduto']);
                $dado2      = $this->presentes->pegaListaUnitario($idProduto);           

                if (is_array($dado2) && array_key_exists('img', $dado2)) {                        

                    if (array_key_exists(0, $dado2['img'])) {

                        $dado['img'][]          = $dado2['img'][0];
                        $dado['label'][]        = utf8_encode($dado2['label'][0]);
                        $dado['desc'][]         = $dado2['desc'][0];
                        $dado['finalPrice'][]   = $dado2['finalPrice'][0];
                        $dado['entity_id'][]    = trim(str_replace('\'', '', $dado2['entity_id'][0]));

                    }                    

                }

            }

            $this->load->view('lista-presentes-compra', $dado);

        }

    }

    public function preview_lista_presentes()
    {
        
        $this->load->model('loginx', 'login');
        $this->login->logged();

        $this->load->model('menu');


        $idPedido = $this->session->userdata('idPedido');

        if ($dadoP = $this->presentes->pegaPresentes($idPedido)) {


            $dado["headerx"] = $this->menu->header();
            $dado["footerx"] = $this->menu->footer();
            $urlLista        = $this->presentes->pegaIdLista($idPedido);
            $ur              = explode('http://', $urlLista['url']);
            $ur2             = explode('/', $ur[1]);
            $dado['listaId'] = $ur2[5];

            $max = count($dadoP);

            for($i=0; $i<$max; $i++) {

                $idProduto  = str_replace("'", "", $dadoP[$i]['idProduto']);
                $dado2      = $this->presentes->pegaListaUnitario($idProduto);           

                if (is_array($dado2) && array_key_exists('img', $dado2)) {                        

                    if (array_key_exists(0, $dado2['img'])) {

                        $dado['img'][]          = $dado2['img'][0];
                        $dado['label'][]        = utf8_encode($dado2['label'][0]);
                        $dado['desc'][]         = $dado2['desc'][0];
                        $dado['finalPrice'][]   = $dado2['finalPrice'][0];
                        $dado['entity_id'][]    = trim(str_replace('\'', '', $dado2['entity_id'][0]));

                    }                    

                }

            }

            $this->load->view('lista-presente-preview', $dado);

        }

    }

    public function preview_lista_presentes2()
    {
        
        $this->load->model('loginx', 'login');
        $this->login->logged();

        $this->load->model('menu');

        $idPedido = $this->session->userdata('idPedido');

        if ($dadoP = $this->presentes->pegaPresentes($idPedido, 2)) {

            $dado["headerx"] = $this->menu->header();
            $dado["footerx"] = $this->menu->footer();
            $urlLista        = $this->presentes->pegaIdLista($idPedido, 2);
            $ur              = explode('http://', $urlLista['url']);
            $ur2             = explode('/', $ur[1]);
            $dado['listaId'] = $ur2[5];

            $max = count($dadoP);

            for($i=0; $i<$max; $i++) {

                $idProduto  = str_replace("'", "", $dadoP[$i]['idProduto']);
                $dado2      = $this->presentes->pegaListaUnitario($idProduto);           

                if (is_array($dado2) && array_key_exists('img', $dado2)) {                        

                    if (array_key_exists(0, $dado2['img'])) {

                        $dado['img'][]          = $dado2['img'][0];
                        $dado['label'][]        = utf8_encode($dado2['label'][0]);
                        $dado['desc'][]         = $dado2['desc'][0];
                        $dado['finalPrice'][]   = $dado2['finalPrice'][0];
                        $dado['entity_id'][]    = trim(str_replace('\'', '', $dado2['entity_id'][0]));

                    }                    

                }

            }

            $this->load->view('lista-presente-preview2', $dado);

        }

    }

    private function criar_lista_presentes($senha, $email, $nome, $data_aniver, $hora, $endereco, $idPedido)
    {

        $data = array(
                    'email'          => $email,
                    'nome'           => $nome,
                    'dt_aniversario' => $data_aniver,
                    'hora'           => $hora,
                    'endereco'       => $endereco,
                    'idPedido'       => $idPedido,
                    'senha'          => $senha            
                );
       

        $this->db->where(array('email' => $email)); 
        $this->db->where(array('nome' => $nome)); 
        $this->db->where(array('dt_aniversario' => $data_aniver)); 
        $this->db->where(array('hora' => $hora)); 
        $this->db->where(array('endereco' => $endereco));            
        $this->db->where(array('idPedido' => $idPedido));

        $dado2 = $this->db->select('idListaPresente')->from('lista_presente')->get()->result_array();
        
        if (array_key_exists('0', $dado2)) {

            $this->db->where('idListaPresente', $dado2[0]['idListaPresente']);
            $this->db->update('lista_presente', $data);           

        } else {

            $this->db->insert('lista_presente', $data);

        }            

        return;      

    }

}
<?php

class Player2 extends CI_Controller {
 
    function __construct()
    {
      parent::__construct();
      $this->load->helper(array('form', 'url'));  
/*
      ini_set('display_errors', 1);
      ini_set('log_errors', 1);
      error_reporting(E_ALL);
*/    

    } 


    public function pegaadultos()
    {

      $idPedido = $this->input->get('id',true);
      $this->load->model('aniversario');
      $this->load->model('cliente');
      
      $usuario            = $this->session->userdata('username');            
      $cliente            = $this->cliente->getCliente($usuario);
      $idCliente          = $cliente[0]['idCliente'];  
      $idPedido           = base64_decode($idPedido);
      
      $data = $this->aniversario->qtdAdultos($idPedido, $idCliente);

      die(json_encode(array('qtd'=> $data[0]['qtd'])));
        
    }

    public function pegacriancas()
    {

      $idPedido = $this->input->get('id',true);
      $this->load->model('aniversario');        
      $this->load->model('cliente');
      
      $usuario            = $this->session->userdata('username');            
      $cliente            = $this->cliente->getCliente($usuario);
      $idCliente          = $cliente[0]['idCliente'];        
      $idPedido           = base64_decode($idPedido);        

      $data = $this->aniversario->qtdCriancas($idPedido, $idCliente);   

      die(json_encode(array('qtd'=> $data[0]['qtd'])));

    }


    public function confirmar()
    {

        $this->load->model('emailx');
       

        if ($_POST) {             

            $config = array(

                          array(
                             'field' => 'acompanhantes',
                             'label' => 'acompanhantes',
                             'rules' => 'required|xss_clean'     
                          ),
                          array(
                             'field' => 'urlx',
                             'label' => 'urlx',
                             'rules' => 'required|xss_clean'     
                          ),
                          array(
                             'field' => 'fase',
                             'label' => 'fase',
                             'rules' => 'required|xss_clean'     
                          )                          
            );

            $this->form_validation->set_rules($config);  
            $this->form_validation->set_error_delimiters('<p style="font-weight: bold;color: #5d1613;">', '</p>');          

            if ($this->form_validation->run() === false) {

                $this->error = true;
                $this->load->view('player2','');
                return;

            } else {
                
                $max         = count($this->input->post('acompanhantes'));                
                $campos      = $this->input->post('acompanhantes'); 
                $tipoPessoa  = $this->input->post('fase');            
                $urlShortner = $this->input->post('urlx');
                $nome        = $this->input->post('nome');
                $info        = $this->emailx->presencaEmail($nome, $urlShortner);           
                $convidados  = $this->emailx->addAcompanhante($campos, $tipoPessoa, $info[0]['idEmailPedido']);

                $this->load->view('confirmacao','');                
                
            }
        }

    }


    public function disparo_lembrete_lista_presentes($email)
    {
      
      $condi[]  =  array(
                            'field' => 'email',
                            'op'    => 'contains',
                            'value' => $email
                        );

      $conditions[] = $condi;

      $subject  = 'Festa de aniversário - Adicione presentes a sua lista';

      $template = '<!doctype html>
                        <html>
                        <head>
                        <meta charset="UTF-8">
                        <title>Espero Você</title>
                        <style>
                        body{ background:#4d2f27; }
                        </style>
                        </head>
                        <body>
                        <table width="620" border="0" align="center">
                          <tbody>
                            <tr>
                              <td align="center"><a href="'.$url.'/*|MERGE1|* *|MERGE2|*"><img src="'.$url2.'/images/newsBUFFET.jpg" width="595"  height="1453" alt="espero voce"/></a></td>
                            </tr>
                          </tbody>
                        </table>
                        </body>
                        </html>';

      if (is_array($conditions) && array_key_exists('0', $conditions) && array_key_exists('0', $conditions[0]) && $conditions[0][0]['value'] != '') {

          $dado[] = array( 'email'   => $email);
          $result = $this->emails->chamada('Lista', $dado);

          $campaignOptions =   array(
                                      'type'        => 'regular',
                                      'content_type'=> 'html',
                                      'recipients'  =>  array(
                                                            'list_id'       =>  '????',
                                                            'segment_opts'  =>  array(             
                                                                                    'match' => 'any',
                                                                                    'conditions' => $conditions[$y]
                                                                                )
                                                        ),
                                      'settings'    =>  array(                    
                                                              'subject_line'  => $subject,
                                                              'reply_to'      => 'mensagem@esperovoce.com.br',
                                                              'from_name'     => 'Esperovoce.com.br Mensagens',
                                                              'to_name'       => 'Esperovoce.com.br Mensagens',
                                                              //'title'         => 'example title',
                                                              //'generate_text' => true,
                                                        )                                      
                                );                  

          $result       = $this->emails->chamada('Campanha', '', $campaignOptions);     
          $campaignId   = $result['id'];
          $sendDate     = date('Y-m-d H:i:s', strtotime('+2 minutes'));
          $campaignOpt  = array('cid' => $campaignId);  
          $content      = array('html'  => $template);
          $result       = $this->emails->chamada('Conteudo', '', '', $campaignId, $content);
          $result       = $this->emails->chamada('Campanha-disparo', '', '', $campaignId);

      }


    }
    


}    

?>
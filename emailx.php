<?php 

class Emailx extends CI_Model {

	function __construct() {
		parent::__construct();

	}

	function addEmailCartao($email) 
	{

		$data = array(
						'idCliente'   => $email['idCliente'],
						'nome' 	 	  => $email['nome'],						
						'mensagem' 	  => $email['mensagem']
				);	

		if (array_key_exists('telefone', $email)) {

			$data['telefone'] = $email['telefone'];			

		}		

		$this->db->where(array('email' => $email['email']));	
		$dado = $this->db->select('idEmail')->from('email')->get()->result_array();

		if (array_key_exists('0', $dado)) {	

			$this->db->where(array('idPedido' => $this->session->userdata('idPedido')));	
			$dado2 = $this->db->select('idEmailPedido')->from('email_pedido')->get()->result_array();			

			if (array_key_exists('0', $dado2)) {

				$this->db->where('idEmailPedido', $dado2[0]['idEmailPedido']);
    			$this->db->update('email_pedido', $data);

    			return true;

			} else {	

				$data['idEmail'] 	= $dado[0]['idEmail'];
				$data['idPedido'] 	= $this->session->userdata('idPedido');
				$data['sessionId']  = $this->session->userdata('id_sessao');
				$this->db->insert('email_pedido', $data);

				return true;
			}   		

		} else {

			$dadoEmail['idCliente'] = $email['idCliente'];
			$dadoEmail['email'] 	= $email['email'];
			$dadoEmail['nome'] 		= $email['nome'];

			$this->db->insert('email', $dadoEmail);
			$idEmail 			= $this->db->insert_id();
			$data['idEmail'] 	= $idEmail;
			$data['idPedido'] 	= $this->session->userdata('idPedido');
			$data['sessionId']  = $this->session->userdata('id_sessao');
						
			$this->db->insert('email_pedido', $data);

			return true;
		}

	}

	
	function addEmail($email, $idPedido) 
	{		

		$max = count($email);		

		for($x=0; $x<$max; $x++) {					

			if (array_key_exists('email', $email[$x]) && array_key_exists($x, $email)) {
				$this->db->where('sessionId', $email[0]['sessionId']);
				$this->db->delete('email_pedido');
				break;
			}

		}		

		$cont = 0;
		for($x=0; $x<$max; $x++) {			

			if (array_key_exists('email', $email[$x]) && array_key_exists($x, $email)) {

				$data['email'] 		 = '';
				$datax['telefone'] 	 = '';
				$datax['nome'] 		 = '';
				$datax['qtdConvite'] = '';

				if ($cont == 0) {
					$datax['mensagem'] 	 = $email[$x]['mensagem'];	
				}
				

				$data['email'] = $email[$x]['email'];			

				if (array_key_exists('telefone', $email[$x])) {	
					$datax['telefone'] 	 = $email[$x]['telefone'];
				}

				if (array_key_exists('nome', $email[$x])) {	
					$datax['nome'] 		 = $data['nome'] 		= $email[$x]['nome'];
				}

				if (array_key_exists('qtdConvite', $email[$x])) {	
					$datax['qtdConvite'] = $email[$x]['qtdConvite'];
				}

				if (array_key_exists('idCliente', $email[$x])) {	
					$data['idCliente']	 = $datax['idCliente']  = $email[$x]['idCliente'];
				}				

				$this->db->where(array('email' => $email[$x]['email']));	
				$dado = $this->db->select('idEmail')->from('email')->get()->result_array();
				
				if (array_key_exists('0', $dado)) {	
					
		    		$datax['idEmail'] 	= $dado[0]['idEmail'];
		    		$datax['sessionId'] = $this->session->userdata('id_sessao');	    		
					$datax['idPedido'] 	= $idPedido;
		    		$this->db->insert('email_pedido', $datax);

		    	} else {	    

					$this->db->insert('email', $data);
					$idEmail 		 	= $this->db->insert_id();
					$datax['idEmail'] 	= $idEmail;
					$datax['sessionId'] = $this->session->userdata('id_sessao');
					$datax['idPedido'] 	= $idPedido;
					$this->db->insert('email_pedido', $datax);
			
				}			

			}
		}

		return true;

	}

    public function excluiMailChimp($email)
    {

	  $this->load->library('emails');        
         
	  $result   = $this->emails->call('lists/members', 
	                                  array(
	                                    'id'      => '4cf5476879',
	                                    'status'  => 'subscribed',
	                                    'opts'    => array(
	                                                        'start'   => 0,
	                                                        'limit'   => 100
	                                                 )
	                                  )
	              );

	  if ($result) {

		if(array_key_exists('total', $result)) {
			$max  = $result['total'];
		} else {
			$max  = 0;
		}

		for($i=0; $i<$max; $i++) {
		  
		  	if ($result['data'][$i]['email'] == $email) {

		        $result2 = $this->emails->call('lists/unsubscribe', array(
		                                'id'            => '4cf5476879',
		                                'email'         =>  array(
		                                                          'email' => $result['data'][$i]['email'],
		                                                          'euid'  => $result['data'][$i]['euid'],
		                                                          'leid'  => $result['data'][$i]['leid']
		                                                  ),
		                                'delete_member' => 'false',
		                                'send_goodbye' => 'false',
		                                'send_notify' => 'false'
							  )
							);
		    }
		}

	  }

    }


	function removeEmail($email)
	{	

		$idCliente = $email['idCliente'];
		$this->db->where(array('email' => $email['email']));						
		$data = $this->db->select()->from('email')->limit(1)->get()->result_array();

		if (array_key_exists('0', $data)) {

			$idEmail = $data[0]['idEmail'];			

			$this->db->where(array('idEmail' => $idEmail));	
			$d 			= $this->db->select()->from('email_pedido')->get()->result_array();
			$stExc 		= 0;
			$qtdEmail 	= count($d);

			for($i=0; $i<$qtdEmail; $i++) {

				if ($d[$i]['idCliente'] != $idCliente || $d[$i]['sessionId'] != $this->session->userdata('id_sessao')) {

					if ($this->session->userdata('idPedido')) {
						$this->db->where('idPedido', $idPedido);					 
					} else {
						$this->db->where('sessionId', $this->session->userdata('id_sessao'));
					}

					$this->db->where('idEmail', $idEmail);			
					$this->db->delete('email_pedido');
					$stExc = 1;
					break;

				}

			}

			if ($stExc == 0) {

				if ($this->session->userdata('idPedido')) {
					$this->db->where('idPedido', $idPedido);					 
				} else {
					$this->db->where('sessionId', $this->session->userdata('id_sessao'));
				}

				$this->db->where('idEmail', $idEmail);			
				$this->db->delete('email_pedido');

				$this->db->where('idEmail', $idEmail);			
				$this->db->delete('email');			
				
				$ex = $email['email'];
				$this->excluiMailChimp($ex);

			}						

		}
		

	}


	function removeAcompanhante($idAcompanhante)
	{

		$idAcompanhante = base64_decode($idAcompanhante);
		$this->db->where('idAcompanhante', $idAcompanhante);
		$this->db->delete('acompanhante');	

		return;
		
	}

	function removeAcompanhantePai($idAcompanhante)
	{


		$idAcompanhante = base64_decode($idAcompanhante);
		$this->db->where(array('idAcompanhante' => $idAcompanhante));			
  		$dado = $this->db->select('idEmailPedido')
					->from('acompanhante')						
					->get()->result_array();


	  	if (is_array($dado)) {

	  		if (array_key_exists('0', $dado)) {

				$this->db->where('idEmailPedido', $dado[0]['idEmailPedido']);
				$this->db->delete('acompanhante');

				$this->db->where('idEmailPedido', $dado[0]['idEmailPedido']);
				$this->db->delete('email_pedido');				

			}	

		}

		return;

	}

	
	function addAcompanhante($convidado, $tipoPessoa, $idEmailPedido)
	{		

		$this->db->where(array('idEmailPedido' => $idEmailPedido));
        $dado = $this->db->select('qtdConvite')->from('email_pedido')->get()->result_array();

        if (array_key_exists('0', $dado)) {

            $qtdConvite = $dado[0]['qtdConvite'];

            if ($qtdConvite >= 0) {

                $qtdConvite--;
                $this->db->where('idEmailPedido', $idEmailPedido);
                $this->db->update('email_pedido', array('qtdConvite' => $qtdConvite));

                $max = count($convidado);

				for($i=0 ;$i<$max; $i++) {

					if ($convidado[$i] != '') {

						$data['nome'] 			= $convidado[$i];
						$data['tipoPessoa'] 	= $tipoPessoa[$i];
						$data['stPresente'] 	= 1;
						$data['idEmailPedido'] 	= $idEmailPedido;
						$this->db->insert('acompanhante', $data);

					}
					
				}

            }

        }		

	}

	function rmAcompanhante($convidado, $tipoPessoa, $idEmailPedido)
	{		

		$this->db->where(array('idEmailPedido' => $idEmailPedido));
        $dado = $this->db->select('qtdConvite')->from('email_pedido')->get()->result_array();

        if (array_key_exists('0', $dado)) {

            $qtdConvite = $dado[0]['qtdConvite'];

            if ($qtdConvite >= 0) {

                $qtdConvite--;
                $this->db->where('idEmailPedido', $idEmailPedido);
                $this->db->update('email_pedido', array('qtdConvite' => $qtdConvite));

                $max = count($convidado);

				for($i=0 ;$i<$max; $i++) {

					if ($convidado[$i] != '') {

						$data['nome'] 			= $convidado[$i];
						$data['tipoPessoa'] 	= $tipoPessoa[$i];
						$data['stPresente'] 	= 0;
						$data['idEmailPedido'] 	= $idEmailPedido;
						$this->db->insert('acompanhante', $data);

					}
					
				}

            }

        }		

	}

	function admAcompanhante($convidado, $idAcompanhante)
	{

		$idAcompanhante = base64_decode($idAcompanhante);
		$this->db->where(array('idAcompanhante' => $idAcompanhante));			
  		$dado = $this->db->select()
					->from('acompanhante')						
					->get()->result_array();


	  	if (is_array($dado)) {

			if (array_key_exists('0', $dado)) {

				$this->db->where('idAcompanhante', $idAcompanhante);
				$this->db->update('acompanhante', array('nome' => $convidado));

			}

		}

	}




	function qtdSms()
	{
		$this->db->where(array('sessionId' => $this->session->userdata('id_sessao')));		
		$this->db->where('telefone !=','');
		$this->db->where('telefone !=','NULL');
		$this->db->from('email_pedido');
		
		return $this->db->count_all_results();		
	}

	function pegaEmails($idCliente)
	{

		$this->db->where(array('idCliente' => $idCliente));	
		$dado = $this->db->select('email, nome')
			->from('email')						
			->get()->result_array();

		return $dado;	
	}

	function pegaCatalogosPedido($idPedido)
	{
		
		$this->db->where(array('idPedido' => $idPedido));
		$this->db->from('email_pedido');
		$this->db->select();		

		return $this->db->get()->result_array();

	}	

	function pegaEmailsPedido($idCliente)
	{

		$this->db->where(array('ep.idCliente' => $idCliente));	
		$this->db->where(array('ep.sessionId' => $this->session->userdata('id_sessao')));	
		$this->db->select('e.email, ep.nome, ep.qtdConvite, e.idEmail, ep.telefone');					
		$this->db->from('email as e');
		$this->db->join('email_pedido as ep', 'e.idEmail = ep.idEmail');
		$this->db->group_by('e.idEmail');
		$dado = $this->db->get()->result_array();		
			
		return $dado;	
		
	}


	function pegaEmailsPedidoByIdSessao($idSessao = '',$idPedido = '')
	{

		if ($idSessao != '' && $idPedido == '') {

			$this->db->where(array('ep.sessionId' => $idSessao));	

		} else if ($idSessao == '' && $idPedido != '') {

			$this->db->where(array('ep.idPedido' => $idPedido));				

		}

		
		$this->db->select('e.email, ep.nome, ep.qtdConvite, e.idEmail, ep.telefone');					
		$this->db->from('email as e');
		$this->db->join('email_pedido as ep', 'e.idEmail = ep.idEmail');
		$this->db->group_by('e.idEmail');
		$dado = $this->db->get()->result_array();		
			
		return $dado;	

	}

	function pegaSmsEmail($idPedido)
	{

		$this->db->where(array('idPedido' => $idPedido));	
		$this->db->from('email_pedido');
		$this->db->select();	

		return $this->db->get()->result_array();

	}

	function qtdConvites()
	{

		$this->db->where(array('sessionId' => $this->session->userdata('id_sessao')));
		$this->db->select_sum('qtdConvite');
		$this->db->from('email_pedido');

		return $this->db->get()->result_array();

	}

	function qtdConvitesEmail()
	{

		$this->db->where(array('sessionId' => $this->session->userdata('id_sessao')));
		$this->db->from('email_pedido');
		
		return $this->db->count_all_results();		

	}

	function pegaEmailsDisparo($idPedido)
	{

		$this->db->where(array('ep.idPedido' => $idPedido));	
		$this->db->select('e.email, ep.nome, ep.qtdConvite');					
		$this->db->from('email as e');
		$this->db->join('email_pedido as ep', 'e.idEmail = ep.idEmail');
		$dado = $this->db->get()->result_array();					
			
		return $dado;	
	}

	function presencaEmail($nome, $urlShortner)
	{

		$this->load->model('pedidox');
		$this->load->model('videos');		
		$this->load->model('aniversario');		

		$idCliente 	= $this->aniversario->getIdCliente($urlShortner);
		$idPedido 	= $this->aniversario->getIdPedido($urlShortner);
		$nome 		= urldecode($nome);

		$this->db->where(array('ep.idPedido' => $idPedido));		
		$this->db->where(array('aco.nome' => $nome));			
		$this->db->select();
		$this->db->from('acompanhante as aco');
		$this->db->join('email_pedido as ep', 'aco.idEmailPedido = ep.idEmailPedido');
		$dado1 = $this->db->get()->result_array();
		$max = count($dado1);

		if (is_array($dado1) && array_key_exists('0', $dado1) && $dado1[0]['qtdConvite'] < 0) {
			
			return false;		

		} else {
			
			$this->db->where(array('ep.nome' => $nome));		
			$this->db->where(array('ep.idPedido' => $idPedido));		
			$this->db->select('e.email, ep.nome, ep.idEmailPedido, ep.qtdConvite');
			$this->db->from('email_pedido as ep');
			$this->db->join('email as e', 'e.idEmail = ep.idEmail');
			$dado = $this->db->get()->result_array();			
						
			$user_data 	= $this->session->all_userdata();
			$stConf 	= 0;
	        foreach ($user_data as $key => $value) {
	            if ($key == 'idEmailPedido') {
	                $stConf = 1;
	            }
	        }        
			

			return $dado;
		}

	}

	function confirmaPresencaResp($nome, $urlShortner)
	{

		$this->load->model('aniversario');		
		
		$data['stPresente']  = 1;

		$this->db->where('idEmail', $this->session->userdata('idEmail'));
		$this->db->where('nome', $nome);
		$this->db->update('email', $data);
		
	}

	function confirmaPresenca($nome, $urlShortner)
	{

		$this->load->model('aniversario');		
		
		$data['stPresente']  = 1;

		$this->db->where('idEmail', $this->session->userdata('idEmail'));
		$this->db->where('nome', $nome);
		$this->db->update('acompanhante', $data);
		
	}

	function buscaAcompanhante($email)
	{

		$this->db->where('e.email', $email);		
		$this->db->select('e.idEmail, aco.nome as acompanha');
		$this->db->from('email as e');
		$this->db->join('acompanhante as aco', 'e.idEmail = aco.idEmail');
		
		return $this->db->get()->result_array();
	}

	function pegaEmail($idEmail)
	{

		$this->db->where('idEmail', $idEmail);		
		$this->db->select();
		$this->db->from('email');		
		
		return $this->db->get()->result_array();	

	}

	
}
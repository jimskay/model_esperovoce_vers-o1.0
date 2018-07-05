<?php 

class Pedidox extends CI_Model {

	function __construct() {

		parent::__construct();
		
	}
	
	function addPedido($pedido) 
	{

		if (!$this->session->userdata('idPedido') ||  $this->session->userdata('idPedido') === '') {


			$data = array(
							'dataPedidoInicio' 	 => date('Y-m-d h:i:s'),
							'idCliente' 		 => $pedido['idCliente'],
							'sessionID' 	 	 => $this->session->userdata('id_sessao'),
							'ipCliente' 		 => $_SERVER["REMOTE_ADDR"],
							'statusPedido' 		 => 0,
							'cpfCobranca' 		 => $pedido['cpf'],
							'logradouroCobranca' => $pedido['endereco'],
							'bairroCobranca' 	 => $pedido['bairro'],
							'cidadeCobranca' 	 => $pedido['id_cidade'],
							'estadoCobranca' 	 => $pedido['uf'],
							'cepCobranca' 	 	 => $pedido['cep'],
							'idVideo' 	 	 	 => $pedido['idVideo'],
							'total' 	 	     => $pedido['valor'],
							'aniversariante' 	 => $pedido['aniversariante'],
							'sexo' 				 => $pedido['sexo'],							
							'tipo' 				 => $pedido['tipo'],
							'dt_aniversario' 	 => $pedido['dt_aniversario']
							
					);

			if (array_key_exists('cupom', $pedido)) {

				$data['cupom'] = $pedido['cupom'];
				
			}

			if (array_key_exists('statusPedido', $pedido)) {

				$data['statusPedido'] = $pedido['statusPedido'];
				
			}

			if ($this->session->userdata('tipo') == 'Convite') {

				$data['latitude'] 	 		= $pedido['latitude'];
				$data['longitude'] 	 		= $pedido['longitude'];
				$data['endereco_festa'] 	= $pedido['endereco_festa'];
				$data['complemento_festa'] 	= $pedido['complemento_festa'];
				$data['status_sms']	 		= $pedido['status_sms'];
				$data['hora']	 			= $pedido['hora'];
				$data['hora_final']			= $pedido['hora_final'];
				$data['idade']	 			= $pedido['idade'];

			} else if ($this->session->userdata('nome_remetente')) {

				$data['nomeCobranca'] = $this->session->userdata('nome_remetente');				
	        	$data['status_sms']	  = $pedido['status_sms'];				   	        
									
			}

			$this->db->insert('pedido', $data);	 	
			$idPedido 	= $this->db->insert_id();
			$data 		= array();

			if ($this->session->userdata('tipo') == 'Convite') {

				if ($this->session->userdata('sms_ativo') == 1) {

					$dadosPedido['status_sms'] 	= 1;
	                $data['vlItem'] 			= $this->session->userdata('custoSms');
	                $data['descricao'] 			= 'sms';
	                $data['idPedido'] 			= $idPedido;
	                $data['qtdItem'] 			= $this->session->userdata('qtdSms');
	                $this->db->insert('pedido_item', $data);

	            } else {
	                $dadosPedido['status_sms'] = 0;                
	            }

	            if ($this->session->userdata('qtdConvite') > 10) {

					$data['vlItem'] 			= $this->session->userdata('vlCvt');
	                $data['descricao'] 			= 'convite-adicional';
	                $data['idPedido'] 			= $idPedido;
	                $data['qtdItem'] 			= $this->session->userdata('qtdCvt');
	                $this->db->insert('pedido_item', $data);            	

	            }

	        } 

			$this->session->set_userdata(array('idPedido' => $idPedido));				
			$this->db->where('sessionId', $this->session->userdata('id_sessao'));
    		$this->db->update('email_pedido', array('idPedido' => $idPedido));

		} else {

			return;

		}		 
      	
	}	

  	function updatePedido($data, $id)
  	{

  		if ($this->session->userdata('tipo') == 'Convite') {

	  		if ($this->session->userdata('sms_ativo') == 1) {
				
				$data['status_sms']  	= 1;
				$dadosItem['vlItem'] 	= $this->session->userdata('custoSms');
				$dadosItem['qtdItem']	= $this->session->userdata('qtdSms');
				$this->db->where('idPedido', $id);			
				$this->db->where('descricao', 'sms');
				$this->db->update('pedido_item', $dadosItem);

			} else if ($this->session->userdata('sms_ativo') == 0) {

				$data['status_sms'] = 0;			

				if ($this->session->userdata('custoSms') > 0 || $this->session->userdata('qtdSms') > 0) {

					$this->db->where('idPedido', $id);			
					$this->db->where('descricao', 'sms');			
					$this->db->delete('pedido_item');

				} 
			}

			if ($this->session->userdata('sms_lembrete') == 1) {
				
				$dadosItem['vlItem'] 	= $this->session->userdata('custoSmsLembrete');
				$dadosItem['qtdItem']	= $this->session->userdata('qtdSmsLembrete');
				$this->db->where('idPedido', $id);			
				$this->db->where('descricao', 'sms-lembrete');
				$this->db->update('pedido_item', $dadosItem);

			} else if ($this->session->userdata('sms_ativo') == 0) {
				
				if ($this->session->userdata('custoSmsLembrete') > 0 || $this->session->userdata('qtdSmsLembrete') > 0) {

					$this->db->where('idPedido', $id);			
					$this->db->where('descricao', 'sms-lembrete');			
					$this->db->delete('pedido_item');

				} 
			}

			if ($this->session->userdata('qtdConvite') > 10) {

	            $dadosItem['vlItem'] 	= $this->session->userdata('vlCvt');
				$dadosItem['qtdItem']	= $this->session->userdata('qtdCvt');
				$this->db->where('idPedido', $id);			
				$this->db->where('descricao', 'convite-adicional');
				$this->db->update('pedido_item', $dadosItem);                   	
	            
	        } else {
				
	        	$this->db->where('idPedido', $id);			
				$this->db->where('descricao', 'convite-adicional');			
				$this->db->delete('pedido_item');

	        }

	    } else {

	    	if ($this->session->userdata('nome_remetente')) {

				$data['nomeCobranca'] = $this->session->userdata('nome_remetente');
					
			}
			
	    }


  		$this->db->where('idPedido', $id);
        $this->db->update('pedido', $data);
        //die($this->db->last_query());

        $this->db->where('sessionId', $this->session->userdata('id_sessao'));
		$this->db->update('email_pedido', array('idPedido' => $id));
            
  	}

  	function listaPedido($user, $flag = false, $ped = '')
  	{
  		

  		$this->db->like('email', $user, 'none');
  		$dado = $this->db->select('idCliente')
					->from('cliente')						
					->get()->result_array();


	  	if (is_array($dado)) {

			if (array_key_exists('0', $dado)) {					  				

				$this->db->where(array('p.idCliente' => $dado[0]['idCliente']));						
				$this->db->select('p.idPedido, p.codigoTransacao, p.statusPedido, p.dataPedidoInicio, p.total, p.idVideo, p.status_sms, p.tipo, email_disparo, p.sms_disparo, p.cupom, p.descricao, cp.hash');
				$this->db->join('cliente_pedido as cp', 'p.idPedido = cp.idPedido', 'left');
				$this->db->from('pedido as p');
				$this->db->order_by('p.idPedido', "desc");

				
				if ($ped == 'Abertos') {

					$this->db->where('p.statusPedido >=', 0);
					$this->db->where('p.statusPedido <', 3);
					$this->db->limit(5);

				} else if ($flag) {

					$this->db->where('p.statusPedido', 3);					
					$this->db->limit(1);

				} else {

					$this->db->where('p.statusPedido',3);					
					$this->db->limit(5);

				}

				$d = $this->db->get()->result_array();								
				
				return $d;

			} else {

				return false;

			}

		} else {

			return false;

		}			


  	}

  	function listaPedidoProblema($user)
  	{

  		$this->db->like('email', $user, 'none');
  		$dado = $this->db->select('idCliente')
					->from('cliente')						
					->get()->result_array();


	  	if (is_array($dado)) {

			if (array_key_exists('0', $dado)) {					  				

				$this->db->where(array('idCliente' => $dado[0]['idCliente']));						
				$this->db->where(array('codigoTransacao <>' => ''));				
				$this->db->select('idPedido, sessionId, codigoTransacao, dataPedidoInicio');
				$this->db->from('pedido');
				$this->db->order_by('idPedido', "desc");
				$d 	 = $this->db->get()->result_array();

				if (array_key_exists('0', $d)) {

					$max = count($d);

					for($i=0; $i<$max; $i++) {
						
						$this->db->where(array('idCliente' => $dado[0]['idCliente']));						
						$this->db->where(array('sessionId' => $d[$i]['sessionId']));						
						$this->db->where('codigoTransacao IS NULL', null);
						$this->db->delete('pedido');
						
						$this->db->select();
						$this->db->from('pedido as p');
				      	$this->db->join('email_pedido as ep', 'p.idPedido = ep.idPedido');
				      	$this->db->where(array('p.sessionId' => $d[$i]['sessionId']));
				      	$d2 = $this->db->get()->result_array();
						
						if (array_key_exists('0', $d2)){} else {

							$this->db->where('sessionId', $d[$i]['sessionId']);							
				    		$this->db->update('email_pedido', array('idPedido' => $d[$i]['idPedido']));

						}
						
					}					

				}			

			}
		
		}

  	}

  	function getPedido($id)
  	{

  		$this->db->where(array('idPedido' => $id));	
						
		return $this->db->select()
					->from('pedido')						
					->get()->result_array();

  	}

  	function listaPedidoPago()
  	{

  		$query = "email_disparo = 0";

  		$this->db->where($query);  		
  		$this->db->where(array('idBuffet' => NULL));
  		$this->db->where_in('statusPedido', array(3,4));
		$this->db->select('idPedido, codigoTransacao, statusPedido, dataPedidoInicio, total, idVideo, status_sms, tipo, email_disparo, sms_disparo, cupom, descricao, dt_aniversario, idCliente, aniversariante, id_cidade, latitude, longitude, hora, endereco_festa');
		$this->db->from('pedido');
		$this->db->limit(20);
		$this->db->order_by('idPedido', "desc");	

		$d = $this->db->get()->result_array();		

		return $d;

  	}

  	function listaLembretePedidoPago()
  	{

  		//Somente 

  		$this->db->where_in('statusPedido', array(3,4));
  		$this->db->where('idBuffet >', 0);
  		$this->db->where('idBuffet IS NOT NULL');
  		$this->db->where('status_disparo_lembrete IS NULL');  		
  		$this->db->like('dt_aniversario', date('Y-m-d', strtotime('+2 day')), 'both');
		$this->db->select('idPedido, codigoTransacao, statusPedido, dataPedidoInicio, total, idVideo, status_sms, tipo, email_disparo, sms_disparo, cupom, descricao, dt_aniversario, idCliente, aniversariante, id_cidade, latitude, longitude, hora, endereco_festa');
		$this->db->from('pedido');
		$this->db->limit(20);
		$this->db->order_by('idPedido', "desc");		

		$d = $this->db->get()->result_array();
				
		return $d;

  	}

  	function listaPedidosBuffet($idMatriz)
  	{

  		$this->db->where('idFranquia', $idMatriz);
  		$this->db->from('unidades');  		
  		$d0 = $this->db->get()->result_array();

  		if (array_key_exists('0', $d0)) {

  			$max = count($d0);

  			for($i=0; $i<$max; $i++) {

  				$this->db->select('idPedido, dt_aniversario, aniversariante, lista_presente, qtdConvites');
  				$this->db->where_in('statusPedido', array(3,4));
		  		$this->db->where('idBuffet >', 0);
		  		$this->db->where('idBuffet', $d0[$i]['idUnidade']);
		  		$this->db->from('pedido');  		
		  		$d 	  = $this->db->get()->result_array();
		  		$max2 = count($d);

		  		for($j=0; $j<$max2; $j++) {

		  			$d[$j]['Unidade'] 	= $d0[0]['descricao'];	
		  			$d[$j]['Percentual']= $d0[0]['percentual'];	


		  		}

  			}  			

	  		return $d;	

  		}

  		return false;  		

  	}


}


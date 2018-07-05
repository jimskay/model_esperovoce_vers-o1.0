<?php 


class Aniversario extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function addVideo($idCliente, $hash, $dtAniversario, $idPedido, $urlShortner) 
	{			

		$this->db->select();
        $this->db->from('cliente_pedido');
        $this->db->where(array('idPedido' => $idPedido));
        $this->db->limit(1);
        $dado = $this->db->get()->result_array();       

        $data = array(
						'dt_aniversario' => $dtAniversario,
						'idCliente' 	 => $idCliente,
						'idPedido' 		 => $idPedido,
						'hash' 	 	 	 => $hash,
						'urlGerada'		 => $urlShortner									
				);

		if (!array_key_exists(0,$dado)) {						

			$this->db->insert('cliente_pedido', $data);
			
		}		      	

	}

	function addListaPresentes($idPedido)
    {

        $this->db->select('senha, email, nome, dt_aniversario, endereco, hora');
        $this->db->from('lista_presente');
        $this->db->where(array('idPedido' => $idPedido));
        $this->db->limit(1);
        $dado = $this->db->get()->result_array();

        if (array_key_exists(0, $dado)) {

            $senha       = $dado[0]['senha'];
            $email       = $dado[0]['email'];
            $nome        = $dado[0]['nome'];
            $dt 		 = explode(' ', $dado[0]['dt_aniversario']);
            $data_aniver = urlencode($dt[0]);
            $endereco    = $dado[0]['endereco'];
            $hora        = $dado[0]['hora'];
            $endereco 	 = str_replace('.', '', $endereco);

            $this->db->select('telefone');
        	$this->db->from('buffet_whats');
	        $this->db->where(array('idPedido' => $idPedido));
	        $this->db->limit(1);
	        $dado2 = $this->db->get()->result_array();

	        if (array_key_exists(0, $dado2)) {

	        	$telefone = urlencode($dado2[0]['telefone']);

	        }

            $url = 'http://loja.esperovoce.com.br/criar_lista_presentes.php';  

            $ch  = curl_init();        
            curl_setopt($ch, CURLOPT_URL, $url);   
            //curl_setopt($ch, CURLOPT_POSTFIELDS,'s='.$senha.'&email='.$email.'&nome='.$nome.'&endereco='.$endereco.'&hora='.$hora.'&dtaniversario='.$data_aniver);  

            if (!isset($telefone) || $telefone == '') {

            	curl_setopt($ch, CURLOPT_POSTFIELDS,'s='.$senha.'&email='.$email.'&nome='.$nome.'&endereco='.$endereco.'&hora='.$hora.'&dtaniversario='.$data_aniver);            	

            } else if (isset($telefone) && $telefone != '') {

            	curl_setopt($ch, CURLOPT_POSTFIELDS,'s='.$senha.'&email='.$email.'&nome='.$nome.'&endereco='.$endereco.'&hora='.$hora.'&dtaniversario='.$data_aniver.'&telefone='.$telefone);

            }

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $ch_exec =  curl_exec($ch);
            
            if ($ch_exec !== FALSE ) {                              

                $ch_exec = str_replace('":"', ' : ', $ch_exec);
                $url     = explode(' : ', $ch_exec);

                if (array_key_exists('0', $url)) {
                    
                    $url2 = str_replace('"', '', $url[1]);                    
                    $url2 = str_replace('}', '', $url2);                    
                    $url2 = str_replace('\\', '', $url2);

                    $data   = array('url' => $url2);
                	$data2  = array('lista_presente_disparo' => 1);

	                $this->db->where(array('idPedido' => $idPedido));
	                $this->db->update('lista_presente', $data);

	                $this->db->where(array('idPedido' => $idPedido));
	                $this->db->update('pedido', $data2);

                }                

            } else {

                echo $ch_exec;
                die;

            }     

        }        

    }	

    function listaLembretes()
    {

    	$mes 			= date("m");
    	$ano 			= date("Y");
		$ultimo_dia 	= date("t", mktime(0,0,0,$mes,'01',$ano));		
		$ultimaData  	= $ano.'-'.$mes.'-'.$ultimo_dia;
		$primeiraData	= $ano.'-'.$mes.'-01';

    	$this->db->select('urlGerada');
		$this->db->from('cliente_pedido');      		
		$this->db->where('dt_aniversario >=', $primeiraData);
		$this->db->where('dt_aniversario <=', $ultimaData);		

        return $this->db->get()->result_array();

    }

    function pegaListaPresente($idPedido)
    {

        $this->db->select();
        $this->db->from('lista_presente_comprador');
        $this->db->where(array('idPedido' => $idPedido));        
        $this->db->order_by('idPresenteComprador', "desc");
        $this->db->limit(1);

        return $this->db->get()->result_array();       
        
    }

	function getInfoAniver($idPedido, $idCliente = '')
	{

		$this->db->select('dt_aniversario, urlGerada');
        $this->db->from('cliente_pedido');
        $this->db->where(array('idPedido' => $idPedido));

        if ($idCliente != '') {
        	$this->db->where(array('idCliente' => $idCliente));        
        }
        
        $this->db->limit(1);        
        return $this->db->get()->result_array();        

	}

	function detalheAniver($idPedido)
	{

		$this->db->select('descricao, vlItem, qtdItem');
        $this->db->from('pedido_item');
        $this->db->where(array('idPedido' => $idPedido));
        $this->db->limit(1);
        
        return $this->db->get()->result_array();

	}

	function getAniverValido($urlShortner, $lembrete = false)
	{

		$this->db->select('idPedido, dt_aniversario');
		$this->db->from('cliente_pedido');      
		$this->db->where(array('urlGerada'=>$urlShortner));
		$this->db->limit(1);
		$clientePedido = $this->db->get()->result_array();
		

		if ($clientePedido[0]['dt_aniversario'] >= date('Y-m-d')) {

			/* Verifica se é lembrete de aniversário com $lembrete = true, caso contrario se $lembrete = false, verifica somente se o aniversário não expirou. */
			if ($lembrete) {

				$data 	 = explode('-',$clientePedido[0]['dt_aniversario']);
				$dias 	 = 2;
				$newData = date("Y-m-d", mktime(0, 0, 0, $data[1], $data[2] - $dias, $data[0]));

				if ($newData == date('Y-m-d')) {

					return true;

				} else {

					return false;

				}

			} else {

				return true;	

			}			

		} else {

			return false;

		}


	}


	function getVideo($urlShortner)
	{

		$this->load->model('pedidox');
		$this->load->model('videos');
		
		$this->db->select('idPedido, dt_aniversario');
		$this->db->from('cliente_pedido');      
		$this->db->where(array('urlGerada'=>$urlShortner));
		$this->db->limit(1);
		$this->db->order_by('idClientePedido', "desc");
		$clientePedido 	= $this->db->get()->result_array();

		if (!array_key_exists(0,$clientePedido)) {
			return false;
		}

		$data 	 = explode('-',$clientePedido[0]['dt_aniversario']);
		$dias 	 = 2;
		$newData = date("Y-m-d", mktime(0, 0, 0, $data[1], $data[2] + $dias, $data[0]) );

		if ($newData >= date('Y-m-d')) {

			$pedido 	 		= $this->pedidox->getPedido($clientePedido[0]['idPedido']);
			$data['latitude']  	= $pedido[0]['latitude'];
			$data['longitude'] 	= $pedido[0]['longitude'];
			$data['festa'] 		= utf8_decode($pedido[0]['endereco_festa']);
			$data['hora'] 		= $pedido[0]['hora'];
			$data['complemento']= utf8_decode($pedido[0]['complemento_festa']);
			$o       			= explode('-',$clientePedido[0]['dt_aniversario']);
			$ox       			= explode(' ',$o[2]);
      		$data['dt_aniver']	= $ox[0].'/'.$o[1].'/'.$o[0];			 			
			$data['tipo'] 		= $pedido[0]['tipo'];


			if ($pedido[0]['hora_final'] != '') {

				$data['horaFinal'] = $pedido[0]['hora_final'];

			}

			$dado 		 		= $this->videos->getVideoInterno2($pedido[0]['idVideo']);

			if (array_key_exists(0, $dado)) {

	        	$data['url'] = $dado[0]['hashUrl'];

	        }	      

			//die($this->db->last_query());	        
	        return $data;

	    } else {

	    	return false;

	    }    
      	
	}

	function getIdCliente($urlShortner)
	{

		$this->db->select('idCliente');
		$this->db->from('cliente_pedido');      
		$this->db->where(array('urlGerada'=>$urlShortner));
		$this->db->limit(1);
		$clientePedido = $this->db->get()->result_array();

		$idCliente = $clientePedido[0]['idCliente'];

		return $idCliente;

	}

	function getIdPedido($urlShortner)
	{

		$this->db->select('idPedido');
		$this->db->from('cliente_pedido');      
		$this->db->where(array('urlGerada'=>$urlShortner));
		$this->db->limit(1);
		$clientePedido 	= $this->db->get()->result_array();

		$idPedido = $clientePedido[0]['idPedido'];

		return $idPedido;

	}

	function listaPresenca($idPedido, $idEmailPedido, $nome)
	{

		$this->db->select('a.nome, a.tipoPessoa, a.stPresente, a.idAcompanhante');
		$this->db->from('acompanhante as a');      
		$this->db->join('email_pedido as e', 'e.idEmailPedido = a.idEmailPedido');
      	$this->db->where(array('e.idPedido' => $idPedido));		
      	$this->db->where(array('e.idEmailPedido' => $idEmailPedido));
      	$this->db->where(array('a.nome <>' => $nome));

      	return $this->db->get()->result_array();
      	//die($this->db->last_query());      	

	}

	function listaConvites($idPedido)
	{

		$this->db->select('e.idEmailPedido, a.nome, a.tipoPessoa, a.stPresente, a.idAcompanhante');
		$this->db->from('acompanhante as a');      
		$this->db->join('email_pedido as e', 'e.idEmailPedido = a.idEmailPedido and e.nome = a.nome');
      	$this->db->where(array('e.idPedido'=>$idPedido));		      	
      	
      	return $this->db->get()->result_array();     	

	}


	function qtdAdultos($idPedido)
	{

		$this->db->select('a.tipoPessoa, count(*) as qtd');
		$this->db->from('acompanhante as a');      
		$this->db->join('email_pedido as e', 'e.idEmailPedido = a.idEmailPedido');
      	$this->db->where(array('e.idPedido'   => $idPedido));      	
      	$this->db->where(array('a.tipoPessoa' => 'Adulto'));		
      	$this->db->where(array('a.stPresente' => 1));		
      	$this->db->group_by("tipoPessoa");

      	return $this->db->get()->result_array();      	

	}

	function qtdCriancas($idPedido)
	{

		$this->db->select('a.tipoPessoa, count(*) as qtd');
		$this->db->from('acompanhante as a');      
		$this->db->join('email_pedido as e', 'e.idEmailPedido = a.idEmailPedido');
      	$this->db->where(array('e.idPedido'   => $idPedido));      	
      	$this->db->where(array('a.tipoPessoa' => 'Crianca'));		
      	$this->db->where(array('a.stPresente' => 1));      	
      	$this->db->group_by("tipoPessoa");

      	return $this->db->get()->result_array();      	

	}

	function qtdConvidados($idPedido)
	{

		$this->db->select('a.tipoPessoa, count(*) as qtd');
		$this->db->from('acompanhante as a');      
		$this->db->join('email_pedido as e', 'e.idEmailPedido = a.idEmailPedido');
      	$this->db->where(array('e.idPedido'   => $idPedido));      	
      	$this->db->where(array('a.stPresente' => 1));
      	$this->db->group_by("tipoPessoa");

      	return $this->db->get()->result_array();

	}

	function getTotalLista($idPedido)
    {     

		$this->db->select('url');
		$this->db->from('lista_presente');      			
      	$this->db->where(array('idPedido' => $idPedido));		
		$d = $this->db->get()->result_array();

      	if (array_key_exists(0, $d)) {      		

      		$dx 	 = explode('/', $d[0]['url']);
      		$m  	 = count($dx);
      		$idLista = $dx[$m-2];

            $url = 'http://loja.esperovoce.com.br/obter_vl_lista_presentes.php';
            $ch  = curl_init();        

            curl_setopt($ch, CURLOPT_URL, $url);   
            curl_setopt($ch, CURLOPT_POSTFIELDS,'s='.$idLista);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $ch_exec = curl_exec($ch);
            
            if ($ch_exec !== FALSE ) {

                $ch_exec = str_replace('":"', ' : ', $ch_exec);
                $url     = explode(' : ', $ch_exec);

                if (array_key_exists('0', $url)) {
                    
                	return $url[1];                	

                }                

            } else {

                echo $ch_exec;
                die;

            }     

        }

    }

}
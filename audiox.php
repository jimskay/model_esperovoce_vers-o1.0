
<?php 

class Audiox extends CI_Model {

	function __construct() {
		parent::__construct();
	}


	public function list_audios($nome = '', $sexo)
  	{


  		if ($nome != '' && strlen($nome) >= 3) {

		    $this->db->select('a.hashUrl, n.nome, a.idAudio, a.sexo');
	    	$this->db->from('nomes as n');
	      	$this->db->join('audio as a', 'n.idNome = a.idNome');
	      	$this->db->like('n.nome', $nome, 'none');
	      	$this->db->limit(1);
	      	
	      	$data = $this->db->get()->result_array();

	      	if (array_key_exists('0', $data) && array_key_exists('sexo', $data[0])) {

		      	$sexo = $data[0]['sexo'];

				if($sexo == 'fem') {                  
	                
	                    $data_session = array('sexo' => 'fem');

	            } else {

	                    $data_session = array('sexo' => 'ma');
	            }

		      	$this->session->set_userdata($data_session);
	      	}      

	      	return $data;
	      	
	      	
		} else if ($nome == '2') {

			$this->db->select('a.hashUrl, n.nome, a.idAudio, a.sexo');
	    	$this->db->from('nomes as n');
	      	$this->db->join('audio as a', 'n.idNome = a.idNome');
	      	$this->db->like('n.nome', '2', 'none');
	      	$this->db->limit(1);
	      	
	      	$data = $this->db->get()->result_array();

			if (array_key_exists('0', $data) && array_key_exists('sexo', $data[0])) {

		      	$sexo = $data[0]['sexo'];
		      	
		      	if($sexo == 'fem') {                  
	                
					$data_session = array('sexo' => 'fem');

	            } else {

					$data_session = array('sexo' => 'ma');
					
	            }

		      	$this->session->set_userdata($data_session);

	      	}      

	      	return $data;
	      	
		}
	    	//die($this->db->last_query());			        

  	}

	
}
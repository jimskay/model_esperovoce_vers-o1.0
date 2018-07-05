<?php 

class Videos extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function getVideos() 
	{

		$this->db->select('hashUrl, thumb, heroi, nome, grupo');
		$this->db->from('videos');
		 	
      	return $this->db->get()->result_array();	

	}


	public function list_videos($idAudio = '')
  	{

	    $this->db->select('hashUrl, thumb, nome, grupo, heroi');    
    	$sexo 	 = $this->session->userdata('sexo');
    	$tipo 	 = $this->session->userdata('tipo');  	


    	$idAudio = base64_decode($idAudio);       	 	

		$this->db->where('idAudio', $idAudio);			
		$this->db->like('sexo', $sexo, 'both');
		$this->db->like('tipo', $tipo, 'both');
		
		$this->db->from('videos');
		$this->db->group_by('heroi');
		$this->db->order_by('grupo');
		
		return $this->db->get()->result_array();

	    
  	}


  	public function list_videos_subopcoes($nome = '', $heroi = '')
  	{
  		
	    $this->db->select('hashUrl, thumb, nome, grupo, heroi');

	    if ($nome != '' && strlen($nome) >= 3) {

	    	if (strstr($nome, 'O nome')) {

		    	$nome = 'Feliz';
		    	$this->db->like('nome', $nome, 'both');					

		    } else {

		    	$this->db->like('nome', $nome, 'none');						
		    	$sexo = $this->session->userdata('sexo');	    	
		    	$this->db->like('sexo', $sexo, 'both');
				
		    }

	    	$tipo = $this->session->userdata('tipo');

			$this->db->like('tipo', $tipo, 'both');
			$this->db->like('heroi', $heroi, 'both');
			$this->db->group_by('nome');
			$this->db->group_by('categoria');		
			
			$this->db->from('videos');			
			//die($this->db->last_query());			
			return $this->db->get()->result_array();

	    } else {



	    }   

  	}


  	public function list_videos_nome($nome = '')
  	{
  		
	    $this->db->select('hashUrl, thumb, nome, grupo, heroi');

	    if ($nome != '' && strlen($nome) >= 3) {	    	

	    	$this->db->like('nome', $nome, 'none');						
	    	$sexo = $this->session->userdata('sexo');	    	
	    	$this->db->like('sexo', $sexo, 'both');	    

	    	$tipo = $this->session->userdata('tipo');
			$this->db->like('tipo', $tipo, 'both');
			$this->db->like('heroi', $heroi, 'both');
			$this->db->group_by('nome');
			$this->db->group_by('categoria');

			$this->db->from('videos');						

			$dado = $this->db->get()->result_array();

			if (array_key_exists(0, $dado)) {

				return $dado;

			} else {

				$nome = 'Feliz';
		    	$this->db->like('nome', $nome, 'both');							    	
				$this->db->like('tipo', $tipo, 'both');
				$this->db->like('heroi', $heroi, 'both');
				$this->db->group_by('nome');
				$this->db->group_by('categoria');

				$this->db->from('videos');

				return $this->db->get()->result_array();

			}

	    } else {

	    	

	    }   

  	}


  	public function list_videos_b_home($nome = '')
  	{

	    $this->db->select('hashUrl, thumb, nome, grupo, heroi');

	    if ($nome != '' && strlen($nome) >= 3) {

	    	if (strstr($nome, 'O nome')) {

		    	$nome = 'Feliz';
		    	$this->db->like('nome', $nome, 'both');					

		    } else {

		    	$this->db->like('nome', $nome, 'none');						
		    	
		    }
	    	
			$this->db->group_by('nome');
			$this->db->group_by('categoria');		
			
			$this->db->from('videos');			
			$this->db->limit(3);
		
			//die($this->db->last_query());			
			return $this->db->get()->result_array();

	    } else {}   

  	}

  	public function list_videos_home()
  	{


  		for($x=0; $x<4; $x++) {

	  		$num = rand(3836,6804);
	  		$this->db->select('hashUrl, thumb, nome, grupo, heroi');    	
			$this->db->group_by('nome');
			$this->db->group_by('categoria');		
			
			$this->db->from('videos');			
			$this->db->limit(1,$num);
			
			$data   = $this->db->get()->result_array();

			if (is_array($data)) {
				if (array_key_exists(0,$data)) {	
					$dado[] = $data[0];
					$data   = array();
				}
			}

		}

		if (is_array($dado)) {
			if (array_key_exists(0,$dado)) {
				return $dado;		
			}
		}
		
  	}



  	function getVideo($id) 
	{

		$this->db->select('hashUrl, thumb, heroi, nome, grupo, valor, categoria');
		$this->db->from('videos');
		$this->db->like('hashUrl', $id, 'none');		
		$this->db->limit(1);
				 	
      	return $this->db->get()->result_array();	

	}

	function getVideoUrl($url)
	{

		$this->db->select('hashUrl');
        $this->db->from('videos');
        $this->db->where(array('hashUrl' => $url));

        return $this->db->get()->result_array();       

	}

	function getVideoInterno($url)
	{

		$this->db->select('idVideo');
        $this->db->from('videos');
        $this->db->where(array('hashUrl' => $url));
        $this->db->limit(1);

        return $this->db->get()->result_array();

	}

	function getVideoInterno2($idVideo)
	{

		$this->db->select('hashUrl, thumb, heroi, nome, grupo, valor');
        $this->db->from('videos');
        $this->db->where(array('idVideo' => $idVideo));
        $this->db->limit(1);
        
        return $this->db->get()->result_array();

	}


}


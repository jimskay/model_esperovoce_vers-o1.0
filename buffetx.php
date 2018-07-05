
<?php 

class Buffetx extends CI_Model {

  function __construct() {
  	parent::__construct();
  }

  function pegar_buffet($buffet = '')
  {  		

    $this->db->select('u.idUnidade, u.descricao');
  	$this->db->from('franquias as f');
  	$this->db->join('unidades as u', 'u.idFranquia = f.idFranquia');
  	$this->db->where('f.idFranquia', $buffet);

  	$data = $this->db->get()->result_array();

  	if (array_key_exists('0', $data)) {

  		return $data;
    	
  	} else {

  		return;

  	}	

  }

  function pegar_buffet_matriz($cidade)
  {  		

    $this->db->select();
  	$this->db->from('franquias as f');	      	
  	$this->db->like('f.idCidade', $cidade, 'none');
  	$this->db->limit(1);
  	
  	$data = $this->db->get()->result_array();

  	if (array_key_exists('0', $data)) {

  		return $data;
    	
  	}

  }

  function pegar_buffet_matriz_codigo($codigo)
  {     

    $this->db->select();
    $this->db->from('unidades as f');          
    $this->db->like('f.codBuffet', $codigo, 'none');
    $this->db->limit(1);
    
    $data = $this->db->get()->result_array();

    if (array_key_exists('0', $data)) {

      return $data;
      
    }

  }

  function pegar_buffet_id($idBuffet)
  {

    $this->db->select();
    $this->db->from('unidades as f');          
    $this->db->like('f.idUnidade', $idBuffet, 'none');
    $this->db->limit(1);
    
    $data = $this->db->get()->result_array();

    if (array_key_exists('0', $data)) {

      return $data;
      
    }

  }


  function pegar_buffet_localizacao($id = '')
  {  	  		

    $this->db->select('lat, lng, descricao, endereco, bairro, cep, uf, imagem, modelo_convite');
  	$this->db->from('unidades');      	
    $this->db->where('idUnidade', $id);
    $this->db->limit(1);
    $data = $this->db->get()->result_array();

    if (array_key_exists('0', $data)) {

    	return $data;
    	
    } else {

    	return;

    }	

  }

  function pegar_buffet_fotos($id = '')
  {         
    
    $this->db->select('arquivo');
    $this->db->from('buffet_galeria');        
    $this->db->where('idUnidade', $id);    
    
    $d = $this->db->get()->result_array();
    
    if (array_key_exists('0', $d)) {

      return $d;      
      
    } else {

      return;

    } 

  }

  function pegar_imagem_buffet($idUnidade)
  {
    
    $this->db->select('imagem_video');
    $this->db->from('unidades as f');
    $this->db->like('f.idUnidade', $idUnidade, 'none');
    $this->db->limit(1);
    
    $data = $this->db->get()->result_array();

    if (array_key_exists('0', $data)) {

      return $data;
      
    }    

  }

	
}
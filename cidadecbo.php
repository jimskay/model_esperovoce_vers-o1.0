<?php 

class Cidadecbo extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function getEstados() {

		$this->db->select('id,uf');
		$this->db->from('tb_estados');
		 	
      	return $this->db->get()->result_array();

	}
	
	function getCidades($id = null) {
		
		if(!is_null($id)) {

			$this->db->where( array('estado' => $id) );	
						
			return $this->db->select('*')
						->from('tb_cidades as cid')						
						->get()->result_array();						
		}					
	}

	function getCidadeEscolhida($id = null) {
		
		if(!is_null($id)) {

			$this->db->where( array('id' => $id) );	
						
			return $this->db->select('*')
						->from('tb_cidades as cid')						
						->get()->result_array();						
		}					
	}


	function getEstadoEscolhido($id = null) {
		
		if(!is_null($id)) {

			$this->db->where( array('id' => $id) );	
						
			return $this->db->select('*')
						->from('tb_estados as est')						
						->get()->result_array();						
		}					
	}
	
}
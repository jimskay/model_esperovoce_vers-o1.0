<?php 


class Cidade extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function getEstados() 
	{

		$this->db->select('DISTINCT(cid.id), cid.nome, cid.uf');
		$this->db->from('usuarios as u');
      	$this->db->join('tb_cidades as cid', 'cid.id = u.id_cidade');
      	$this->db->where(array('u.status' => '1'));
      	return $this->db->get()->result_array();	

	}
	
	function getCidades($id = null) 
	{
		
		if(!is_null($id)) {

			$this->db->where( array('uf' => $id) );	
		
			return $this->db->select('DISTINCT(cid.id), cid.nome, cid.uf, count(u.id_usuario) as pessoas')
						->from('usuarios as u')
						->join('tb_cidades as cid', 'cid.id = u.id_cidade')
						->where(array('u.status' => '1'))
						->get()->result_array();	
		}					
	}

	function getCidade($idCidade)
	{

		return $this->db->select('DISTINCT(cid.id), cid.nome, cid.uf')				
				->from('tb_cidades as cid')
				->where(array('cid.id' => $idCidade))
				->get()->result_array();	

	}

}
<?php 


class Cliente extends CI_Model {

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
/*
	function getCliente($usuario) 
	{

		$this->db->select(
						'DISTINCT(cid.id), 
						cid.nome as cidade, 
						cid.uf, 
						cli.idCliente, 
						cli.nome, 
						cli.email, 
						cli.endereco, 
						cli.numero, 
						cli.complemento, 
						cli.bairro, 
						cli.cep, 
						cli.ddd, 
						cli.telefone, 
						cli.cpf'
		);

		$this->db->from('cliente as cli');      
		$this->db->join('tb_cidades as cid', 'cid.id = cli.id_cidade');	
      	$this->db->where(array('cli.email' => $usuario));
      	return $this->db->get()->result_array();      	
      	//echo $this->db->last_query();

	}
*/

	function getCliente($usuario) 
	{

		$this->db->select(
							'cli.idCliente, 
							cli.nome, 
							cli.email, 
							cli.endereco, 
							cli.numero, 
							cli.complemento, 
							cli.bairro, 
							cli.cep, 
							cli.ddd, 
							cli.telefone, 
							cli.cpf,
							cli.id_cidade,
							cli.uf'
		);

		$this->db->from('cliente as cli');
		$this->db->where(array('cli.email' => $usuario));
	  	
	  	return $this->db->get()->result_array();      	
      	//echo $this->db->last_query();

	}

	function getUsuario($usuario)
	{

		$this->db->select(
							'usuario,
							password'
		);

		$this->db->from('usuario');
		$this->db->where(array('usuario' => $usuario));
	  	
	  	return $this->db->get()->result_array();      	

	}

}

?>
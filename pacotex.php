<?php 

class Pacotex extends CI_Model {

	public function getPacote($servico)
	{

		$this->db->where(array('descricao' => $servico));	
		$this->db->from('pacote');
		$this->db->select('valor');		
		return $this->db->get()->result_array();

	}
	
}

?>
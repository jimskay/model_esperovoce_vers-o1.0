<?php 

class Smsx extends CI_Model {

	function addTelefone($dado) 
	{

		$data = array(
						'idCliente' => $dado['idCliente'],
						'idConvite' => $dado['idConvite']															
				);
		
		if (array_key_exists('telefone' , $dado)) {

			$data['telefone'] = $dado['telefone'];			
			
		} 

		if (array_key_exists('nome' , $dado)) {

			$data['nome'] = $dado['nome'];
		
		}


		$this->db->where(array('idCliente' => $data['idCliente']));	
		$this->db->where(array('idConvite' => $data['idConvite']));	
		$dado = $this->db->select('idSms')
			->from('sms')						
			->get()->result_array();

		if (array_key_exists('0', $dado)) {	

			$this->db->where('idSms', $dado[0]['idSms']);
    		$this->db->update('sms', $data);

    		return true;

		} else {

			$this->db->insert('sms', $data);

			return true;

		}


	}

	function removeTelefone($dado)
	{
		
		$this->db->where(array('idCliente'=> $dado['idCliente']));						
		$this->db->where(array('idConvite' => $dado['idConvite']));	
		$dado = $this->db->select('idSms')
			->from('sms')						
			->get()->result_array();

		if (array_key_exists('0', $dado)) {

			$idSms = $dado[0]['idSms'];

			$this->db->where('idSms', $idSms);
			$this->db->delete('sms');	

		}			

	}

	function pegaSms($idCliente)
	{

		$this->db->where(array('idCliente' => $idCliente));	
		$dado = $this->db->select('telefone, nome')
			->from('sms')						
			->get()->result_array();

		return $dado;	
	}

	function qtdSms($idCliente)
	{
		$this->db->where(array('idCliente' => $idCliente));
		$this->db->from('sms');
		return $this->db->count_all_results();
	}
	
}

?>
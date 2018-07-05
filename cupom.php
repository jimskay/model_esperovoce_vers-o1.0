<?php 

class Cupom extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function getCupom($cupom) 
	{

		$this->db->select('dt_validade, status_ativo');
        $this->db->from('cupom');
        $this->db->where(array('cupom' => $cupom));

        $clientePedido = $this->db->get()->result_array();

        if (array_key_exists('0', $clientePedido)) { 

        	if ($clientePedido[0]['dt_validade'] >= date('Y-m-d') && $clientePedido[0]['status_ativo'] == 0) {

        		return true;

        	} else {

        		return false;

        	}
        	
    	} else {

            return false;
            
        }       

	}

	function getStatusCupom($cupom) 
	{

		$this->db->select('status_ativo');
        $this->db->from('cupom');
        $this->db->where(array('cupom' => $cupom));

        $clientePedido = $this->db->get()->result_array();

		return $clientePedido[0]['status_ativo'];

	}

    function getCupomPersonalizado($cupom)
    {

        $this->db->select();
        $this->db->from('cupom');
        $this->db->where(array('cupom' => $cupom));

        $clientePedido = $this->db->get()->result_array();

        return $clientePedido[0]['codigo_buffet'];

    }

}

?>

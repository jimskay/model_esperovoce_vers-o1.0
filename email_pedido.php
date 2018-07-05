<?php 

class Email_pedido extends CI_Model {

	function __construct() {
		parent::__construct();		        
	}
	
	function pegaMensagem($idPedido) 
	{

		$this->db->select('mensagem, nome');
        $this->db->from('email_pedido');
        $this->db->where(array('idPedido' => $idPedido));
        $this->db->limit(1);
        $this->db->order_by('idEmailPedido', "desc");
        $dados = $this->db->get()->result_array();

        return $dados;

	}

}
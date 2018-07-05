<?php
class Loginx extends CI_Model {


    function validate() 
    {

        $this->db->where('usuario', $this->input->post('username')); 
        $this->db->where('password', base64_encode($this->input->post('password')));
        $this->db->where('status', 1); // Verifica o status do usuário

        $query = $this->db->get('usuario');
        
        if ($query->num_rows == 1) { 
            return true; // RETORNA VERDADEIRO
        }

    }

    function logged() 
    {

        $logged = $this->session->userdata('logado');

        if (!isset($logged) || $logged != true) {
            redirect('/');
        }

    }

    function verificaEmail($email)
    {

        $this->db->select();
        $this->db->from('usuario as u');      
        $this->db->join('cliente as c', 'u.usuario = c.email');
        $this->db->where(array('c.email' => $email));     
        $this->db->where(array('u.status' => 1));            
        $query = $this->db->get();
        
        if ($query->num_rows == 1) { 

            return true;

        } else {

            return false;

        }        

    }

    function recuperaSenha($email)
    {

        $this->db->select();
        $this->db->from('usuario as u');      
        $this->db->join('cliente as c', 'u.usuario = c.email');
        $this->db->where(array('c.email' => $email));     
        $this->db->where(array('u.status' => 1));            
        $dado = $this->db->get()->result_array();

        $hash = rand().date('Ymd');
        $hash = md5($hash);        
        $data = array(
            'hash'      => $hash,
            'idCliente' => $dado[0]['idCliente'],
            'idUsuario' => $dado[0]['id'],
            'data'      => date('Y-m-d h:m:s'),
            'status'    => 0
        );

        $this->db->insert('recupera_senha', $data);

        return $hash;

    }

    function verificaHash($hash)
    {

        $this->db->select();
        $this->db->from('recupera_senha'); 
        $this->db->where(array('hash'   => $hash)); 
        $this->db->where(array('status' => 0)); 
        $query = $this->db->get();
        
        if ($query->num_rows == 1) { 

            return true;

        } else {

            return false;

        }        

    }

    function mudarSenha($senha, $hash)
    {
        if ($this->verificaHash($hash)) {

            $this->db->select();
            $this->db->from('recupera_senha'); 
            $this->db->where(array('hash'   => $hash)); 
            $this->db->where(array('status' => 0));
            $dado = $this->db->get()->result_array();

            $this->db->where('id', $dado[0]['idUsuario']);
            $this->db->update('usuario', array('password' => base64_encode($senha)));                
            $this->db->where(array('hash' => $hash)); 
            $this->db->update('recupera_senha', array('status' => 1));

            return true;           

        }
    }


}

?>
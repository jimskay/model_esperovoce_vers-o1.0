<?php

/*
  CLASSE Pedido

  > save (public) - salva uma nova informação no DB de dados, seja inserção ou edição # em caso de erro retorna uma mensagem com o erro
  > delete (public) - remove um dado do DB
  > select (public) - seleciona dados do DB relativos a classe corrente [1. atributos para filtragem da consulta] # array com objetos criados
  > security (private) - segurança quando essa classe é salva
  > originalVar (public) - resgata uma variável sem qualquer tipo de formatação [1. nome da variável] # valor original da variável
 */

class Pedido {

    private $fields;
    private $db;
    private $idPedido;     //	int(11) - 1
    private $dataPedidoInicio;     //	datetime - 1
    private $dataPedido;     //	datetime - 1
    private $idCliente;     //	varchar(150) - 1
    private $sessionID;     //	varchar(30) - 1
    private $ipCliente;     //	varchar(30) - 0
    private $statusPedido;     //	varchar(30) - 0    
    private $subtotal;     //	float - 1
    private $taxaEnvio;     //	float - 0
    private $taxaAdicional;     //	float - 0
    private $total;     //	float - 1
    private $tipoFrete;     //	varchar(30) - 0
    private $pesoTotal;     //	float - 1
    private $atendido;     //	smallint(6) - 0
    private $pago;     //	smallint(6) - 0
    private $falha;     //	smallint(6) - 0
    private $cancelado;     //	smallint(6) - 0
    private $devolvido;     //	smallint(6) - 0
    private $fraude;     //	smallint(6) - 0
    private $dataPedidoAberto;     //	float - 0
    private $dataPedidoFechado;     //	float - 0
    private $dataPedidoPago;     //	float - 0
    private $dataPedidoAtendido;     //	float - 0
    private $dataPedidoCancelado;     //	float - 0
    private $codEmpresa;     //	int
    private $tipo;
    private $descricao;
    private $idEmpresa;
    private $prioridade;
    private $idEquipamento;
    private $qtdAlunoMaquina;
    private $dataPedidoEntrega;
    private $hash;
    private $statusAutoriza;
    private $servico;
	private $nome;					//	varchar(255) - 0
	private $email;					//	varchar(255) - 0
	private $telefone;					//	varchar(20) - 0
    private $outrasInformacoes;                 //  text - 0
	private $idDepartamento;					//	text - 0
    private $idOperador;



    public function __construct(Database $db, $idPedido = NULL) {
        $this->db = $db;
        $fields = $this->db->listFields('pedido');
        if ($fields !== false) {
            foreach ($fields as $f) {
                $this->fields[] = $f[0];
            }
        } else {
            $this->fields = NULL;
        }
        if ($idPedido !== NULL) {
            $attribute = "where idPedido = '" . $idPedido . "'";
            $resultado = $this->db->select('pedido', $this->fields, $attribute);
            if ($resultado !== false) {
                $this->idPedido = $resultado[0]['idPedido'];
                $this->dataPedidoInicio = $resultado[0]['dataPedidoInicio'];
                $this->dataPedido = $resultado[0]['dataPedido'];
                $this->idCliente = $resultado[0]['idCliente'];
                $this->sessionID = $resultado[0]['sessionID'];
                $this->ipCliente = $resultado[0]['ipCliente'];
                $this->statusPedido = $resultado[0]['statusPedido'];               
                $this->subtotal = $resultado[0]['subtotal'];
                $this->taxaEnvio = $resultado[0]['taxaEnvio'];
                $this->tipoTaxaAdicional = $resultado[0]['tipoTaxaAdicional'];
                $this->taxaAdicional = $resultado[0]['taxaAdicional'];
                $this->total = $resultado[0]['total'];
                $this->tipoFrete = $resultado[0]['tipoFrete'];
                $this->codigoFrete = $resultado[0]['codigoFrete'];
                $this->pesoTotal = $resultado[0]['pesoTotal'];
                $this->atendido = $resultado[0]['atendido'];
                $this->pago = $resultado[0]['pago'];
                $this->falha = $resultado[0]['falha'];
                $this->cancelado = $resultado[0]['cancelado'];
                $this->devolvido = $resultado[0]['devolvido'];                
                $this->dataPedidoAberto = $resultado[0]['dataPedidoAberto'];
                $this->dataPedidoFechado = $resultado[0]['dataPedidoFechado'];
                $this->dataPedidoPago = $resultado[0]['dataPedidoPago'];
                $this->dataPedidoAtendido = $resultado[0]['dataPedidoAtendido'];
                $this->codEmpresa = $resultado[0]['codEmpresa'];
                $this->tipo = $resultado[0]['tipo'];
                $this->descricao = $resultado[0]['descricao'];
                $this->idEmpresa = $resultado[0]['idEmpresa'];
                $this->prioridade = $resultado[0]['prioridade'];
                $this->idPedido = $resultado[0]['idPedido'];
                $this->qtdAlunoMaquina = $resultado[0]['qtdAlunoMaquina'];
                $this->dataPedidoEntrega = $resultado[0]['dataPedidoEntrega'];
                $this->hash = $resultado[0]['hash'];
                $this->statusAutoriza = $resultado[0]['statusAutoriza'];
                $this->servico = $resultado[0]['servico'];
				$this->nome = $resultado[0]['nome'];
				$this->email = $resultado[0]['email'];
				$this->telefone = $resultado[0]['telefone'];
                $this->outrasInformacoes = $resultado[0]['outrasInformacoes'];
                $this->idDepartamento = $resultado[0]['outrasInformacoes'];
				$this->idOperador = $resultado[0]['idOperador'];
            }
        }
    }

    public function __toString() {
        return $this->idPedido;
    }

    public function __set($var, $value) {
        switch ($var) {
            case 'DataPedidoInicio' : $this->dataPedidoInicio = Validation::revDate($value, "/", "-");
                break;
            case 'DataPedido' : $this->dataPedido = Validation::revDate($value, "/", "-");
                break;
            case 'IdCliente' : $this->idCliente = Validation::toBd($value);
                break;
            case 'SessionID' : $this->sessionID = Validation::toBd($value);
                break;
            case 'IpCliente' : $this->ipCliente = Validation::toBd($value);
                break;
            case 'StatusPedido' : $this->statusPedido = $value;
                break;           
            case 'Subtotal' : $this->subtotal = $value;
                break;
            case 'TaxaEnvio' : $this->taxaEnvio = $value;
                break;
            case 'Total' : $this->total = $value;
                break;
            case 'PesoTotal' : $this->pesoTotal = $value;
                break;
            case 'Atendido' : $this->atendido = $value;
                break;
            case 'Pago' : $this->pago = $value;
                break;
            case 'Falha' : $this->falha = $value;
                break;
            case 'Cancelado' : $this->cancelado = $value;
                break;
            case 'Devolvido' : $this->devolvido = $value;
                break;
            case 'Fraude' : $this->fraude = $value;
                break;
            case 'DataPedidoAberto' : $this->dataPedidoAberto = Validation::revDate($value, "/", "-");
                break;
            case 'DataPedidoFechado' : $this->dataPedidoFechado = Validation::revDate($value, "/", "-");
                break;
            case 'DataPedidoPago' : $this->dataPedidoPago = Validation::revDate($value, "/", "-");
                break;
            case 'DataPedidoAtendido' : $this->dataPedidoAtendido = Validation::revDate($value, "/", "-");
                break;
            case 'DataPedidoCancelado' : $this->dataPedidoCancelado = Validation::revDate($value, "/", "-");
                break;
            case 'CodEmpresa' : $this->codEmpresa = $value;
                break;
            case 'Tipo' : $this->tipo = $value;
                break;
            case 'Descricao' : $this->descricao = $value;
                break;
            case 'IdEmpresa' : $this->idEmpresa = $value;
                break;
            case 'Prioridade' : $this->prioridade = $value;
                break;
            case 'IdEquipamento' : $this->idEquipamento = $value;
                break;
            case 'QtdAlunoMaquina' : $this->qtdAlunoMaquina = $value;
                break;
            case 'DataPedidoEntrega' : $this->dataPedidoEntrega = $value;
                break;
            case 'Hash' : $this->hash = $value;
                break;
            case 'StatusAutoriza' : $this->statusAutoriza = $value;
                break; 
            case 'Servico' : $this->servico = $value;
                break;
			case 'Nome'		:	$this->nome = $value;				
				break;
			case 'Email'		:	$this->email = $value;				
				break;
			case 'Telefone'		:	$this->telefone = $value;				
				break;
			case 'OutrasInformacoes'     :   $this->outrasInformacoes = $value;
                break;
            case 'IdDepartamento'       :   $this->idDepartamento = $value;
                break;            
            case 'IdOperador'       :   $this->idOperador = $value;
                break;            
        }
    }

    public function __get($var) {
        switch ($var) {
            case 'IdPedido' : return $this->idPedido;
                break;
            case 'IdPedido64' : return base64_encode($this->idPedido);
                break;
            case 'DataPedidoInicio' : return Validation::sqlToDateTime($this->dataPedidoInicio);
                break;
            case 'DataPedido' : return Validation::sqlToDateTime($this->dataPedido);
                break;
            case 'IdCliente' : return $this->idCliente;
                break;
            case 'SessionID' : return Validation::toHTML($this->sessionID);
                break;
            case 'IpCliente' : return Validation::toHTML($this->ipCliente);
                break;
            case 'StatusPedido' : return $this->statusPedido;
                break;          
            case 'Subtotal' : return $this->subtotal;
                break;
            case 'TaxaEnvio' : return $this->taxaEnvio;
                break;            
            case 'Total' : return $this->total;
                break;
            case 'Atendido' : return $this->atendido;
                break;
            case 'Pago' : return $this->pago;
                break;
            case 'Falha' : return $this->falha;
                break;
            case 'Cancelado' : return $this->cancelado;
                break;
            case 'Devolvido' : return $this->devolvido;
                break;
            case 'Fraude' : return $this->fraude;
                break;
            case 'DataPedidoAberto' : return Validation::sqlToDateTime($this->dataPedidoAberto);
                break;
            case 'DataPedidoFechado' : return Validation::sqlToDateTime($this->dataPedidoFechado);
                break;
            case 'DataPedidoPago' : return Validation::sqlToDateTime($this->dataPedidoPago);
                break;
            case 'DataPedidoAtendido' : return Validation::sqlToDateTime($this->dataPedidoAtendido);
                break;
            case 'DataPedidoCancelado' : return Validation::sqlToDateTime($this->dataPedidoCancelado);
                break;
            case 'CodEmpresa' : return $this->codEmpresa;
                break;
            case 'Tipo' : return $this->tipo;
                break;
            case 'Descricao' : return $this->descricao;
                break;
            case 'IdEmpresa' : return $this->idEmpresa;
                break;
            case 'Prioridade' : return $this->prioridade;
                break;
            case 'IdEquipamento' : return $this->idEquipamento;
                break;
            case 'QtdAlunoMaquina' : return $this->qtdAlunoMaquina;
                break;
            case 'DataPedidoEntrega' : return Validation::sqlToDateTime($this->dataPedidoEntrega);
                break;
            case 'Hash' : return $this->hash;
                break;
            case 'StatusAutoriza' : return $this->statusAutoriza;
                break;
            case 'Servico' : return $this->servico;
                break;
			case 'Nome'		:	return $this->nome;		
				break;
			case 'Email'		:	return $this->email;	
				break;
			case 'Telefone'		:	return $this->telefone;		
				break;
			case 'OutrasInformacoes'     :   return $this->outrasInformacoes;
                break;
            case 'IdDepartamento'       :   return $this->idDepartamento;
                break;
            case 'IdOperador'       :   return $this->idOperador;
                break;                            
        }
    }

    //	SAVE
    public function save($fields = '', $values = '') {

        //$check = self::security();
        /*
        if ($check !== true) {
            return $check;
        }
        */

        if ($this->idPedido === NULL) {
            return self::insert();
        } else {
            return self::update($fields,$values);
        }
    }

    //	INSERT
    public function insert() {

        $fields = array('dataPedidoInicio', 'dataPedido', 'idCliente', 'sessionID', 'ipCliente', 'statusPedido','subtotal', 'total', 'atendido', 'pago', 'falha', 'cancelado', 'devolvido', 'dataPedidoAberto', 'dataPedidoFechado', 'dataPedidoPago', 'dataPedidoAtendido', 'dataPedidoCancelado', 'tipo', 'descricao', 'idEmpresa', 'prioridade', 'idEquipamento', 'qtdAlunoMaquina', 'dataPedidoEntrega', 'hash', 'statusAutoriza', 'servico','nome','email','telefone','outrasInformacoes', 'idDepartamento');
        $values = array($this->dataPedidoInicio, $this->dataPedido, $this->idCliente, $this->sessionID, $this->ipCliente, $this->statusPedido, $this->subtotal, $this->total, $this->atendido, $this->pago, $this->falha, $this->cancelado, $this->devolvido, $this->dataPedidoAberto, $this->dataPedidoFechado, $this->dataPedidoPago, $this->dataPedidoAtendido, $this->dataPedidoCancelado, $this->tipo, $this->descricao, $this->idEmpresa, $this->prioridade, $this->idEquipamento, $this->qtdAlunoMaquina, $this->dataPedidoEntrega, $this->hash, $this->statusAutoriza, $this->servico, $this->nome, $this->email, $this->telefone, $this->outrasInformacoes, $this->idDepartamento);
        $check = $this->db->insert('pedido', $fields, $values);
        $this->idPedido = $check === true ? mysqli_insert_id($this->db->conexao) : NULL;
        $return = $check === true ? true : false;
        return $return;
    }

    //	UPDATE
    public function update($fields = '', $values = '') {
        
        if ($fields == '') {
            $fields = array('dataPedidoInicio', 'dataPedido', 'idCliente', 'sessionID', 'ipCliente', 'statusPedido', 'subtotal', 'total', 'atendido', 'pago', 'falha', 'cancelado', 'devolvido', 'dataPedidoAberto', 'dataPedidoFechado', 'dataPedidoPago', 'dataPedidoAtendido', 'dataPedidoCancelado', 'descricao', 'idEmpresa', 'prioridade', 'idEquipamento', 'qtdAlunoMaquina', 'dataPedidoEntrega', 'hash', 'statusAutoriza', 'servico','nome','email','telefone','outrasInformacoes', 'idDepartamento', 'idOperador');
        }

        if ($values == '') {
            $values = array($this->dataPedidoInicio, $this->dataPedido, $this->idCliente, $this->sessionID, $this->ipCliente, $this->statusPedido, $this->subtotal, $this->total, $this->atendido, $this->pago, $this->falha, $this->cancelado, $this->devolvido, $this->dataPedidoAberto, $this->dataPedidoFechado, $this->dataPedidoPago, $this->dataPedidoAtendido, $this->dataPedidoCancelado, $this->descricao, $this->idEmpresa, $this->prioridade, $this->idEquipamento, $this->qtdAlunoMaquina, $this->dataPedidoEntrega, $this->hash, $this->statusAutoriza, $this->servico, $this->nome, $this->email, $this->telefone, $this->outrasInformacoes, $this->idDepartamento, $this->idOperador);
        }

        $attribute  = "where idPedido = '" . $this->idPedido . "'";
        $check      = $this->db->update('pedido', $fields, $values, $attribute);
        $return     = $check === true ? true : false;

        return $return;
    }

    //	DELETE
    public function delete() {
        if ($this->idPedido !== NULL) {
            $attribute = "where idPedido = '" . $this->idPedido . "'";
            $check = $this->db->delete('pedido', $attribute);
            $return = $check === true ? true : false;
        } else {
            $return = false;
        }
        $this->idPedido = $return === true ? NULL : $this->idPedido;
        return $return;
    }

    //	SELECT
    public function select($attribute = NULL) {
        $resultado = $this->db->select('pedido', $this->fields, $attribute);
        if ($resultado !== false) {
            $return = array();
            foreach ($resultado as $f) {
                $return[] = new Pedido($this->db, $f['idPedido']);
            }
        } else {
            $return = false;
        }
        return $return;
    }

    //	SECURITY
    private function security() {


        //	idCliente
        if ($this->idCliente == '') {
            return 'idCliente deve estar preenchido.';
        }

        //	sessionID
        if ($this->sessionID == '') {
            return 'sessionID deve estar preenchido.';
        }

        //	ipCliente
        //	sessionID
        if ($this->statusPedido == '') {
            $this->statusPedido = 0;
        }

       
        return true;
    }

    public function getActive($idCliente, $idSessao) {
        $pedido = self::select('where idCliente = ' . $idCliente . ' and statusPedido = 0 and sessionID = "' . $idSessao . '"');
        return $pedido == false ? false : new Pedido($this->db, $pedido[0]->IdPedido);
    }

    public function setStatus() {
        $fields = array('statusPedido');
        $values = array($this->statusPedido);
        $attribute = "where idPedido = '" . $this->idPedido . "'";
        $check = $this->db->update('pedido', $fields, $values, $attribute);
        $return = $check === true ? true : false;
        return $return;
    }

    public function originalVar($var) {
        return $this->{$var};
    }

}

?>
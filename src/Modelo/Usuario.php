<?php
namespace Modelo;

/** 
 * Classe do usuário do administrador do banco de dados
 * @author Minello
 *
 */
class Usuario {
	private $id;
	private $nome;
	private $email;
	private $senha;
	
	public function __construct() {
		
	}
	
	public function hashSenha($senha) {
		$options = array(
		    'cost' => 9,
		    'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)
		);
		
		$this->__set('senha', password_hash($senha, PASSWORD_BCRYPT, $options));
	}
	
	public function __set($nome, $valor) {
		if (property_exists(get_class($this), $nome))
			$this->$nome = $valor;
	}
	
	public function __get($nome) {
		if (property_exists(get_class($this), $nome))
			return $this->$nome;
		else
			return false;
	}
}
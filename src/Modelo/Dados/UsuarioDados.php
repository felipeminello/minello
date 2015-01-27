<?php
namespace Modelo\Dados;

use Modelo\Dados\BancoDados;
use Modelo\Usuario;

class UsuarioDados extends BancoDados {
	public function __construct() {
		parent::__construct();
	}
	
	public function inserir(Usuario $u) {
		$this->bind('nome', $u->__get('nome'));
		$this->bind('email', $u->__get('email'));
		$this->bind('senha', $u->__get('senha'));
		
		return $this->query("INSERT INTO admin (nome, email, senha) VALUES (:nome, :email, :senha)");
	}
}
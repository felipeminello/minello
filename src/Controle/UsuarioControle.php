<?php
namespace Controle;

use Modelo\Usuario;
use Modelo\Dados\UsuarioDados;

class UsuarioControle {

	public function inserirAction() {
		$u = new Usuario();
		$uDados = new UsuarioDados();
		
		$u->__set('nome', 'Felipe Minello');
		$u->__set('email', 'felipeminello@gmail.com');
		$u->hashSenha(123);
		
		return $uDados->inserir($u);
	}
	
}
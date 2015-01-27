<?php
header('Content-type: text/html; charset=iso-8859-1');

use Modelo\Funcao;

require_once __DIR__.'/../vendor/autoload.php';

Twig_Autoloader::register();

$pag = Funcao::checkString(Funcao::get('pag'));

$arrayUrl = explode('/', $pag);

if (empty($arrayUrl[0]))
	$arrayUrl[0] = 'home';

if (strtolower($arrayUrl[0]) == 'admin') {
	if (is_file(__DIR__.'/../src/admin/Controle/acao/'.$arrayUrl[1].'.php'))
		include_once __DIR__.'/../src/admin/Controle/acao/'.$arrayUrl[1].'.php';
} else {
	if (is_file(__DIR__.'/../src/Controle/acao/'.$arrayUrl[0].'.php'))
		include_once __DIR__.'/../src/Controle/acao/'.$arrayUrl[0].'.php';
}

try {
	// specify where to look for templates
	$loader = new Twig_Loader_Filesystem('../src/Visao');
	
	// initialize Twig environment
	$twig = new Twig_Environment($loader);
	
	// load template
	$template = $twig->loadTemplate($pag.'.tpl');

	// set template variables
	// render template
	echo $template->render(array(
		'name' => 'Clark Kents',
		'siteNome' => 'Minello Development',
		'username' => 'ckent',
		'password' => 'krypt0n1te',
		'produtos' => array(
			1 => 'Teste 1',
			2 => 'Teste 2',
			3 => 'Teste 3'
		)
	));
} catch (Exception $e) {
	die ('ERRO: ' . $e->getMessage());
}
<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'classes/Estoque.php';

class Rest
{
	public static function open($requisicao)
	{
		$url = explode('/', $requisicao['url']);
		
		$classe = ucfirst($url[0]);
		array_shift($url);

		$metodo = $url[0];
		array_shift($url);

		$parametros = array();
		$parametros = $url;

		try {
			if (class_exists($classe)) {
				if (method_exists($classe, $metodo)) {
					$retorno = call_user_func_array(array(new $classe, $metodo), $parametros);

					return json_encode(array('status' => 'sucesso', 'dados' => $retorno));
				} else {
					# retorno para erro poderia ser um response_code 
					# retorno para erro poderia ser um response_code  no lugar do json com um array de dados.
					http_response_code(404);
					# O erro 404 ou 4:04 é um código de resposta HTTP que indica que o cliente pôde comunicar com o servidor, mas o servidor não pôde encontrar o que foi pedido, 
					# ou foi configurado para não cumprir o pedido e não revelar a razão, a página não existe mais ou a URL foi inserida incorretamente. Wikipédia
					// return json_encode(array('status' => 'erro', 'dados' => 'Método inexistente!'));
				}
			} else {
				http_response_code(404);
				// return json_encode(array('status' => 'erro', 'dados' => 'Classe inexistente!'));
			}	
		} catch (Exception $e) {
			http_response_code(400);
			// return json_encode(array('status' => 'erro', 'dados' => $e->getMessage()));
		}
		
	}
}

if (isset($_REQUEST)) {
	echo Rest::open($_REQUEST);
}

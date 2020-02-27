<?php
return [
	'ID_SITIO' => '455',
	'URL_API' => 'https://apirest.fidelitytools.net/api/',
	'URL_REST' => 'https://rest.fidelitytools.net/Fidelitytools/',
	'URL_WS' => 'https://ws.fidelitytools.net/v2/api',
	'FIDELITY_KEY' => '265c092ff1813072ffeb07ec2ab84e4d',
	'WS_KEY' => '5d52c64c5cb8340f980bdaab',


	'PAGE_CACHE' => false,
	'ID_ANALYTIICS' => '123',
	'IDIOMA_DEFAULT' => 'es',
	'MULTIDIOMAS' => true,
	'NOMBRE_SITIO' => "La Casa de la Comunicación",

	'ID_DESCUENTOS' => 0,
	'ID_ECOMMERCE' => 0,
	'ID_ECOMMERCE_ENCODE' => '',

	'JS_MAP' => false,
	'JS_SCROLLING' => false,
	'JS_SCROLLING_LIMIT' => false,
	'JS_SCROLLING_LOADING' => true,
	'JS_SHOPPING_CART' => false,

	'META_DESCRIPTION' => '',
	'META_KEYWORDS' => '',

	'USUARIO_TWITTER' => '@',
	'IS_CACHE' => (isset($_SERVER['SERVER_ADDR']) AND ($_SERVER['SERVER_ADDR'] == '10.1.2.10')) ? true : false,
	'CACHE' => true,
	'CACHE_CATEGORIA' => 7200,
	'CACHE_CONTENIDO' => 5,
	'CACHE_PERSONA' => 3600,
	'CACHE_PRODUCTO' => 600,
	'CACHE_ARCHIVO' => 7200,
	'CACHE_ETIQUETA' => 7200,
];

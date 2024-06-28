<?php

require __DIR__ . '/vendor/autoload.php';

require_once './app/Core/Core.php';
require_once './lib/database/Connection.php';


require_once './app/Controller/ErroController.php';
require_once './app/Controller/HomeController.php';
require_once './app/Controller/PetsController.php';
require_once './app/Controller/PetSingleController.php';
require_once './app/Controller/CanilController.php';
require_once './app/Controller/AdminCanilController.php';
require_once './app/Controller/LoginController.php';
require_once './app/Controller/CadastroController.php';
require_once './app/Controller/LogOutController.php';
require_once './app/Controller/AdminCanilEditController.php';
require_once './app/Controller/ValidaContaController.php';
require_once './app/Controller/RecuperarSenhaController.php';
require_once './app/Controller/EditProfileController.php';
require_once './app/Controller/NovaPostagemController.php';
require_once './app/Controller/UserController.php';
require_once './app/Controller/EditPostagemController.php';
require_once './app/Controller/FeedController.php';

require_once './app/Model/Postagem.php';
require_once './app/Model/Pets.php';
require_once './app/Model/Canil.php';
require_once './app/Model/Usuario.php';
require_once './app/Model/Auth.php';




$template = file_get_contents('app/Template/Template.html');
ob_start();
	$core = new Core;
	$core->start($_GET);

	$saida = ob_get_contents();
ob_end_clean();

$tplPronto = str_replace('{{area_dinamica}}', $saida, $template);
echo $tplPronto;
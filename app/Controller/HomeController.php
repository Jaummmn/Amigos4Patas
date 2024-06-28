<?php

class HomeController
{
        public function index()
        {
            try 
            {	
				
				$colecPostagensPets = Pets::selecionaRecentes();
				$colecPostagens = Postagem::SelecionarPostagens();
				$totalCanil= Canil::selecionaTotalCanil();
				$totalPetsRegistrados = Pets::selecionaTotalPet();
				$totalPetsAdotados = Pets::selecionaTotalPetAdotado();

		
				Usuario::Login();  
				
				
				$loader = new \Twig\Loader\FilesystemLoader('app/View');
				$twig = new \Twig\Environment($loader);
				$template = $twig->load('home.html');
			
		
				$nomeUsuario = isset($_SESSION['nomeUsuario']) ? $_SESSION['nomeUsuario'] : null;
				$idLogin = isset($_SESSION['idLogin']) ? $_SESSION['idLogin'] : null;
				$idCanilUser = isset($_SESSION['idCanilUser']) ? $_SESSION['idCanilUser'] : null;
				

				$imgProfile = Usuario::MostrarImage($idLogin);
				$imgPathProfile = ($imgProfile !== null && isset($imgProfile['imgpath'])) ?$imgProfile['imgpath'] : null;
			
				$imgProfileCanil = Canil::MostrarImageCanil($idCanilUser);
				$imgPathCanilProfile = ($imgProfileCanil !== null && isset($imgProfileCanil['imgpath'])) ? $imgProfileCanil['imgpath'] : null;
		
			$parametros = array(
                'nomeUsuario' => $nomeUsuario,
                'idCanilUser' => $idCanilUser,
                'idLogin' => $idLogin,
                'total_registros' => $totalCanil['total_registros'],
                'total_registros_Pets' => $totalPetsRegistrados['total_registros_Pets'],
                'total_registros_Pets_adotados' => isset($totalPetsAdotados["total_registros_Pets_adotado"]) ? $totalPetsAdotados["total_registros_Pets_adotado"] : 0
            );

	

			
		
				foreach ($colecPostagensPets as $pet) {
					$parametros['pet_descricao'][]= $pet['pet_descricao'];
					$parametros['nomePets'][] = $pet['nomePet'];
					$parametros['idPet'][] = $pet['idPet'];   
				
					$imgPet = Pets::MostrarImage( $pet['idPet']);
					$imgPathPet = ($imgPet !== null && isset($imgPet['imgpath'])) ? $imgPet['imgpath'] : null;
					$parametros['imgPathPet'][]= $imgPathPet;
				}
				
			 foreach ($colecPostagens as $postagens) {
                        
                        $imgPostagem = Postagem::MostrarImagePostagem($postagens['idPostagem']);
                        $imgUser = Usuario::MostrarImage($postagens['idUsuarioPostagem']);
                       
                        $imgPathPostagem = ($imgPostagem !== null && isset($imgPostagem['imgpath'])) ? $imgPostagem['imgpath'] : null;
					    $imgPathPostagemUser = ($imgUser !== null && isset($imgUser['imgpath'])) ?$imgUser['imgpath'] : null;
                    
                        $parametros['postagens'][] = [
                            'nomeUsuario'=>$imgUser['nomeUsuario'],
						    'imgPathPostagemUser'=>$imgPathPostagemUser,
                            'idUsuarioPostagem'=>$postagens['idUsuarioPostagem'],
                            'tituloPostagem' => $postagens['tituloPostagem'],
                            'textoPostagem' => $postagens['textoPostagem'],
                            'imgPathPostagem' => $imgPathPostagem
                        ];
                    }

				if(isset($idCanilUser)){
					$parametros['imgPath']= $imgPathCanilProfile;
				}
				else
				{
					$parametros['imgPath']= $imgPathProfile;
				}
			
				

				$conteudo = $template->render($parametros);
				echo $conteudo;

			

			} catch (Exception $e) {
				echo $e->getMessage();
			}
			
		}

    
}
    
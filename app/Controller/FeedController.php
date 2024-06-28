<?php
class FeedController
{
   
            public function index()
            {
                try 
                {	
                    
               
                    $colecPostagens = Postagem::SelecionarPostagens();
                    Usuario::Login();  
                    
                    
                    $loader = new \Twig\Loader\FilesystemLoader('app/View');
                    $twig = new \Twig\Environment($loader);
                    $template = $twig->load('Feed.html');
                
            
                    $nomeUsuario = isset($_SESSION['nomeUsuario']) ? $_SESSION['nomeUsuario'] : null;
                    $idLogin = isset($_SESSION['idLogin']) ? $_SESSION['idLogin'] : null;
                    $idCanilUser = isset($_SESSION['idCanilUser']) ? $_SESSION['idCanilUser'] : null;
                    
    
                    $imgProfile = Usuario::MostrarImage($idLogin);
                    $imgPathProfile = ($imgProfile !== null && isset($imgProfile['imgpath'])) ?$imgProfile['imgpath'] : null;
                
                    $imgProfileCanil = Canil::MostrarImageCanil($idCanilUser);
                    $imgPathCanilProfile = ($imgProfileCanil !== null && isset($imgProfileCanil['imgpath'])) ? $imgProfileCanil['imgpath'] : null;
                    
                
                    $parametros = array();
                    $parametros['nomeUsuario'] = $nomeUsuario;
                    $parametros['idCanilUser'] = $idCanilUser;
                    $parametros['idLogin'] = $idLogin;
            
    
                    
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
<?php

class NovaPostagemController
{
    public function index()
    {
        Usuario::Login();  
        Auth::AuthUsuario();
        
        $loader = new \Twig\Loader\FilesystemLoader('app/View');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('postagem.html');
		
        $nomeUsuario = isset($_SESSION['nomeUsuario']) ? $_SESSION['nomeUsuario'] : null;
        $idLogin = isset($_SESSION['idLogin']) ? $_SESSION['idLogin'] : null;
    
        

        $imgProfile = Usuario::MostrarImage($idLogin);
        $imgPathProfile = ($imgProfile !== null && isset($imgProfile['imgpath'])) ?$imgProfile['imgpath'] : null;
    

    
        $parametros = array();
        $parametros['nomeUsuario'] = $nomeUsuario;
    
        $parametros['idLogin'] = $idLogin;


        
            $parametros['imgPath']= $imgPathProfile;
				

        $conteudo = $template->render($parametros);
        echo $conteudo;
    }
    public function insertPostagem()
    {   
        Usuario::Login();
        
        Auth::AuthUsuario();
        $idLogin = isset($_SESSION['idLogin']) ? $_SESSION['idLogin'] : null;
        Postagem:: InsertPostagem($idLogin);
        header("Location: http://amigos4patas.com/?pagina=feed");
    }
   
}

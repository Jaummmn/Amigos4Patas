<?php

class EditPostagemController
{
        public function index($idPostagem)
        {
            Usuario::Login();  
            Auth::AuthUsuario();
            
            $postagemSingle = Postagem::SelecPostagem($idPostagem);
            $loader = new \Twig\Loader\FilesystemLoader('app/View');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('EditPostagem.html');
            
            $nomeUsuario = isset($_SESSION['nomeUsuario']) ? $_SESSION['nomeUsuario'] : null;
            $idLogin = isset($_SESSION['idLogin']) ? $_SESSION['idLogin'] : null;
        
            
            $imgProfile = Usuario::MostrarImage($idLogin);
            $imgPathProfile = ($imgProfile !== null && isset($imgProfile['imgpath'])) ?$imgProfile['imgpath'] : null;
        
            $imgPostagem = Postagem::MostrarImagePostagem($idPostagem);
            $imgPathostagemtSingle = ( $imgPostagem !== null && isset( $imgPostagem['imgpath'])) ?  $imgPostagem['imgpath'] : null;
            $parametros= array();
        
            $parametros = array(
                'tituloPostagem'=>$postagemSingle['tituloPostagem'],
                'textoPostagem'=>$postagemSingle['textoPostagem'],
                'imgPathostagemtSingle'=> $imgPathostagemtSingle,
                'idPostagem'=>$postagemSingle['idPostagem']
            );
            $parametros['nomeUsuario'] = $nomeUsuario;
        
            $parametros['idLogin'] = $idLogin;
    
    
            
                $parametros['imgPath']= $imgPathProfile;
                    
    
            $conteudo = $template->render($parametros);
            echo $conteudo;
        }
        public function UpdatePostagem($idPostagem)
        {   
            Usuario::Login();
            
          
            Auth::AuthUsuario();
        
            Postagem:: UpdatePostagem($idPostagem);
            
            
        }
        public function ExcluirPostagem($idPostagem)
        {   
            Usuario::Login();
            Auth::AuthUsuario();
            $idLogin = isset($_SESSION['idLogin']) ? $_SESSION['idLogin'] : null;
            Postagem::excluirPorId($idPostagem);
            
        
            echo  '<script>
                        window.location.href = "https://amigos4patas.com/?pagina=User&idUser='.$idLogin.'";
                  </script>';
        }

       
    }
    

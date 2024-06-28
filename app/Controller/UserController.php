<?php
class UserController
{
    public function index($idUserPage)
    {
        try 
        {	
            $colecPostagens = Postagem::selecionaPostagemUsuario($idUserPage);
            $usuarioPage = Usuario::selecionaPorId($idUserPage);
            Usuario::Login();  
         
            
            $loader = new \Twig\Loader\FilesystemLoader('app/View');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('User.html');
            
            $nomeUsuario = isset($_SESSION['nomeUsuario']) ? $_SESSION['nomeUsuario'] : null;
            $idLogin = isset($_SESSION['idLogin']) ? $_SESSION['idLogin'] : null;
            $idCanilUser = isset($_SESSION['idCanilUser']) ? $_SESSION['idCanilUser'] : null;
            
            $imgProfile = Usuario::MostrarImage($idLogin);
            $imgPathProfile = ($imgProfile !== null && isset($imgProfile['imgpath'])) ? $imgProfile['imgpath'] : null;

            $imgProfileCanil = Canil::MostrarImageCanil($idCanilUser);
            $imgPathCanilProfile = ($imgProfileCanil !== null && isset($imgProfileCanil['imgpath'])) ? $imgProfileCanil['imgpath'] : null;

            $imgProfilePage = Usuario::MostrarImage($idUserPage);
            $imgPathProfilePage = ($imgProfilePage !== null && isset($imgProfilePage['imgpath'])) ? $imgProfilePage['imgpath'] : null;
            
            $parametros = array(
                'idUserPage'=>$usuarioPage['idLogin'],
                'idLogin' => $idLogin,
                'imgPathProfilePage' => $imgPathProfilePage,
                'nomeUsuarioPage' => $usuarioPage['nomeUsuario'],
                'nomeUsuario' => $nomeUsuario,
                'idCanilUser' => $idCanilUser,
                'idLogin' => $idLogin,
                'imgPath' => isset($idCanilUser) ? $imgPathCanilProfile : $imgPathProfile
            );

            foreach ($colecPostagens as $postagem) {
                $imgPostagem = Postagem::MostrarImagePostagem($postagem['idPostagem']);
                $imgPathPostagem = ($imgPostagem !== null && isset($imgPostagem['imgpath'])) ? $imgPostagem['imgpath'] : null;

                $parametros['postagens'][] = [
                    'tituloPostagem' => $postagem['tituloPostagem'],
                    'idPostagem'=>$postagem['idPostagem'],
                    'textoPostagem' => $postagem['textoPostagem'],
                    'imgPathPostagem' => $imgPathPostagem
                ];
            }

            $conteudo = $template->render($parametros);
            echo $conteudo;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

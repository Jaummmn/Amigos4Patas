<?php
 class Core
 {
        public function start($urlGet)
        {
            if (isset($urlGet['metodo'])) {
                $acao = $urlGet['metodo'];
            } 
            else
            {
                $acao = 'index';
            }
           
            if (isset($urlGet['pagina'])) 
            {
                $controller = ucfirst($urlGet['pagina'].'Controller');
            } else {
                $controller = 'HomeController';
            }
    
            if (!class_exists($controller)) 
            {
                $controller = 'ErroController';
            }
            if (isset($urlGet['idpet']) && $urlGet['idpet'] !== null) {
                $id = $urlGet['idpet'];
            } elseif (isset($urlGet['idCanil']) && $urlGet['idCanil'] !== null) {
                $id = $urlGet['idCanil'];
            } elseif (isset($urlGet['idUser']) && $urlGet['idUser'] !== null) {
                $id = $urlGet['idUser'];
            } elseif (isset($urlGet['idPostagem']) && $urlGet['idPostagem'] !== null) {
                $id = $urlGet['idPostagem'];
            } else {
                $id = null;
            }
            
        
            call_user_func_array(array(new $controller,$acao), array($id));



    }
 }
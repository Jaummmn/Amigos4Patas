<?php

class ValidaContaController
{
    public function index()
    {
        try 
        {   
            Usuario::TokenValidation();
          
            header("Location: https://amigos4patas.com/?pagina=home");
          
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        
    }
}
?>

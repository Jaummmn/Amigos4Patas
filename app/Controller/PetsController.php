<?php

class PetsController
{
    private function initializeParameters()
    {
        return [
            'cores' => Canil::selecionarCor(),
            'porte' => Canil::selecionarPorte(),
            'raca' => Canil::selecionarRaca(),
            'nomePets' => [],
            'idPet' => [],
            'imgPathPet' => [],
            'Nome_pet'=>[],
            'especie_pet'=>[],
            'sexo'=>[],
            'porte_pet'=>[],
            'cor_pet'=>[],
            'castrado'=>[],
            'raca_pet'=>[],
            'nomeUsuario' => isset($_SESSION['nomeUsuario']) ? $_SESSION['nomeUsuario'] : null,
            'idLogin' => isset($_SESSION['idLogin']) ? $_SESSION['idLogin'] : null,
            'idCanilUser' => isset($_SESSION['idCanilUser']) ? $_SESSION['idCanilUser'] : null,
        ];
    }

    private function loadTemplate()
    {
        $loader = new \Twig\Loader\FilesystemLoader('app/View');
        $twig = new \Twig\Environment($loader);
        return $twig->load('Pets.html');
    }

    private function processPetsFiltro($colecPostagens, &$parametros)
    {
        // Verifique se $colecPostagens não é nulo antes de prosseguir
        if ($colecPostagens !== null && isset($colecPostagens['pets'])) {
            foreach ($colecPostagens['pets'] as $pet) {
                $parametros['nomePets'][] = $pet['nomePet'];
                $parametros['idPet'][] = $pet['idPet'];
                $imgPet = Pets::MostrarImage($pet['idPet']);
                $imgPathPet = ($imgPet !== null && isset($imgPet['imgpath'])) ? $imgPet['imgpath'] : null;
                $parametros['imgPathPet'][] = $imgPathPet;
            }
        }
        
    }
    private function processPets($colecPostagens, &$parametros)
    {
       
       
            foreach ($colecPostagens as $pet) {
                $parametros['nomePets'][] = $pet['nomePet'];
                $parametros['idPet'][] = $pet['idPet'];
                $imgPet = Pets::MostrarImage($pet['idPet']);
                $imgPathPet = ($imgPet !== null && isset($imgPet['imgpath'])) ? $imgPet['imgpath'] : null;
                $parametros['imgPathPet'][] = $imgPathPet;
            }
        
    }

    private function processUserImages(&$parametros)
    {
        $imgProfile = Usuario::MostrarImage($parametros['idLogin']);
        $imgPathProfile = ($imgProfile !== null && isset($imgProfile['imgpath'])) ? $imgProfile['imgpath'] : null;

        $imgProfileCanil = Canil::MostrarImageCanil($parametros['idCanilUser']);
        $imgPathCanilProfile = ($imgProfileCanil !== null && isset($imgProfileCanil['imgpath'])) ? $imgProfileCanil['imgpath'] : null;

        $parametros['imgPath'] = isset($parametros['idCanilUser']) ? $imgPathCanilProfile : $imgPathProfile;
    }

    private function renderTemplate($parametros)
    {
        $template = $this->loadTemplate();
        echo $template->render($parametros);
    }

    public function index()
    {
        try {
            Usuario::Login();
            $colecPostagens = Pets::selecionaRecentes();

            $parametros = $this->initializeParameters();
            $this->processPets($colecPostagens, $parametros);
            $this->processUserImages($parametros);

            $this->renderTemplate($parametros);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function filtrarPets()
    {
        try {
            Usuario::Login();
            $colecPostagens = Pets::filtrarPets();
    
            $parametros = $this->initializeParameters();
            $parametros['pets'] = $colecPostagens;
    
            $this->processPetsFiltro($colecPostagens, $parametros);
            $this->processUserImages($parametros);
    
            if (isset($colecPostagens['params'])) {
                $params = $colecPostagens['params'];
                $parametros['nome_pet_filtro'] = str_replace('%', '', $params[':nomePet'] ?? '');
                $parametros['sexo_pet_filtro'] = $params[':sexo'] ?? '';
                $parametros['especie_pet_filtro'] = $params[':especie'] ?? '';
                $parametros['porte_pet_filtro'] = $params[':porte'] ?? '';
                $parametros['cor_pet_filtro'] = $params[':cor'] ?? '';
                $parametros['castrado_pet_filtro'] = $params[':castrado'] ?? '';
                $parametros['raca_pet_filtro'] = $params[':raca'] ?? '';
            }
    
            $this->renderTemplate($parametros);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    

    public function FiltroSexo()
    {
        try {
            Usuario::Login();
            $colecPostagens = Pets::FiltroSexo();

            $parametros = $this->initializeParameters();
            $parametros['pets'] = $colecPostagens;
            $this->processPets($colecPostagens, $parametros);
            $this->processUserImages($parametros);
       
            $parametros['sexo_pet_filtro']='Macho';

            $this->renderTemplate($parametros);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function filtrarCaramelo()
    {
        try {
            Usuario::Login();
            $colecPostagens = Pets::FiltrarCaramelo();

            $parametros = $this->initializeParameters();
            $parametros['pets'] = $colecPostagens;
            $this->processPets($colecPostagens, $parametros);
            $this->processUserImages($parametros);

            $parametros['cor_pet_filtro']= '4' ;

            $this->renderTemplate($parametros);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function FiltrarPorte()
    {
        try {
            Usuario::Login();
            $colecPostagens = Pets::FiltrarPorte();

            $parametros = $this->initializeParameters();
            $parametros['pets'] = $colecPostagens;
            $this->processPets($colecPostagens, $parametros);
            $this->processUserImages($parametros);
            $parametros['porte_pet_filtro']= 'Medio' ;
            $this->renderTemplate($parametros);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function FiltrarCastrado()
    {
        try {
            Usuario::Login();
            $colecPostagens = Pets::FiltrarCastrado();

            $parametros = $this->initializeParameters();
            $parametros['pets'] = $colecPostagens;
            $this->processPets($colecPostagens, $parametros);
            $this->processUserImages($parametros);

            $parametros['castrado_pet_filtro']= '1' ;
         
            
            $this->renderTemplate($parametros);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function FiltroFemea()
    {
        try {
            Usuario::Login();
            $colecPostagens = Pets::FiltroFemea();

            $parametros = $this->initializeParameters();
            $parametros['pets'] = $colecPostagens;
            $this->processPets($colecPostagens, $parametros);
            $this->processUserImages($parametros);

            $parametros['sexo_pet_filtro']='Femea';

            $this->renderTemplate($parametros);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

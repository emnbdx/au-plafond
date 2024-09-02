<?php
	if(!session_id()) @session_start();

    // composer autoloader
    require 'vendor/autoload.php';

	// project autoloader
    spl_autoload_register(function ($className) {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        if (file_exists($fileName)){
            require $fileName;
        }
    });

	$router = new AltoRouter();

	$repository = new Repositories\Repository();
	$contactController = new Controllers\ContactController();
	$staticController = new Controllers\StaticController($repository);

	$adminRepository = new Repositories\AdminRepository();
	$fileUploader = new Repositories\FileUploader();
	$adminController = new Controllers\AdminController($adminRepository, $fileUploader);

	// front route
	$router->map('GET', '', 'StaticController#index');
    $router->map('GET', '/', 'StaticController#index');
    $router->map('GET', '/librairie', 'StaticController#librairie');
    $router->map('GET', '/atelier', 'StaticController#atelier');
    $router->map('GET', '/qui-sommes-nous', 'StaticController#whoweare');
    $router->map('GET', '/contact', 'ContactController#index');
    $router->map('POST', '/contact', 'ContactController#send');

	// bo route
	$router->map('GET', '/admin/login', 'AdminController#login');
	$router->map('POST', '/admin/login', 'AdminController#login');
	$router->map('GET', '/admin/logout', 'AdminController#logout');
	$router->map('GET', '/admin', 'AdminController#index');

	$router->map('GET', '/admin/documents', 'AdminController#documentList');
	$router->map('GET', '/admin/document/add', 'AdminController#documentForm');
	$router->map('POST', '/admin/document/add', 'AdminController#addDocument');
	$router->map('GET', '/admin/document/[i:id]/delete', 'AdminController#deleteDocument');

	$router->map('GET', '/admin/pages', 'AdminController#pageList');
	$router->map('GET', '/admin/page/[i:id]/update', 'AdminController#pageForm');
	$router->map('POST', '/admin/page/[i:id]/update', 'AdminController#updatePage');

	$match = $router->match();
    
    if(is_array($match)) {

		list($controller, $action, $auth) = array_pad(explode('#', $match['target']), 3, null);
		$ctrl = NULL;
        $bo = false;
		if($controller == "StaticController") {
			$ctrl = $staticController;
		} else if ($controller == "ContactController") {
			$ctrl = $contactController;
		} else if ($controller == "AdminController") {
            $bo = true;
			$ctrl = $adminController;
		}

        list($type, $view, $data) = $ctrl->{$action}($match['params']);

        if(!$bo) {
            if($type == 'json') {
                header('Content-Type: application/json');
                echo $data;
            } else {
                $content = __DIR__ . '/Views/' . $view . '.phtml';
                $title = getTitle($view ?? $action);
                require __DIR__ . "/Views/shared/layout.phtml";
            }
        } else {
            $logged = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
            if(!$logged && $view != 'login') {
                header('location: /admin/login');
                exit;
            }

            if($logged && $view == 'login') {
                header('location: /admin');
                exit;
            }

            $content = __DIR__ . '/Views/bo/' . $view . '.phtml';
            require __DIR__ . "/Views/shared/layout-bo.phtml";

        }
    }
 
	function getTitle($action) 
    {
        $title = "Au plafond | ";
        switch ($action)
        {
            case 'index' :
                $title .= "Accueil : la librairie";
                break;
            case 'librairie' :
                $title .= "Librairie";
                break;
            case 'atelier' :
                $title .= "Atelier";
                break;
            case 'qui-sommes-nous' :
                $title .= "Qui sommes-nous ?";
                break;
            case 'contact' :
                $title .= "Contact";
                break;
            default :
                $title .= "404";
                break;
        }

     	return $title;
    }

?>
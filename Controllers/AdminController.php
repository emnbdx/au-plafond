<?php

namespace Controllers {

    use \Config;

    class AdminController
    {
        private $repository;
        private $fileUploader;

        public function __construct($repository, $fileUploader)
        {
            $this->repository = $repository;
            $this->fileUploader = $fileUploader;
        }

        public function index()
        {
            return ['view', 'index', null];
        }

        public function login()
        {
            $data = '';
            if (isset($_REQUEST['submit']) && $_REQUEST['submit'] != "") {
                extract($_REQUEST);
                
                if(Config::getInstance()->ADMIN_USER == $login && Config::getInstance()->ADMIN_PASSWORD == $password) {
                    $_SESSION["loggedin"] = true;
                    header('location: /admin');
                    exit();
                } else {
                    $_SESSION['error'] = 'Erreur de connexion';
                    header('location: /admin/login');
                    exit();
                }
            }
            
            return ['view', 'login', $data];
        }

        public function logout()
        {
            unset($_SESSION["loggedin"]);
            header('location: /admin/login');
            exit();
        }

        public function documentList()
        {
            $data = $this->repository->getAllRecords(Config::getInstance()->DBPREFIX . 'document', '*', '', 'ORDER BY id DESC');
            return ['view', 'document/index', $data];
        }

        public function documentForm()
        {
            return ['view', 'document/add', null];
        }

        public function addDocument()
        {
            if (isset($_REQUEST['submit']) && $_REQUEST['submit'] != "") {
                extract($_REQUEST);
                if ($_FILES["document"]["name"] == "") {
                    $_SESSION['error'] = 'Merci de fournir un nom';
                    header('location: /admin/documents');
                    exit;
                }

                if ($_FILES["document"]["name"] != "") {
                    $uploadError = $this->fileUploader->upload($_FILES["document"]);
                    if ($uploadError !== "") {
                        $_SESSION['error'] = $uploadError;
                        header('location: /admin/documents');
                        exit;
                    }
                }

                $data = array(
                    'name' => $_FILES["document"]["name"]
                );

                $this->repository->insert(Config::getInstance()->DBPREFIX . 'document', $data);

                $_SESSION['success'] = 'Document ajouté';
                header('location: /admin/documents');
                exit;
            }
        }

        public function deleteDocument($params)
        {
            if (isset($params['id']) && $params['id'] != "") {
                $document = $this->repository->getAllRecords(Config::getInstance()->DBPREFIX . 'document', '*', ' AND id="' . $params['id'] . '"')[0];
                unlink(Config::getInstance()->UPLOADFOLDER . $document["name"]);

                $this->repository->delete(Config::getInstance()->DBPREFIX . 'document', array('id' => $params['id']));
                $_SESSION['success'] = 'Document supprimé';
                header('location: /admin/documents');
                exit;
            }
        }

        public function pageList()
        {
            $data = $this->repository->getAllRecords(Config::getInstance()->DBPREFIX . 'page', '*', '', 'ORDER BY id DESC');
            return ['view', 'page/index', $data];
        }

        public function pageForm($params)
        {
            $data = $this->repository->getAllRecords(Config::getInstance()->DBPREFIX . 'page', '*', ' AND id="'.$params['id'].'"')[0];

            return ['view', 'page/update', $data];
        }

        public function updatePage()
        {
            if (isset($_REQUEST['submit']) && $_REQUEST['submit'] != "") {
                extract($_REQUEST);
                if ($content == "")
                {
                    $_SESSION['error'] = 'Le contenu ne peut pas être vide';
                    header('location: /admin/page/' . $editId . '/update');
                    exit;
                }

                $data = array(
                    'content'=>$content
                );
                
                $this->repository->update(Config::getInstance()->DBPREFIX . 'page', $data, array('id' => $editId));

                $_SESSION['success'] = 'Page mise à jour';
                header('location: /admin/pages');
                exit;
            }
        }
    }
}

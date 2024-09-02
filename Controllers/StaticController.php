<?php

namespace Controllers
{   
    class StaticController
    {
        private $repository;

        public function __construct($repository)
        {
            $this->repository = $repository;
        }

        public function index()
        {
            $data = $this->repository->getPageContent("home");
            return ['raw', null, $data];
        }

        public function librairie()
        {
            $data = $this->repository->getPageContent("librairie");
            return ['raw', null, $data];
        }

        public function atelier()
        {
            $data = $this->repository->getPageContent("atelier");
            return ['raw', null, $data];
        }

        public function whoweare()
        {
            $data = $this->repository->getPageContent("qui-sommes-nous");
            return ['raw', null, $data];
        }
    }
}

?>
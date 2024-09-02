<?php
    class Config {
        private static $_instance = null;

        // DB
        public $DBURL;
        public $DBNAME;
        public $DBUSER;
        public $DBPASSWORD;
        public $DBPREFIX;
    
        // FILE
        public $UPLOADFOLDER;
        public $UPLOADMAXSIZE;

        // MAIL
        public $MAIL_HOST;
        public $MAIL_PORT;
        public $MAIL_LOGIN;
        public $MAIL_PASSWORD;

        // ADMIN
        public $ADMIN_USER;
        public $ADMIN_PASSWORD;
        
        private function __construct()
        {
            date_default_timezone_set('Europe/Paris');
        
            $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
            $dotenv->safeLoad();

            $this->DBURL = $_ENV['DATABASE_HOST'];
            $this->DBNAME = $_ENV['DATABASE_NAME'];
            $this->DBUSER = $_ENV['DATABASE_USERNAME'];
            $this->DBPASSWORD = $_ENV['DATABASE_PASSWORD'];
            $this->DBPREFIX = $_ENV['DATABASE_PREFIX'];
            
            $this->UPLOADFOLDER = __DIR__ . "/images/uploads/";
            $this->UPLOADMAXSIZE = 10000000;
            
            $this->MAIL_HOST = "ssl0.ovh.net";
            $this->MAIL_PORT = 465;
            $this->MAIL_LOGIN = "librairie@au-plfond.fr";
            $this->MAIL_PASSWORD = $_ENV['MAIL_PASSWORD'];
            
            $this->ADMIN_USER = $_ENV['ADMIN_USER'];
            $this->ADMIN_PASSWORD = $_ENV['ADMIN_PASSWORD'];
        }

        public static function getInstance()
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new Config();
            }
    
            return self::$_instance;
        }
    }
?>

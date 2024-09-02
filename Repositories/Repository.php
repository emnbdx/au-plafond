<?php

namespace Repositories
{
    use \PDO;
    use \Config;
    use Models\CreationModel;
    use Models\TechniqueModel;
    use Models\ThemeModel;

    class Repository
    {        
        private $connection;
        
        public function __construct()
        {
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_SSL_CA => '',
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            );
            
            $this->connection = new PDO(
                'mysql:host=' . Config::getInstance()->DBURL . ';dbname=' . Config::getInstance()->DBNAME . ';charset=utf8mb4',
                Config::getInstance()->DBUSER,
                Config::getInstance()->DBPASSWORD,
                $options
            );
        }

        public function getPageContent($name)
        {
            $sql = 'SELECT content FROM ' . Config::getInstance()->DBPREFIX . 'page where name = ?';
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$name]);

            return $stmt->fetch()["content"];
        }
    }
}

?>
<?php

namespace Repositories
{
    use \Config;

    class FileUploader
    {    
        public function __construct()
        { }

        public function upload($file)
        {
            $target_file = Config::getInstance()->UPLOADFOLDER . basename($file["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $uploadOk = 1;
            
            // Check if file already exists
            if (file_exists($target_file))
            {
                return "Ce fichier existe déjà";
                $uploadOk = 0;
            }

            // Check file size
            if ($file["size"] > Config::getInstance()->UPLOADMAXSIZE)
            {
                return "Ce fichier est trop volumineux";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0)
            {
                return "Erreur lors du chargement du fichier";
            
            }
            else // if everything is ok, try to upload file
            {
                if (move_uploaded_file($file["tmp_name"], $target_file))
                {
                    return "";
                }
                else
                {
                    return "Erreur lors du chargement du fichier";
                }
            }
        }    
    }
}
    
?>
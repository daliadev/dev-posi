<?php

class ImageUploader
{

    static function create($image, $path, $name, $ext, $delete = false, $width = 100, $height = 100)
    {
        // On supprime l'extension du nom
        //$name = substr($name, 0, -4);
        //var_dump($name);
        //var_dump($ext);
        //exit();

        // On créé une image à partir du fichier récup et selon le format choisi
        if ($ext == "jpg")
        {
            $imageCreated = imagecreatefromjpeg($image); 
        }
        else if ($ext == "png")
        {
            $imageCreated = imagecreatefrompng($image); 
        }
        else if ($ext == "gif")
        {
            $imageCreated = imagecreatefromgif($image); 
        }
        else 
        {
            return false;    
        }
        
        // On récupère les dimensions de l'image
        $dimension = getimagesize($image);
        $imageWidth = $dimension[0];
        $imageHeight = $dimension[1];
        
        // Suppression de la photo originale
        if ($delete)
        {
            unlink($image);
        }
        
        /* Création des miniatures */
        // On créé une image vide de la largeur et hauteur voulue
        $imageFinale = imagecreatetruecolor($width, $height);

        // On va gérer la position et le redimensionnement de la grande image
        $thumbDimRatio = $width / $height;

        if ($imageWidth > $thumbDimRatio * $imageHeight)
        {
            // L'image est plus large que le format demansé
            $finalWidth = $height * $imageWidth / $imageHeight; 
            $finalHeight = $height; 
            $offsetY = 0;
            $offsetX = -($finalWidth - $width) / 2;
        }
        if ($imageWidth < $thumbDimRatio * $imageHeight)
        { 
            $finalWidth = $width; 
            $finalHeight = $width * $imageHeight / $imageWidth;   
            $offsetX = 0;
            $offsetY = -($finalHeight - $height) / 2; 
        }
        if ($imageWidth == $thumbDimRatio * $imageHeight)
        { 
            $finalWidth = $width; 
            $finalHeight = $height; 
            $offsetX = 0;
            $offsetY = 0; 
        }

        // on modifie l'image de base par l'image finale redimensionnée et décalée
        imagecopyresampled($imageFinale, $imageCreated, $offsetX, $offsetY, 0, 0, $finalWidth, $finalHeight, $imageWidth, $imageHeight);

        // On sauvegarde l'image finale
        //imagejpeg($imageFinale, $path.$name.".".$ext, 100);
        if ($ext == "jpg")
        {
            imagejpeg($imageFinale, $path.$name.".".$ext, 100);
        }
        else if ($ext == "png")
        {
            imagepng($imageFinale, $path.$name.".".$ext, 0);
        }
        else if ($ext == "gif")
        {
            imagegif($imageFinale, $path.$name.".".$ext);
        }

        // On libère la mémoire
        imagedestroy($imageFinale);

        return true;
    }
    
    
    
    
    static function convert($source, $format = "jpg")
    {
        // Création de l'image selon le format de l'mage reçue
        if (substr(strtolower($source), -4) == ".jpg")
        {
            $image = imagecreatefromjpeg($source); 
        }
        else if (substr(strtolower($source), -4) == ".png")
        {
            $image = imagecreatefrompng($source); 
        }
        else if (substr(strtolower($source), -4) == ".gif")
        {
            $image = imagecreatefromgif($source); 
        }
        else 
        {
            // L'image ne peut etre redimensionnée
            return false;    
        }
        
        // Suppression de la miniature
        unlink($source);
        
        // On enregistre l'image
        imagejpeg($image, substr($source, 0, -3)."jpg", 100);
        
        // On libère la mémoire
        imagedestroy($image);
        
        return true;
    }
    
}

?>

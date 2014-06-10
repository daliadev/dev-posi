<?php

/**
 * Description of tools
 *
 * @author Nicolas Beurion
 */

class Tools {
    
    
    
    
    static function toggleDate($date, $type = "fr")
    {
        if ($type == "us")
        {
            // strreplace() pour mettre une barre oblique à la place d'un autre caractére (comme -);
            $date = str_replace("-", "/", $date);
            $tabDate = explode("/", $date);
            $day = $tabDate[0];
            $month = $tabDate[1];
            $year = $tabDate[2];

            $toggleDate = $year."-".$month."-".$day;
        }
        else if ($type == "fr")
        {
            $date = str_replace("/", "-", $date);
            $tabDate = explode("-", $date);
            $year = $tabDate[0];
            $month = $tabDate[1];
            $day = $tabDate[2];

            $toggleDate = $day."/".$month."/".$year;
        }

        return  $toggleDate;
    }
    
    
    
    
    
    static function timeToString($time, $outputFormat = "full")
    {

        $hours = floor($time / 3600);
        $time -= $hours * 3600;
        
        $minutes = floor($time / 60);
        $time -= $minutes * 60;
        
        $seconds = round($time);
        
        
        $timeString = "";
        
        switch ($outputFormat) 
        {
            case "full":
                
                if ($hours > 0)
                {
                    $timeString .= $hours." h ";
                }
                if ($minutes > 0)
                {
                    $timeString .= $minutes." min ";
                }

                $timeString .= $seconds." s";

                break;
                
            case "h:m:s":
            
                if ($hours > 0)
                {
                    if ($hours < 10)
                    {
                        $timeString .= "0".$hours;
                    }
                    else
                    {
                        $timeString .= $hours;
                    }
                }
                else
                {
                    $timeString .= "00";
                }
                
                $timeString .= ":";
                
                if ($minutes > 0)
                {
                    if ($minutes < 10)
                    {
                        $timeString .= "0".$minutes;
                    }
                    else
                    {
                        $timeString .= $minutes;
                    }
                }
                else
                {
                    $timeString .= "00";
                }
                
                $timeString .= ":";
                
                if ($seconds > 0)
                {
                    if ($seconds < 10)
                    {
                        $timeString .= "0".$seconds;
                    }
                    else
                    {
                        $timeString .= $seconds;
                    }
                }
                else
                {
                    $timeString .= "00";
                }
            
                break;
            
            case "h:m":
                
                if ($hours > 0)
                {
                    if ($hours < 10)
                    {
                        $timeString .= "0".$hours;
                    }
                    else
                    {
                        $timeString .= $hours;
                    }
                }
                else
                {
                    $timeString .= "00";
                }
                
                $timeString .= ":";
                
                if ($minutes > 0)
                {
                    if ($minutes < 10)
                    {
                        $timeString .= "0".$minutes;
                    }
                    else
                    {
                        $timeString .= $minutes;
                    }
                }
                else
                {
                    $timeString .= "00";
                }
            
                break;
            
            default:
                break;
        }
        
        return $timeString; 
    }
    
    
    
    
    
    static function timeToSeconds($time, $inputFormat = "h:m:s")
    {
        $hours = 0;
        $minutes = 0;
        $seconds = 0;
        
        if ($inputFormat == "h:m:s")
        {
            $hours = intval(substr($time, 0, 2));
            $minutes = intval(substr($time, 3, 2));
            $seconds = intval(substr($time, 6, 2));
        }
        else if ($inputFormat == "h:m")
        {
            $hours = intval(substr($time, 0, 2));
            $minutes = intval(substr($time, 3, 2));
            $seconds = 0;
        }
        else 
        {
            return false;
        }

        $hours *= 3600;
        $minutes *= 60;
        
        $totalSecondes = $hours + $minutes + $seconds;
        
        return $totalSecondes;  
    }
    
    
    


    static function stripSpecialCharsFromString($string)
    {
        $specialChars = "àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ";
        $cleanedChars = "aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY";

        $string = utf8_decode($string);    
        $string = strtr($string, utf8_decode($specialChars), $cleanedChars);

        return utf8_encode($string);
    }




}


?>

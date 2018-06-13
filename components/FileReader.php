<?php
namespace components;

use nikcherr\parser\StringParser;

class FileReader {
    
    protected static function getFile($filePath){
        if(file_exists($filePath)){
            return file_get_contents($filePath);
        }
        return null;
    }
    
    public static function run($filePath): string{
        $content = self::getFile($filePath);
        $result = "";
        
        if($content){
            $parser = new StringParser();
            try{
                $result = $parser->roundBracket($content);
            } 
            catch (\InvalidArgumentException $e) {
                return $e->getMessage();
            }
            if ($result) {
                return "Correct\n";
            } 
            else {
                return "Non correct\n";
            }
        }
        return "Ошибка при открытии файла\n";
    }
}

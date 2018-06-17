<?php

namespace bootstrap;

class Command {

    public static function getOptsByName(...$args){
        $shortopts = '';
        $longopts = [];
        
        if(isset($args)){
            
            foreach ($args as $key => $value) {
                $longopts[] = $value . ":";
            }
            $options = getopt($shortopts, $longopts);
            return $options;
        } 
        return null;
    }
}




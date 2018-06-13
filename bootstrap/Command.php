<?php

namespace bootstrap;

use components\FileReader;

class Command {

    public static function run(){
        $shortopts = "";
        //$shortopts .= "f:";
        $longopts = array(
            "file:",
        );
        $options = getopt($shortopts, $longopts);

        if(isset($options["file"])){
            $result = FileReader::run($options["file"]);
            echo $result;
        }
        else echo "Задайте файл в качестве параметра\n";
    }
}




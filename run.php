#!/usr/bin/php
<?php
require_once 'vendor/autoload.php';

bootstrap\Command::run();


/*
$pid = pcntl_fork();
if(-1 == $pid){
    die('Can not fork process');
}
elseif($pid){
    print sprintf("Parent, created child: %s\n", $pid);
    sleep(1);
    pcntl_waitpid($pid, $status);
    $exitCode = pcntl_wexitstatus($status);
    print $exitCode;
}
else{ //$pid = 0
    print sprintf("This is child process, my pid: %s\n", getmypid());
    
    exit(123);
}
*/
/*
$pid = pcntl_fork();

if(0 == $pid){
    echo "child pid = $pid\n";
    sleep(3);
    echo "after child\n";
    exit(111);
}

if($pid > 0){
    echo "parent pid = $pid\n";
    sleep(200);
    pcntl_waitpid($pid, $status);
    $exitCode = pcntl_wexitstatus($status);
    echo "after parent exitChild = $exitCode\n";
}
/*
$fh = fopen("text.txt", "r");
var_dump(posix_getpid());
sleep(10);
fclose($fh);
sleep(200);
*/
/*
while($f = fgets(STDIN)){
    echo "line: $f";
}
*/
 
<?php
class C_Caching 
{     
    protected function write_content($text, $cache_file) {
        $f = fopen($cache_file, 'w');
        fwrite ($f, $text, strlen($text));
        fclose($f);
       }
    protected function read_content($cache_file) {
        $f = fopen($cache_file, 'r');
        $buffer = '';
        while(!feof($f)) {
        $buffer .= fread($f, 2048);
        }
        fclose($f);
        return $buffer;
    }
}
?>




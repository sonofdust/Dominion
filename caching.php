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

class C_SmartyStreets extends C_Caching{
    private $auth_id = '1232f705-7bd5-76d3-a0d3-6bcb004bce40';
    private $auth_token = 'mU8xyF2a5hD9cIt7Z4M6';
    private $file = "cache.text";
    private $url = "https://us-zipcode.api.smartystreets.com/lookup?"; 
    
     private function getSmartyZips($state,$city){
         $cmd = $this->url."auth-id=".$this->auth_id."&auth-token=".$this->auth_token."&city=".$city."&state=".$state; 

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'GET'
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($cmd, false, $context);
        $decode = json_decode($result, true);
        $temp = $decode[0]["zipcodes"];
        $zip_list = array();
        foreach($temp as $zip)
        {
            $zip_list[] = $zip["zipcode"];
        }
        return $zip_list;
     }
    
     private function writeStateCityZip($state,$city,$ptr){
        $zips =  $this->getSmartyZips($state,$city);
        $ptr[$state][$city] = array("TS"=>time(),"zipCodes"=>$zips);
        $this->write_content(json_encode($ptr), $this->file);
        return $zips;
    }

    private function isExpired($state,$city,$ptr){

    }

    public function getZipCodes($state,$city){
         if (file_exists($this->file)) {
             $a_state = json_decode($this->read_content($this->file),true); 
             if (array_key_exists($state, $a_state)){
                 if(array_key_exists($city, $a_state[$state])){
                    return ['This works!!!!'];
                 }
                 else{
                    return $this->writeStateCityZip($state,$city,$a_state);
                }
             }
             else{
                $a_state[$state] = array();
                return $this->writeStateCityZip($state,$city,$a_state);
             }
         } 
         else {
                $a_state = array();
                $a_state[$state] = array();
                return $this->writeStateCityZip($state,$city,$a_state);
         }
    }

    
}

$Object = new C_SmartyStreets();




   

    


?>




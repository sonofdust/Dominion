<?php
include "caching.php";
class C_SmartyStreets extends C_Caching{
    private $auth_id = '1232f705-7bd5-76d3-a0d3-6bcb004bce40';
    private $auth_token = 'mU8xyF2a5hD9cIt7Z4M6';
    private $file = "cache.text";
    private $url = "https://us-zipcode.api.smartystreets.com/lookup?"; 
//    private $time_limit = 3600/4; 
    private $time_limit = 3600*24; 
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
        if (array_key_exists("zipcodes", $decode[0])){
            $zip_list = array();
            foreach($decode[0]["zipcodes"] as $zip){
                $zip_list[] = $zip["zipcode"];}
            return $zip_list;
        }
        else{
            throw new Exception($city." city does not exist in ".$state);    
        }
     }
    
     private function setStateCityZip($state,$city,$ptr){
         try{
            $zips =  $this->getSmartyZips($state,$city);
            $ptr[$state][$city] = array("TS"=>time(),"zipCodes"=>$zips);
            if(count($zips)===0){
                $zips=[$city." does not exist."];
            }else{
                $this->write_content(json_encode($ptr), $this->file);
            }
            return $zips;
         }catch(Exception $e){
            return [$e->getMessage()];
         }
    }

    public function getZipCodes($state,$city){
        $zips = [];
         if (file_exists($this->file)) {
             $a_state = json_decode($this->read_content($this->file),true); 
             if (array_key_exists($state, $a_state)){
                 if(array_key_exists($city, $a_state[$state])){
                    $duration = time() - $a_state[$state][$city]["TS"];
                    $zips =  ($this->time_limit < $duration) ? $this->setStateCityZip($state,$city,$a_state) : $a_state[$state][$city]["zipCodes"];
                 }
                 else{
                    $zips =  $this->setStateCityZip($state,$city,$a_state);
                }
             }
             else{
                $a_state[$state] = array();
                $zips =  $this->setStateCityZip($state,$city,$a_state);
             }
         } 
         else {
                $a_state = array();
                $a_state[$state] = array();
                $zips = $this->setStateCityZip($state,$city,$a_state);
         }
         return $zips;
    }
}

$Object = new C_SmartyStreets();
?>

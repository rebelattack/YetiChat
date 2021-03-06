<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cmd
 *
 * @author charlie
 */
class Cmd {
    
    private $cpu_total;
    private $cpu_idle;
    private $net_tx;
    private $net_rx;
    private $net_time;
    
    public function __construct(){
        if(file_exists("engine/cache/cpu.cache")){
            $_fp = fopen("engine/cache/cpu.cache", "r");
            $line = fgets($_fp);
            fclose($_fp);            
            $data = explode(" ",$line);            
            $this->cpu_idle = $data[0];
            $this->cpu_total = $data[1];
	}
        
        if(file_exists("engine/cache/network.cache")){
            $_fp = fopen("engine/cache/network.cache", "r");
            $line = fgets($_fp);
            fclose($_fp);            
            $data = explode(" ",$line);            
            $this->net_rx = $data[0];
            $this->net_tx = $data[1];
            $this->net_time = $data[2];
	}
    }
    
    private function CPUparse(){
        exec("cat /proc/stat",$data);
        $data = $data[0];
        $parts = explode(" ",$data);
        
        $new_total = $parts[1]+$parts[2]+$parts[3]+$parts[4]+$parts[5]+$parts[6]+$parts[7]+$parts[8]+$parts[9]+$parts[10]+1;
        $new_idle = $parts[4];
        
        return array("idle"=>$new_idle,"total"=>$new_total);
    }
    
    public function CPUutilization(){
        
        $first = $this->CPUparse();
        sleep(1);
        $second = $this->CPUparse();
        
        $idleDelta = abs($first["idle"] - $second["idle"]);
        $totalDelta = abs($first["total"] - $second["total"]);
        $cpu_usage = (($idleDelta * 100.0) / $totalDelta + 0.5);
        
        return round($cpu_usage,2);
    }
    
   
    public function RAMutilization(){
        exec("free -tl", $data);
        $raw_ram_data = explode(" ",$data[1]);
        $ram_data = array();
        foreach ($raw_ram_data as $d){
            if(strlen($d) != 0){
                $ram_data[] = $d;
            }
        }
        $raw_swap_data = explode(" ",$data[5]);
        $swap_data = array();
        foreach ($raw_swap_data as $d){
            if(strlen($d) != 0){
                $swap_data[] = $d;
            }
        }
        $ram_usage = 100 * round(($ram_data[2] - $ram_data[4] - $ram_data[5] - $ram_data[6])/ ($ram_data[1]),3);
        $swap_usage = 100 * round($ram_data[3] / ($swap_data[1]),3);
        return $ram_usage.' '.$swap_usage;
    }
    
    private function bandwidthParse(){
         exec("cat /proc/net/dev", $data);
        $raw_data = explode(" ",$data[2]);
        $data = array();
        foreach ($raw_data as $d){
            if(strlen($d) != 0){
                $data[] = $d;
            }
        }
        
        $new_net_rx = $data[1];
        $new_net_tx = $data[9];        
        $new_net_time = microtime(true);    
        
        return array("tx"=>$new_net_tx,"rx"=>$new_net_rx,"time"=>$new_net_time);
    }
    
    public function bandwithUsage(){
           
        $first = $this->bandwidthParse();
        sleep(1);
        $second = $this->bandwidthParse();       

        $deltaT = $first["time"] - $second["time"];
        $deltaRx = $first["rx"] - $second["rx"];
        $deltaTx = $first["tx"] - $second["tx"];
        
        $speedRx = round($deltaRx / ($deltaT*1024),2);
        $speedTx = round($deltaTx / ($deltaT*1024),2);
        return $speedRx." ".$speedTx;
    }
}

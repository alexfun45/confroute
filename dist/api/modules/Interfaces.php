<?php
	class Interfaces{
		
		var $action = '';
		var $path = '';
		var $data = null;
		
		function __construct($actname, $path){
			$this->action = $actname;
			$this->path = __WRK . $path;	
		}
		
		public function action($data){
			$this->data = $data;
			//echo "dataa = ".json_encode($this->data);
			return call_user_func(array($this, $this->action));
		}		
		
		private function parseInterfaceConf($file){
			$fileContent = file($file, FILE_IGNORE_NEW_LINES);
			if($fileContent[0]=="disabled" || $fileContent[0]=="NULL" || $fileContent[0]=="")
				return $fileContent[0];
			for($i=0;$i<count($fileContent);$i++){
				$line = explode(";", $fileContent[$i]);
				$data[str_replace(" ", "", $line[0])] = $line[1];		
			}
			return $data;		
		}
		
		private function getIndicators(){
			$path = $this->path . DS . "status.lan";
			$data = file($this->path . DS . "status.lan");
			for($i=0;$i<count($data);$i++){
				$keyval = explode(" ", $data[$i]);
				$statuses[] = trim($keyval[1]);			
			}
			return $statuses;	
		}
		
		private function saveInterface(){
			$data = $this->data;
			$interfaceFile = $this->path . DS . $data->interfaceFile;
			$confFile = fopen($interfaceFile, "w");
			fputs($confFile, $data->interfacePort.";".$data->protocolType."\r\n");
			fputs($confFile, "Protocol;".$data->protocol."\r\n");
			fputs($confFile, "IPv4 address;".$data->ipv4addr."\r\n");
			fputs($confFile, "IPv4 netmask;".$data->ipvnetmask."\r\n");
			if($data->protocolType=="WAN"){
				fputs($confFile, "IPv4 gateway;".$data->ipv4gateway."\r\n");
				fputs($confFile, "Resolv;".$data->resolv."\r\n");
			}
			fputs($confFile, "Override MAC_address;".$data->overrideMacAddr."\r\n");
			fputs($confFile, "MTU;".$data->mtu."\r\n");
			fclose($confFile);	
			echo $data->mtu;
		}
		
		private function deleteInterface(){
			$data = $this->data;
			$interfaceFile = $this->path . DS . $data->iName;
			$confFile = fopen($interfaceFile, "w");
			fputs($confFile, "disabled");
			fclose($confFile);		
		}
		
		private function getInterfaces(){
			$iFiles = array();
			if ($handle = opendir($this->path)) {
			    while ($entry = readdir($handle)) {
			        if (strpos($entry, "..") !== 0 && strpos($entry, ".") !== 0 && $entry !== "status.lan") {
			            $iFiles[$entry] = $this->parseInterfaceConf($this->path.DS.$entry);
			            if($iFiles[$entry]=="disabled")
			            	unset($iFiles[$entry]);
			        }
			    }
			    closedir($handle);
			}
			ksort($iFiles);
			return $iFiles;
		}	
	}
?>
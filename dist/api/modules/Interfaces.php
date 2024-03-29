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

		protected function getShellReturn(){
			$shellReturned = file_get_contents(LOG."feedback.alert");
			if($shellReturned!="")
				unlink(LOG."feedback.alert");
			$shellReturned = explode(":", $shellReturned);
			return array("res"=>trim($shellReturned[0]), "message"=>trim($shellReturned[1]));
		}
		
		private function saveInterface(){
			$data = $this->data;
			$interfaceFile = $this->path . DS . $data->interfaceFile;
			$confFile = fopen($interfaceFile, "w");
			fputs($confFile,"Interface;".$data->interfacePort."\r\n");
			fputs($confFile,"ProtocolType;".$data->protocolType."\r\n");
			//fputs($confFile, $data->interfacePort.";".$data->protocolType."\r\n");
			fputs($confFile, "Protocol;".$data->protocol."\r\n");
			if($data->protocol!="dynamic"){
				fputs($confFile, "IPv4 address;".$data->ipv4addr."\r\n");
				fputs($confFile, "IPv4 netmask;".$data->ipvnetmask."\r\n");
				}
			//if($data->protocolType=="WAN"){
				//if($data->protocol!="dynamic")
				if(isset($data->ipv4gateway))
					fputs($confFile, "IPv4 gateway;".$data->ipv4gateway."\r\n");
				//if($data->protocol!="dynamic" || $data->static_resolv)
				if(isset($data->resolv)){
					if(($data->protocol!="dynamic" && $data->protocolType=="WAN") || $data->static_resolv)
						fputs($confFile, "Resolv;".$data->resolv."\r\n");
					else if($data->protocol=="dynamic" && $data->resolv!="")
						fputs($confFile, "Resolv;".$data->resolv."\r\n");
				}
			//}
			fputs($confFile, "Override MAC_address;".$data->overrideMacAddr."\r\n");
			fputs($confFile, "MTU;".$data->mtu."\r\n");
			if(isset($data->advancedFields)){
				$str = '';
				foreach($data->advancedFields as $prop=>$val){
					if($val->type!="text"){
						$p = array();
						$str = '';
						foreach($val as $option=>$oVal){
								$p[] = $option . ":" . $oVal;
						}
						$str = "[" . implode(",", $p) . "]";
						$str = $prop . ";" . $str;
					}
					else{
						$str = $prop . ";" . $val->value;
 					}
					fputs($confFile, $str."\r\n");
				}
			}
			fclose($confFile);
			if(!$data->isNew && $data->interfaceFile!=$data->changedInterface){
				$rewriteInterfaceFile = $interfaceFile = $this->path . DS . $data->changedInterface;
				file_put_contents($rewriteInterfaceFile, "disabled");
			}
			sleep(3);
			return $this->getShellReturn();
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
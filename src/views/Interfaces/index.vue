<template>
	<div class="InterfaceWrapper">
	  <div class="btn-top"><el-button class="el-button el-button--primary" type="button" @click="addBlock"><i class="el-icon-circle-plus"></i>Add</el-button>
		</div>	  
	  <div class='InterfaceContainer'>
	    <div v-for="(i, index) in interfaces">
			 <InterfaceBlock :key="i.name" :isNew="i.isNew" :indx="i.indx" :isCollapsed="(index>0)?true:false" v-bind:iData="i" v-bind:iName="i.name" v-bind:allInterfaces="getInterfaces()" v-if="isEnabled(i)">{{i.name}}</InterfaceBlock>   	    
	    </div>
	  </div>
  </div>
</template>

<script>
import InterfaceBlock from './components/InterfaceBlock'

export default {
  name: 'Page',
  data: function() {
    return {
      interfaces: [],
      inum:0,
      num: 0,
      maxInterfaces: 6,
      changeNum: 0
    }
  },
  created: function(){
		this.showInterfaces(); 
		this.$bus.$on('removeInterface', this.removeInterface);
		this.$bus.$on("changeInterface", this.changeInterface);
  },
  watch:{
	$route: "showInterfaces"  
  },
  methods:{
  		removeInterface(__interface){
  			//this.$http.post("./dist/api/api.php", JSON.stringify({ path: this.$router.currentRoute.fullPath, action: "deleteInterface", data: {iName: interfaceName}}));
			let req = { path: this.$router.currentRoute.fullPath, action: "deleteInterface", data: {iName: __interface.name}};	
			//console.log(__interface);		
			this.$request({method: 'post', data: req})
			//console.log(this.interfaces); 
			this.interfaces.splice(__interface.index-1, 1);
			//console.log(this.interfaces); 
			//delete this.interfaces[interfaceName]; 
			this.$forceUpdate();		
  		},
  		changeInterface(changeObj){
  				if(this.interfaces.hasOwnProperty(changeObj.newInterface) && changeObj.isNew===true)
  					return false;
  				else if(changeObj.isNew===true)
  					return true;
				let tmp = this.interfaces[changeObj.newInterface];
				this.interfaces[changeObj.newInterface] = this.interfaces[changeObj.oldInterface];
				this.interfaces[changeObj.oldInterface] = tmp;
				this.$forceUpdate();	
  		},
  		//getColorByProtocolType(interfaceObject){
  			//if(!interfaceObject.hasOwnProperty(''))
				//return this.bgColors[]  		
  		//},
  		getCollapsed(){
			let collapsed = (this.num>0) ? true:false;
			this.num++; 
			return collapsed;		
  		},
		showInterfaces(){
			let obj = this;
			let req = { path: this.$router.currentRoute.fullPath, action: "getInterfaces" };
			this.$request({method: 'post', data: req})
				       .then(function (response) {
				       	const {data} = response;
				       	let k = 1;
				       	for(var key in data){
				       		data[key].name = key;
				       		data[key].indx = k;
				       		obj.interfaces.push(data[key]);
				       		k++;
				       		}
				       	obj.inum = 0;
				       	for(var i in obj.interfaces)
								obj.inum++;
				       });	
		},
		addBlock(){
			if(this.inum<=this.maxInterfaces){
				//this.interfaces["lan"+this.inum+".ini"] = "NULL";
				this.inum++;
				let lanNum = this.inum;
				let keylan = "LAN"+lanNum;
				this.interfaces.push({
					IPv4address: "",
					IPv4gateway: "",
					IPv4netmask: "",
					MTU: "",
					OverrideMAC_address: "",
					Protocol: "",
					protocolType: "WAN",
					Resolv: "",
					name: "lan"+lanNum+".ini",
					indx: lanNum,
					isNew: true				
				});
				this.$forceUpdate();	
			}
		},
		isEnabled: function(i){
			return (i!="disabled")	
		},
		getInterfaces(){
			return this.interfaces;		
		}   
  },
  components:{
	InterfaceBlock  
  }
}
</script>
<style scoped>
	
	.InterfaceContainer{
		margin-top: 0px;	
	}	
	
		
	.InterfaceWrapper{
		position: relative;
		padding-top: 0px;	
	}
	
	.btn-top{
		position: relative;
		text-align: right;
		height: 20px;
		top: -75px;
		font-size: 20px !important;
		width: 80%;
		margin-bottom: 5px;
	}
	
	.btn-top button{
		right: 10px;
		width: 140px;
		font-size: 20px !important;
		top: 0px;
		display: inline-block;
		background-color: #4f5a6e;	
	}
	.el-button--primary{
		color: #FFF;	
	}
</style>
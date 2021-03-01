<template>
	<div class="InterfaceWrapper">
	  <div class="btn-top"><el-button class="el-button el-button--primary" type="button" @click="addBlock"><i class="el-icon-circle-plus"></i>Add</el-button>
		</div>	  
	  <div class='InterfaceContainer'>
	    <div v-for="(i, index) in interfaces">
			 <InterfaceBlock :key="i.name" :indx="i.indx" :isCollapsed="(index>0)?true:false" v-bind:iData="i" v-bind:iName="i.name" v-bind:allInterfaces="getInterfaces()" v-if="isEnabled(i)">{{i.name}}</InterfaceBlock>   	    
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
			this.interfaces.splice(__interface.index, 1);
			//console.log(this.interfaces); 
			//delete this.interfaces[interfaceName]; 
			this.$forceUpdate();		
  		},
  		changeInterface(changeObj){
  				console.log("interfaces", this.interfaces);
  				console.log("newInterface", changeObj.newInterface);
  				console.log("oldIntarface", changeObj.oldInterface);
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
				       	//if(response.error==true) return;
				       	const {data} = response;
				       	//console.log(response);
				       	let k = 1;
				       	for(var key in data){
				       		//console.log(key);
				       		data[key].name = key;
				       		data[key].indx = k;
				       		obj.interfaces.push(data[key]);
				       		k++;
				       		//console.log(obj.interfaces);
				       		}
				       	//for(var k=0;k<obj.interfaces.length;k++)
				       		//console.log(obj.interfaces[k]);
				       	//obj.interfaces = response.data;
				       	console.log("response", obj.interfaces);
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
					Resolv: "",
					name: "lan"+lanNum+".ini"				
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
		margin-top: 30px;	
	}	
	
		
	.InterfaceWrapper{
		position: relative;
		padding-top: 0px;	
	}
	
	.btn-top{
		text-align: right;
		height: 30px;
		top: 0px;
		font-size: 20px !important;
		width: 80%;
		margin-bottom: 15px;
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
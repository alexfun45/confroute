<template>
	<div class="InterfaceWrapper">
	  <div class="btn-top"><el-button class="el-button el-button--primary" type="button" @click="addBlock"><i class="el-icon-circle-plus"></i>Add</el-button>
		</div>	  
	  <div class='InterfaceContainer'>
	    <div v-for="(i, indx) in interfaces">
			 <InterfaceBlock :key="i.name" :indx="indx" :isCollapsed="(indx>0)?true:false" v-bind:iData="i" v-bind:iName="i.name" v-bind:allInterfaces="getInterfaces()" v-if="isEnabled(i)">{{i.name}}</InterfaceBlock>   	    
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
  			//if(this.changeNum>1){
	  			//console.log("change");
				//console.log(changeObj.newInterface+" "+changeObj.oldInterface);
				let tmp = this.interfaces[changeObj.newInterface];
				this.interfaces[changeObj.newInterface] = this.interfaces[changeObj.oldInterface];
				this.interfaces[changeObj.oldInterface] = tmp;
				//console.log(this.interfaces);
				//console.log('change');
				this.$forceUpdate();	
			//}	
			//this.changeNum++;		
  		},
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
				       	for(var key in data){
				       		//console.log(key);
				       		data[key].name = key;
				       		obj.interfaces.push(data[key]);
				       		//console.log(obj.interfaces);
				       		}
				       	for(var k=0;k<obj.interfaces.length;k++)
				       		console.log(obj.interfaces[k]);
				       	//obj.interfaces = response.data;
				       	console.log("response= ", obj.interfaces);
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
	
	.InterfaceContainer > div:nth-child(odd) .InterfaceBlock{
		background-color: #93f3fa;		
	}
	
	.InterfaceContainer > div:nth-child(even) .InterfaceBlock{
		background-color: #d9b9fa;	
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
	}
</style>
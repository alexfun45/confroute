<template>
		<div class="InterfaceBlock" :style="{backgroundColor : bgColor}" v-loading="loading" element-loading-text="Loading..." element-loading-spinner="el-icon-loading" element-loading-background="rgba(0, 0, 0, 0.8)">
			<div class="collapsedTitle" :class="{collapsed: isCollapse}"><i style="margin-right: 4px;" class="fa fa-server"></i>{{getLanName(iName)}}</div>
			<span v-on:click="collapseBlock" class=" fa" :class="{'fa fa-caret-down': !isCollapse, block_dropper: true, block_dropper: true, 'fa fa-caret-left': isCollapse}"></span>
				<div :class="{'interface-block-wrapper': true, collapse: isCollapse}">
					<div class="sectionBlock">
						<p class="section-line">
							<select v-model="interfaceIndx" v-on:change="changeIndex(indx, $event)" class="shortList" id="InterfacesLan"><option :value="n" v-for="n in maxInterfaces">{{("LAN"+n)}}</option></select> <select v-model="protocolType" :disabled="!editMode" class="shortList" id="InterfacesProtocol"><option value="WAN">WAN</option><option value="Local">Local</option></select>				
						</p>
						<p class="section-line"><label class="itemLabel">Protocol:</label><select v-model="protocol" :disabled="!editMode"><option :value="protocol.val" v-for="protocol in protocols">{{protocol.title}}</option></select>
						</p>
					</div>			
					<div class="flexcols">
						<div class="flex-item">
							<table class="iptab">
								<tr :class="{'disabled': isDynamic}"><td><label class="itemLabel">IPv4 address</label></td><td><ip-input v-model="ipv4addr" :disabled="(protocol=='dynamic')" :active="editMode" :ip='ipv4addr_placeholder' :segments=4 :segmentMaxSize=3></ip-input></td></tr>
								<tr :class="{'disabled': isDynamic}"><td><label class="itemLabel">IPv4 netmask</label></td><td><ip-input v-model="ipvnetmask" :active="editMode" :ip="ipvnetmask_placeholder"  :segments=4 :segmentMaxSize=3></ip-input></td></tr>
								<tr :class="{'disabled': isDynamic}" v-if="protocolType=='WAN'"><td><label class="itemLabel">IPv4 gateway</label></td><td><ip-input v-model="ipv4gateway" :active="editMode" :ip="ipv4gateway_placeholder"  :segments=4 :segmentMaxSize=3></ip-input></td></tr>
								<tr v-if="protocolType=='WAN'"><td :class="{'disabled': !static_resolv}"><label class="itemLabel">Resolv</label></td><td><span :class="{'disabled': !static_resolv}"><ip-input v-model="resolv" :active="editMode" :segments=4 :segmentMaxSize=3></ip-input></span><span v-if="isDynamic"><input v-model='static_resolv' type='checkbox'> Static resolv</span></td></tr>										
						</table> <span></span>				
						</div>
						<div class="flex-item">
							<p class="section-line"><label class="itemLabel">Override MAC address </label><ip-input :active="editMode" v-model="overrideMacAddr" :segments=6 allowedregexp="[a-fA-F0-9]" :segmentMaxSize=2></ip-input></p>				
							<p class="section-line"><label class="itemLabel">MTU </label><ip-input v-model="mtu" :segments=1 :active="editMode" :segmentMaxSize=4 :maxNumber=10000></ip-input></p>				
						</div>
					</div>	
					<div class="btnPanel"><span style="margin-right: 5px;"><el-button type="success" @click="toggleEdit">{{editBtn}}</el-button></span><span><button type="button" @click="delCancelHandler" class="el-button el-button--danger"><span>{{DelCancelBtn}}</span></button></span></div>				
			</div>
			
			</div>
</template>
<script>
//import maskedInput from 'vue-masked-input'
//import vueIpInput from 'vue-ip-input'
import IpInput from './IpInput'
export default{
	name: "InterfaceBlock",
	data: function() {
		return {
			maxInterfaces: parseInt(this.$store.getters.getMaxInterfaces),
			ipv4addr_placeholder: "127.0.0.1",
         ipv4addr: this.iData.IPv4address || "",
         ipvnetmask: this.iData.IPv4netmask || "",
         interfacePort: '',
         interfaceIndx: 0,
         protocol: this.iData.Protocol || "",
         protocolType: this.iData[this.getLanName(this.iName)] || "",
         loading: false,
         static_resolv: false,
         loader: null,
         mtu: this.iData.MTU || "",
         loading: false,
         ipvnetmask_placeholder: "255.255.255.0",
         ipv4gateway: this.iData.IPv4gateway || "",
         ipv4gateway_placeholder: "10.10.0.1",
         resolv: this.iData.Resolv || "",
         overrideMacAddr: this.iData.OverrideMAC_address || "",
			protocols: [{title:'Static adress', val: 'static'}, {title: 'Dynamic address', val: 'dynamic'}],
			currentColor: null,
			editMode: false,
			editDialogVisible: false,
			own: '',
			changeNum: 0,
			bgColors: {
			"WAN": "#d9b9fa",
			"Local": "#93f3fa",
			__isCollapse: true     
      	}	
		}	
	},
	//props: ["iData", "iName", "allInterfaces", "isCollapsed", "indx"],
	props:{
		iData: {
				type: Object,
				default: {
					ipv4addr: false,
					ipvnetmask: false,
					protocol: '',
					protocolType: '',
					mtu: '',
					ipv4gateway: '',
					resolv: '',
					overrideMacAddr: ''									
				}
			},
			iName:{
				type: String,
				default: ''			
			},
			allInterfaces:{
				type: Array			
			},
			isCollapsed:{
				type: Boolean			
			},
			indx:{
				type: Number			
			},
			isNew:{
				type: Boolean,
				default: false			
			}	
	},
	created(){
		this.interfaceIndx = this.indx;
		this.__isCollapse = this.isCollapsed;		
	},
	mounted(){
		this.interfacePort = this.iName;
		console.log("port="+this.iName+" start index="+this.interfaceIndx+" protocol="+this.iData.Protocol);
		//this.interfaceIndx = this.indx; 
	},
	watch:{
		interfaceIndx: function(val, oldVal){
			//console.log("interfaces="+val+" "+oldVal);
			//if(this.changeNum>0){
				//console.log("change");
			//if(!this.editMode)
				//this.$bus.$emit("changeInterface", {newInterface: val, oldInterface: oldVal});
				//}
			//this.changeNum++;	
		}	
	},
	computed: {
		//maxInterfaces(){
			//return this.$store.getters.getMaxInterfaces;
		//},
		isDynamic(){
			return (this.protocol=='dynamic');		
		},
		bgColor: function(){
			return this.bgColors[this.protocolType];		
		},
		isCollapse: function(){
			return this.isCollapsed;		
		},
		editBtn: function(){
			return (this.editMode) ? "Save":"Edit"		
		},
		DelCancelBtn: function(){
			return (this.editMode) ? "Cancel":"Delete";		
		}	
	},
	methods:{
		changeIndex(item, event){
			//console.log(this.InterfaceIndx);
			//this.interfaceIndx = item;
			//console.log("ints= "+item+" "+this.interfaceIndx);
			let newIndx = event.target.value;
			let old = "lan"+item+".ini";
			this.interfacePort = "lan"+event.target.value+".ini";
			let isExists = false;
			for(let i in this.allInterfaces){
				if(this.allInterfaces[i].indx==newIndx && newIndx!=item)
					isExists = true;			
			}
			if(isExists){
				let selectElement = document.getElementById('InterfacesLan');
				selectElement.options[newIndx-1].selected = false;
				selectElement.options[newIndx-1].disabled = true;
				selectElement.options[item-1].selected = true;
				this.interfaceIndx = item;	
			}
			let res = this.$bus.$emit("changeInterface", {newInterface: item-1, oldInterface: this.interfaceIndx-1, isNew: this.isNew});
			//console.log(this.allInterfaces);
			//console.log("change index "+item+" "+event.target.value);		
		},
		toggleEdit(){
			this.editMode = !this.editMode;
			if(!this.editMode)
				this.save();
		},
		delCancelHandler(){
			if(this.editMode){
				this.editMode = false;
				}
			else
				this.delete();	
		},
		delete(){
			this.$confirm('Вы уверены что хотите удалить настройки?', 'Warning', {
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
          type: 'warning'
        }).then(() => {
        	 let __interface = {name: this.iName, index: this.indx};
          this.$bus.$emit("removeInterface", __interface);
        }).catch(() => {
                    
        });
			},
		save(){
			let data = {
				interfaceFile: this.interfacePort,
				interfacePort: this.getLanName(this.interfacePort),
				protocolType: this.protocolType,
				protocol: this.protocol,
				ipv4addr: this.ipv4addr,
				ipvnetmask: this.ipvnetmask,
				ipv4gateway: this.ipv4gateway,
				resolv: this.resolv,
				overrideMacAddr: this.overrideMacAddr,
				mtu: this.mtu			
			};
			let dat = {
				data: data,
				path: this.$router.currentRoute.fullPath,
				action: "saveInterface"			
			}
			//this.$http.post("./dist/api/api.php", JSON.stringify({ data: data, path: this.$router.currentRoute.fullPath, action: "saveInterface" }));
			this.$request({method: 'post', data: dat})			
			const loading = this.$loading({
          lock: true,
          text: 'Loading',
          spinner: 'el-icon-loading',
          background: 'rgba(0, 0, 0, 0.7)'
        });
        setTimeout(() => {
          loading.close();
        }, 2000);
		  
		},
		getLanName(name){
			//console.log("name="+name);
			return name.replace("\.ini", "").toUpperCase();
		},
		collapseBlock(){
			this.isCollapsed = !this.isCollapsed;		
		},
		open() {
        this.$confirm('Вы уверены что хотите удалить настройки?', 'Warning', {
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
          type: 'warning'
        }).then(() => {
          this.$message({
            type: 'success',
            message: 'Delete completed'
          });
        }).catch(() => {
          this.$message({
            type: 'info',
            message: 'Delete canceled'
          });          
        });
      }
	},
	components:{
		IpInput
	}
}
</script>
<style scoped>
@import url("//unpkg.com/element-ui@2.15.0/lib/theme-chalk/index.css");
	.InterfaceBlock{
		width: 80% !important;
		position: relative;
		margin-bottom: 20px;
		border:2px solid #24bfb5;
	}
	.shortList{
		min-width: 100px;	
	}
	.sectionBlock{
		border-bottom: 2px solid #24bfb5;	
	}
	.section-line{
		display: inline-block;
		width: 100%;
		padding: 8px 12px;
		margin-bottom: 7px;
		margin-top:0;
		line-height: normal;	
	}
	.itemLabel{
		font-size: 16px;
		font-weight: 400;
		margin-right: 5px;	
	}
	.flexcols{
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
		width: 100%;
	}
	.flex-item{
		display: flex;
		padding: 15px 10px;
		flex-direction: column;
		flex-basis: 100%;
		flex: 1;
	}
	.flex-item + .flex-item{
		margin-left: 4%;
	}
	.vue-ip{
		border: 1px solid #000 !important;	
	}
	.vue-ip .segment{
		border: 1px solid #000 !important;		
	}
	.iptab{
		border-spacing: 10px 15px;	
	}
	.block_dropper{
		position: absolute;
		top: 0px;
		right: 5px;
		cursor: pointer;
		font-size: 48px;	
	}
	.collapsedTitle{
		font-size: 22px;
		font-weight: bold;
		padding: 7px 12px;
		display: none;	
	}
	.collapse{
		display: none;	
	}
	.collapsed{
		display: block;	
	}
	.btnPanel{
		display: inline-block;
		position: absolute;
		bottom: 5px;
		right: 10px;
		margin-right: 10px;	
		width: 250px;
	}
	.btnPanel > span{
		display: inline-block;	
	}
	.btnPanel button{
		display: inline-block;
		width: 100px;
		}
	.success {
    	background-color: #4caf50 !important;
    	border-color: #4caf50 !important;
		}
	.disabled{
		opacity: 0.2;	
	}
</style>
<template>
	<div class="app-wrapper">
		<div class='fixed-header'>
				<div class='logoblock'>
					<img :id="logoId" :src="getImagePath" @load="onImgLoad" :style="{display: fromLoaded}" />
					<image-uploader
					    :debug="1"
					    :maxWidth="80"
					    :maxHeight="80"
					    :maxSize="1000"
					    :quality="0.7"
					    :autoRotate=true
					    outputFormat="string"
					    :preview=false
					    :className="['fileinput']"
					    :capture="false"
					    accept="image/*"
					    doNotResize="['gif', 'svg']"
					    @input="setImage"
					  >
						<label class="uploadLogoBlock" for="fileInput" slot="upload-label"><img class='upload-default' style="max-width: 80px;"  v-if="!isLoaded" :src='logoUpload' /><div v-if="isLoaded" class='triggerUpload'></div></label>					  
					  </image-uploader>				
				</div>
				<div class='top-panel'>
					<indicators/>				
				</div>
	        <navbar />
	        <div class='top-menu'>
					<ul>
						<li><router-link to="/STATUS">STATUS</router-link></li><li><router-link to="/Network">NETWORK</router-link></li><li><router-link to="/FIREWALL">FIREWALL</router-link></li><li><router-link to="/SERVICES">SERVICES</router-link></li>					
					</ul>	        
	        </div>
	    </div>
		<div class="main-container">
		<sidebar-menu @toggle-collapse="onToggleSideBar" :menu="menu"/>
      	<app-main :withCollapsed="withCollapsed" :title="title"/>	
		</div>
	</div>
</template>
<script>
	import AppMain from './components/AppMain'
	import Navbar from './components/Navbar'
	import Indicators from './components/Indicators'
	import { SidebarMenu } from 'vue-sidebar-menu'
	import ImageUploader from 'vue-image-upload-resize'
	//Vue.use(SidebarMenu)
	export default{
		name: 'Layout',
		created() {
			this.title = this.$router.currentRoute.meta.title;
			let root = this.$router.currentRoute.fullPath.split("/");
			this.currentPath = this.getTopPath();
			this.menu = this.paths[this.currentPath];
    		//let meta = this.getFields(this.$route.meta);
  		},
		data() {
  				return {
  					title: "",
  					currentPath: '',
  					fromLoaded: "none",
  					logoId: (new Date()).getTime(),
  					path: "",
  					logo: "./dist/assets/logo.png",
  					isLoaded: false,
  					isLogoLoaded: false,
  					logoUpload: './dist/assets/defaultlogo.png',
  					withCollapsed: false,
  					paths:{
						Network:[
							{
								href: {path: '/Network/Interfaces'},
								to: '/',
								title: 'Interfaces'							
							},
							{
								href: {path: '/Network/FastSetup'},
								to: '/FastSetup',
								title: 'FastSetup'						
							},
							{
								href: {path: '/Network/DNSServer'},
								to: '/DnsServer',
								title: 'DNS Server'							
							}							
						]  					
  					},
  					menu:[],
            	/*menu: [
                {
                    href: { path: '/' },
                    to: '/',
                    title: 'Network',
                    icon: 'fa fa-user',
                    child:[
							 {
                         href: '/Network/Interfaces',
                         title: 'Interfaces',
                         badge: {
						 			element:'router-link',
						 			attributes: {to: "/"}                   
                    			}
                      },
                      {
                         href: '/Network/FastSetup',
                         title: 'FastSetup',
                         badge: {
						 			element:'router-link',
						 			attributes: {to: "/"}                   
                    			}
                      },                   
                    ], 
                	},
                	{
                    href: { path: '/Config' },
                    to: '/config',
                    title: 'Config',
                    icon: 'fa fa-circle',
                    badge: {
						 	element:'router-link',
						 	attributes: {to: "/Config"}                    
                    	}
                	 },
            		]*/
            	}
          	},
      computed:{
      	getIsLoaded(){
				return !this.isLoaded;      	
      	},
      	routPath(){
				return this.$router.currentRoute.path;      	
      	},
      	getImagePath(){
				return this.logo + '?' + Math.random();      	
      	}
      },
      watch: {
		    $route(to, from) {
		    	  this.path = this.$router.currentRoute.fullPath;
		        this.title = to.meta.title || '';
		        this.currentPath = this.getTopPath();
		        //console.log("path="+this.path);
		    },
		},
      methods:{
      	getTopPath(){
				let fullpath = this.$router.currentRoute.fullPath.split("/");
				return fullpath[1];     	
      	},
      	onImgLoad(){
				this.isLoaded = true
				this.fromLoaded = "inline-block";     	
      	},
      	setImage: function (file) {
		      this.hasImage = true
		      this.image = file
		      let obj = this;
		      let req = {path: '/admin', action: "setLogo", data: {imagedata: file}};
		      this.$request({method: 'post', data: req}).then(function (response) {
		      	obj.fromLoaded = "inline-block"
		      	obj.logo = obj.logo + '?' + Math.random();
		      	obj.logoId = (new Date()).getTime();
		      	obj.isLoaded = true
		      	obj.$forceUpdate();
		      });
		    },
      	getFields: (meta) => {
			  //console.log(meta);
			  return meta.fields.title; // not sure what you're trying to return exactly
			},
			onToggleSideBar(collapsed){
				this.withCollapsed = collapsed;			
			}
      },
      components: {
		    AppMain,
		    Navbar,
		    //RightPanel,
		    //Settings,
		    SidebarMenu,
		    Indicators,
		    ImageUploader
		  }
	}
</script>
<style lang="scss" scoped>
	
	.app-wrapper {
	    position: relative;
	    height: 100%;
	    width: 100%;
	    /*margin-left: 370px;*/
    }
   .v-sidebar-menu .vsm--toggle-btn::after {
		content: \f03b !important;		   
   }
   
   
   .triggerUpload{
		display: inline-block;
		width: 80px;
		height: 80px;
		position:absolute;
		top: 0;
		left :0;
		z-index: 100;   
   }
   
   .fixed-header {
	   position: relative;
	   height: 200px;
	   background-color: #4f5a6e;
	   top: 0;
	   left: 0;
	   z-index: 9;
	   width: 100%;
	   transition: width 0.28s;
  }
  
  .top-menu{
	 position: absolute;
	 left: 30px;
	 bottom: 5px;  
	 font-size: 22px;
	 color: #8893aa;
	 font-weight: bold;
  }
  .top-menu > ul{
	 list-style-type: none;
  } 
  
  .top-menu > ul> li{
	 display: inline-block; 
	 margin-right: 10px; 
  }
  
  .top-menu > ul> li:hover{
	 color: #FFF;  
  }
</style>
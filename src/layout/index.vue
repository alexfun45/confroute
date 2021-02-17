<template>
	<div class="app-wrapper">
		<sidebar-menu :menu="menu"/>
		<div class="main-container">
			<div>
				<div class='top-panel'>
					<indicators/>				
				</div>
				<div class='fixed-header'>
	        		<navbar />
	        	</div>
        		<div class="content">
					 <h1>{{title}}</h1>      		
        		</div>
      	</div>	
      	<app-main />	
		</div>
	</div>
</template>
<script>
	import AppMain from './components/AppMain'
	import Navbar from './components/Navbar'
	import Indicators from './components/Indicators'
	import { SidebarMenu } from 'vue-sidebar-menu'
	//Vue.use(SidebarMenu)
	export default{
		name: 'Layout',
		created() {
			this.title = this.$router.currentRoute.meta.title;
			console.log(this.$router.currentRoute);
    		//let meta = this.getFields(this.$route.meta);
  		},
		data() {
  				return {
  					title: "",
  					path: "",
            	menu: [
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
            ]
            }
          },
      computed:{
      	routPath(){
				return this.$router.currentRoute.path;      	
      	}
      },
      watch: {
		    $route(to, from) {
		    	  this.path = this.$router.currentRoute.fullPath;
		        this.title = to.meta.title || 'Some Default Title';
		    },
		},
      methods:{
      	getFields: (meta) => {
			  console.log(meta);
			  return meta.fields.title; // not sure what you're trying to return exactly
			}
      },
      components: {
		    AppMain,
		    Navbar,
		    //RightPanel,
		    //Settings,
		    SidebarMenu,
		    Indicators
		    //TagsView
		  }
	}
</script>
<style lang="scss" scoped>
	.app-wrapper {
	    position: relative;
	    height: 100%;
	    width: 95%;
	    margin-left: 370px;
    }
   .v-sidebar-menu .vsm--toggle-btn::after {
		content: \f03b !important;		   
   }
   .fixed-header {
    position: fixed;
    top: 0;
    right: 0;
    z-index: 9;
    width: 200px;
    transition: width 0.28s;
  }
   
</style>
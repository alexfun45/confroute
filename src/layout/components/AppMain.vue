<template>
  <section class="app-main" :class="{fullWidth: withCollapsed}">
    	<div>
        <div class="content">
			 <h1 class='section-title'>{{title}}</h1>      		
        </div>
      </div>	
      <transition name="fade-transform" mode="out-in">
        <router-view :key="key" />
    </transition>
  </section>
</template>

<script>
export default {
  name: 'AppMain',
  props: ['withCollapsed', 'title'],
  data(){
	return {
			
	}  
  },
  watch: {
		    $route(to, from) {
		    	  this.path = this.$router.currentRoute.fullPath;
		        this.title = to.meta.title || '';
		    },
		},
  computed: {
  	created() {
			this.title = this.$router.currentRoute.meta.title;
			console.log("ttile="+this.title);
  		},
    key() {
      return this.$route.path
    }
  }
}
</script>

<style lang="scss" scoped>
.app-main{
  min-height: calc(100vh - 50px);
  width: calc(100% - 370);
  margin-left: 370px;
  position: relative;
  overflow: hidden;
}

.fullWidth{
	margin-left: 100px;
	width: 100%;
}

.fixed-header+.app-main{
  padding-top: 50px;
}

.hasTagsView{
  .app-main {
    /* 84 = navbar + tags-view = 50 + 34 */
    min-height: calc(100vh - 84px);
  }

  .fixed-header+.app-main{
    padding-top: 84px;
  }
}
.section-title{
	font-size: 34px;
	color: #8893aa;
	width: 80%;
	border-bottom: 2px solid #8893aa;
}

.el-popup-parent--hidden{
  .fixed-header {
    padding-right: 15px;
  }
}
</style>
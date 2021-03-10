<template>
  <div class="navbar">
  	<el-dropdown class="avatar-container right-menu-item hover-effect" trigger="click">
        <div class="avatar-wrapper">
          <el-avatar :size="size" :src="avatarSrc" fit="contain"></el-avatar>
          <i class="el-icon-caret-bottom" />
        </div>
        <el-dropdown-menu slot="dropdown">
          <router-link to="/profile/index">
            <el-dropdown-item>Профиль</el-dropdown-item>
          </router-link>
          <router-link to="/Settings/users" v-if="(login=='admin')">
            <el-dropdown-item>Настройки</el-dropdown-item>
          </router-link>
          <el-dropdown-item divided @click.native="logout">
            <span style="display:block;">Выход</span>
          </el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
      <div class='login-title'>{{login}}</div>
    </div>
 </template>
 <script>
 
 export default {
  data: function(){
	return {
		avatar: '',
		login: '',
		size: 40,
		avatarSrc: './dist/assets/profile_logo.jpg',
		circleUrl: "https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png",	
	}  
  },
  created(){
		this.login = this.$store.getters.login;  
  },
  methods: {
    toggleSideBar() {
      this.$store.dispatch('app/toggleSideBar')
    },
    async logout() {
      await this.$store.dispatch('logout')
      this.$router.push(`/login?redirect=${this.$route.fullPath}`)
    }
  }
}
</script>
<style lang="scss" scoped>
.navbar {
  height: 70px;
  cursor: pointer;
  overflow: hidden;
  position: absolute;
  right: 10px;
  top: 5px;
  background: transparent;
  box-shadow: 0 1px 4px rgba(0,21,41,.08);
  }
   .avatar-container {
      margin-right: 30px;

      .avatar-wrapper {
        margin-top: 5px;
        position: relative;

        .user-avatar {
          cursor: pointer;
          width: 40px;
          height: 40px;
          border-radius: 10px;
        }

        .el-icon-caret-bottom {
          cursor: pointer;
          position: absolute;
          right: -20px;
          top: 25px;
          font-size: 12px;
        }
      }
  }
  
  .login-title{
		color: #FFF;
		font-size: 16px;  
  }
</style>
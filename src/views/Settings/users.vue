<template>
	<div>
	<el-button @click="dialogAddVisible=true" class="addBtn" type="primary">+добавить</el-button>
	<el-table
    :data="users"
    stripe
    style="width: 100%">
    <el-table-column
      prop="login"
      label="Логин"
      width="180">
    </el-table-column>
    <el-table-column
      prop="name"
      label="Имя"
      width="180">
    </el-table-column>
    <el-table-column
      prop="surname"
      label="Фамилия">
    </el-table-column>
     <el-table-column
      prop="lastname"
      label="Отчество">
    </el-table-column>
    <el-table-column
      prop="email"
      label="email">
    </el-table-column>
    <el-table-column
      prop="usertype"
      label="тип пользователя">
    </el-table-column> 
     <el-table-column
      align="left">
       <template slot-scope="scope">
        <el-button
          size="mini"
          @click="handleEdit(scope.$index, scope.row)">Edit</el-button>
        <el-button
          size="mini"
          type="danger"
          @click="handleDelete(scope.$index, scope.row)">Delete</el-button>
      </template>
   </el-table-column>
  </el-table>

 
	
	<el-dialog title="Создание пользователя" :visible.sync="dialogAddVisible">
	  <el-form :model="newUser" :rules="rules" ref="ruleForm">
	  	 <el-form-item prop="login" label="Логин" :label-width="formLabelWidth">
	      <el-input v-model="newUser.login" autocomplete="off"></el-input>
	    </el-form-item>
	    <el-form-item prop="name" label="Имя" :label-width="formLabelWidth">
	      <el-input v-model="newUser.name" autocomplete="off"></el-input>
	    </el-form-item>
	    <el-form-item prop="lastname" label="Отчество" :label-width="formLabelWidth">
	      <el-input v-model="newUser.lastname" autocomplete="off"></el-input>
	    </el-form-item>
	    <el-form-item prop="surname" label="Фамилия" :label-width="formLabelWidth">
	      <el-input v-model="newUser.surname" autocomplete="off"></el-input>
	    </el-form-item>
	    <el-form-item prop="email" label="email" :label-width="formLabelWidth">
	      <el-input v-model="newUser.email" autocomplete="off"></el-input>
	    </el-form-item>
	    <el-form-item prop="password" label="password" :label-width="formLabelWidth">
	      <el-input v-model="newUser.password" autocomplete="off"></el-input>
	    </el-form-item>
	    <el-form-item prop="usertype" label="тип пользователя" :label-width="formLabelWidth">
	      <el-select required="true" v-model="newUser.usertype" placeholder="Тип пользователя">
	        <el-option label="пользователь" value="user"></el-option>
	        <el-option label="администратор" value="admin"></el-option>
	      </el-select>
	    </el-form-item>
	  </el-form>
	  <span slot="footer" class="dialog-footer">
	    <el-button @click="dialogAddVisible = false">Отмена</el-button>
	    <el-button type="primary" @click="addUser('ruleForm')">Сохранить</el-button>
	  </span>
	</el-dialog>  
  
  <el-dialog title="Редактирование" :visible.sync="dialogEditVisible">
  	  <div class="formTitle">Редактирование пользователя {{userData.login}}</div>
	  <el-form :model="userData">
	    <el-form-item label="Имя" :label-width="formLabelWidth">
	      <el-input v-model="userData.name" autocomplete="off"></el-input>
	    </el-form-item>
	    <el-form-item label="Отчество" :label-width="formLabelWidth">
	      <el-input v-model="userData.lastname" autocomplete="off"></el-input>
	    </el-form-item>
	    <el-form-item label="Фамилия" :label-width="formLabelWidth">
	      <el-input v-model="userData.surname" autocomplete="off"></el-input>
	    </el-form-item>
	    <el-form-item label="email" :label-width="formLabelWidth">
	      <el-input v-model="userData.email" autocomplete="off"></el-input>
	    </el-form-item>
	    <el-form-item label="тип пользователя" :label-width="formLabelWidth">
	      <el-select v-model="userData.usertype" placeholder="Тип пользователя">
	        <el-option label="пользователь" value="user"></el-option>
	        <el-option label="администратор" value="admin"></el-option>
	      </el-select>
	    </el-form-item>
	  </el-form>
	  <span slot="footer" class="dialog-footer">
	    <el-button @click="dialogEditVisible = false">Отмена</el-button>
	    <el-button type="primary" @click="saveUser">Сохранить</el-button>
	  </span>
	</el-dialog>
	</div>
</template>
<script>
import Vue from 'vue';
export default {
  name: 'users',
  data(){
   var validateLogin = (rule, value, callback) => {
   	var regexp = /[а-яА-Я]/i;
		if(regexp.test(value))
			return callback(new Error('Логин может содержать только символы латиницы'));
		else
			callback();   
    }
    var  validatePassword = (rule, value, callback) => {
		   if(value.length<6)
				return callback(new Error('Пароль должен содержать больше 6 символов'));
			if(/[^\w\d\!~@#\$%\^\&\*]/.test(value))
				return callback(new Error('Пароль не может содержать символы кириллицы'));
			if(!/[0-9]/.test(value))
				return callback(new Error('Пароль должен содержать хотя бы одну цифру'));
			if(!/[A-Za-z]/.test(value))
				return callback(new Error('Пароль должен содержать хотя бы один символ'));
			callback();
    }
    
	return {
		users: [],
		newUser: {
			login: '',
			name: '',
			surname: '',
			lastname: '',
			email: '',
			password: '',
			usertype:''		
		},
		userData: {},
		dialogEditVisible: false,
		dialogAddVisible: false,
		formLabelWidth: '120px',
		rules: {
          login: [
            { required: true, message: 'Пожалуйста, введите логин', trigger: 'blur' },
            { min: 3, max: 40, message: 'Длина должна быть от 3 до 40 символов', trigger: 'blur' },
            { validator: validateLogin, trigger: 'blur' }
          ],
          name: [
				{required: true, message: 'Пожалуйста, введите имя'}          
          ],
          surname: [
				{required: true, message: 'Пожалуйста, введите фамилию'}          
          ],
          //lastname: [
				//{required: false}          
          //],
          email: [
				{required: true, message: 'Пожалуйста, введите email'}          
          ],
          password: [
				{required: true, message: 'Пожалуйста, введите пароль'},
				{validator: validatePassword, trigger: 'blur'}          
          ],
          usertype: [
				{required: true, message: 'Пожалуйста, введите тип пользователя'}          
          ],
         }
	}  
  },
  created(){
  	 let obj = this;
	 let req = { path: "/Users", action: "getUsers" };
	 this.$request({method: 'post', data: req})
		  .then(function (response) {
				     const {data} = response;
				     obj.users = data;
				       	
		}); 
  	},
  	methods: {
      handleEdit(index, row) {
      	this.userData = row;
      	this.userData.index = index;
      	this.dialogEditVisible = true;
      },
      handleDelete(index, row) {
       let obj = this;
       this.$confirm('Вы уверены что хотите удалить пользователя?', 'Warning', {
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
          type: 'warning'
        }).then(() => {
         obj.users.splice(index, 1);
        	let req = { path: "/Users", action: "removeUser", data: index};
          obj.$request({method: 'post', data: req});
        }).catch(() => {
                    
        }); 
      },
      addUser(formName){
      	let obj = this;
      	this.$refs[formName].validate((valid) => {
          	if (valid) {
          		let req = { path: "/Users", action: "addUser", data: obj.newUser};
          		obj.$request({method: 'post', data: req});
          		obj.users.push(Vue.util.extend({}, obj.newUser));
          		obj.$refs[formName].resetFields();
          		obj.dialogAddVisible = false;
          		
          	} else {
            	console.log('error submit!!');
            	return false;
          	}
        });
      },
      saveUser(){
			this.dialogEditVisible = false; 
			let user = this.userData;
			let req = { path: "/Users", action: "editUser", data: this.userData};
			this.$request({method: 'post', data: req}).then(function (response) {
				     const {data} = response;  	
				});    
      }
    },
  }
</script>
<style lang="scss" scoped>
.formTitle{
	font-weight: bold;
	font-size: 20px;
	margin-bottom: 20px;
}
.addBtn{
	float: right;
	margin-right: 100px;
}
</style>
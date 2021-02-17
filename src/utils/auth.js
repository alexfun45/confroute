import VueCookies from 'vue-cookies'
import request from '@/utils/request'
import store from '@/store'
import router from '@/router'

const TokenKey = 'Token'

export function getToken() {
  return VueCookies.get(TokenKey)
}

export function RefreshToken(){
	//return new Promise((resolve, reject) => {
	//return new Promise((resolve, reject) => { 
			//request.post({method: 'post', data: {action: "refreshToken", path: "/Login", token: store.getters.token}});
			let data = {
				action: "refreshToken",
				path: "/Login",
				token: store.getters.token			
			};
			request({method: 'post', data: data}).then(resp => { 
					  const { data } = resp;
					  //console.log("dat="+data);
					  if(data)
			        	store.commit('SET_TOKEN', data);
			    }).catch(err => {
			      	store.commit('auth_error', err);
			      	router.push("/login");
			    });
		//});
	//});
}

export function setToken(token) {
  return VueCookies.set(TokenKey, token);
}

export function removeToken() {
  return VueCookies.remove(TokenKey)
}

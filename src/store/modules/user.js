import request from '@/utils/request'
import { getToken, setToken, removeToken, RefreshToken } from '@/utils/auth'
const LOGIN = "LOGIN";
const LOGIN_SUCCESS = "LOGIN_SUCCESS";
const LOGOUT = "LOGOUT";

const state = {
	isLoggedIn: !!localStorage.getItem("token"),
	token: localStorage.getItem("token"),
	avatar: '',
	login: localStorage.getItem("login"),
}

const mutations = {
	  auth_request(state){
	    state.status = 'loading'
	  },
	  auth_success(state, token, user){
	    state.status = 'success'
	    state.token = token
	    state.user = user
	  },
	  auth_error(state){
	    state.status = 'error'
	    state.token = ''
	    localStorage.removeItem('token')
	  },
	  logout(state){
	    state.status = ''
	    state.token = ''
	    localStorage.removeItem('token')
	    localStorage.removeItem('login')
	  },
	  SET_TOKEN: (state, token) => {
	  	//console.log("set new token "+token);
    	state.token = token
    	localStorage.setItem("token", token);
  	},
  	SET_LOGIN: (state, login) => {
  		console.log("set login "+login);
  		localStorage.setItem("login", login);
		state.login = login;  	
  	},
  	  REMOVE_TOKEN(state){
		 state.status = ''
	    state.token = ''
	    localStorage.removeItem('token') 	  
  	  }
}

const actions = {
	register({commit}, user){
	  return new Promise((resolve, reject) => {
	    commit('auth_request')
	    axios({url: './dist/api/api.php', data: user, method: 'POST' })
	    .then(resp => {
	      const token = resp.data.token
	      const user = resp.data.user
	      localStorage.setItem('token', token)
	      axios.defaults.headers.common['Authorization'] = token
	      commit('auth_success', token, user)
	      resolve(resp)
	    })
	    .catch(err => {
	      commit('auth_error', err)
	      reject(err)
	    })
	  })
	 },
	 
	 login({commit}, user){
	      return new Promise((resolve, reject) => {
		      commit('auth_request')
		      request({method: 'post', data: user})
		      	.then(response => {
			        const {data} = response
			        //console.log("login")
			        console.log("login="+data.login);
			        //console.log("set token = "+data.jwt);
			        commit('SET_TOKEN', data.jwt)
			        commit('SET_LOGIN', data.login)
			        //setToken(data.jwt)
			        resolve()
		   	}).catch(error => {
	        		reject(error)
	      	});
      	});
	      /*axios({url: './dist/api/api.php', data: user, method: 'POST' })
	      .then(resp => {
	        const token = resp.data.token
	        const user = resp.data.user
	        localStorage.setItem('token', token)
	        axios.defaults.headers.common['Authorization'] = token
	        commit('auth_success', token, user)
	        resolve(resp)
	      })
	      .catch(err => {
	        commit('auth_error')
	        localStorage.removeItem('token')
	        reject(err)
	      })*/
	  },
	  // remove token
  		resetToken({ commit }) {
	    return new Promise(resolve => {
	      //commit('SET_TOKEN', '')
	      //commit('SET_ROLES', [])
	      commit('REMOVE_TOKEN')
	      resolve()
	    })
	 	},
	   logout({commit}){
		  return new Promise((resolve, reject) => {
		    commit('logout')
		    localStorage.removeItem('token')
		    //delete axios.defaults.headers.common['Authorization']
		    //resetRouter()
		    resolve()
		  })
		 }
}

const getters = {
  isLoggedIn: state => state.token,
  isRefreshToken: state => {
	  return RefreshToken().then(response => { const { data } = response; commit('SET_TOKEN', data.jwt); return data.jwt; })
  },
  authStatus: state => state.status,
  token: state => state.token,
  avatar: state => state.avatar,
  login: state => { console.log("call getter login with login = "+state.login); return state.login }
}

export default {
  state,
  mutations,
  actions,
  getters
}
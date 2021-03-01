import request from '@/utils/request'
const state = {
	//main_container_margin: 370,
	config: localStorage.getItem("config")
}

const mutations = {
	/*SET_MAINCONTAINER_WIDTH(state, new_width){
		state.main_container_margin = new_width;	
	},*/
	LOAD_CONFIG(state, config){
		console.log("set config = ", config);
		localStorage.setItem("config", config);
		state.config = config;	
	}
}

const actions = {
	getConfig({ commit }){
		request({method: 'post', data: {path: '/Config', action: "getConfig"} })
		      	.then(response => {
			        const {data} = response
			        console.log("load config")
			        commit('LOAD_CONFIG', data.config);
			        //resolve()
		   	});	
	}
}

const getters = {
	getMaxInterfaces: state => state.config.max_interfaces
	}

export default {
  state,
  mutations,
  actions,
  getters
}
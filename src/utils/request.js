import axios from 'axios'
import store from '@/store'
//import { getToken } from '@/utils/auth'
// create an axios instance
import { MessageBox, Message } from 'element-ui'
const service = axios.create({
  baseURL: "./dist/api/api.php", // url = base url + request url
  // withCredentials: true, // send cookies when cross-domain requests
  timeout: 5000 // request timeout
})

service.interceptors.request.use(
  config => {
    // do something before request is sent

    if (store.getters.token) {
      // let each request carry token
      // ['X-Token'] is a custom headers key
      // please modify it according to the actual situation
      config.headers['X-Token'] = store.getters.token
    }
    return config
  },
  error => {
    // do something with request error
    console.log(error) // for debug
    return Promise.reject(error)
  }
)

service.interceptors.response.use(
	response => {
    const res = response.data;
    const info = res.reqInfo;
    if(info.code==201){
		store.commit("SET_TOKEN", info.token);    
    }
    if(info.code==5000){
		 store.commit("SET_TOKEN", res.token);   
    }
    if (info.code == 50001 || info.code == 50002 || info.code == 50003) {
    	   //console.log("error with "+res.code);
    		//console.log("error with "+res.code);
        // to re-login
        MessageBox.confirm('Вы вышли из системы, вы можете отменить, чтобы остаться на странице или перейти на страницу авторизации', 'Confirm logout', {
          confirmButtonText: 'Re-Login',
          cancelButtonText: 'Cancel',
          type: 'warning'
        }).then(() => {
          store.dispatch('resetToken').then(() => {
            location.reload()
          })
        })
       return Promise.reject(new Error(res.message || 'Error'))
      }
    else {
      return res
  	}
  }
  );

export default service

import Vue from 'vue'
import Router from 'vue-router'
import Layout from '@/layout'
import Login from './views/Auth/login'
import store from './store'

Vue.use(Router)
console.log(store.getters.token);
let router = new Router({
	routes : [
		{
	      path: '/',
	      component: Layout,
	      redirect: '/Network/Interfaces',
	      children:[
	      	{
	      		path: "/Network/Interfaces",
	      		component: () => import('@/views/Interfaces/index.vue'),
	      		name: "Interfaces",
	      		meta: {title: 'Interfaces', requiresAuth: true}
	      	}
	      ]
	    },
	   {
	      path: '/Network',
	      component: Layout,
	      redirect: '/Network/Interfaces',
	      children:[
	      	{
	      		path: "/Network/Interfaces",
	      		component: () => import('@/views/Interfaces/index.vue'),
	      		name: "Interfaces",
	      		meta: {title: 'Interfaces', requiresAuth: true}
	      	}
	      ]
	    },
     {
      path: '/Network/FastSetup',
      component: Layout,
      props: true,
      children:[
      	{
      		path: "/Network/FastSetup",
      		component: () => import('@/views/FastSetup'),
      		name: "FastSetup",
      		meta: {title: 'FastSetup', requiresAuth: true}
      	}
      ]
    },
	 { 
	 	path: '/login', 
	 	component: Login},
	 {
		path: '/Settings/users',
		component: Layout,
		children:[
      	{
      		path: "/Settings/users",
      		component: () => import('@/views/Settings/users.vue'),
      		name: "users",
      		meta: {title: 'Users', requiresAuth: true}
      	}
      ]
		
	 }    
    ]
 });
if(!store.getters.isLoggedIn) 


router.beforeEach((to, from, next) => {
	if(to.matched.some(record => record.meta.requiresAuth)) {
		if (store.getters.isLoggedIn) {
			 //console.log("tokens="+store.getters.isLoggedIn);
			 next()
      	 return
		}
		next('/login') 
	}
	else{
	next()	
	}
		
	});


/*router.beforeEach((to, from, next) => {
  if(to.matched.some(record => record.meta.requiresAuth)) {
    if(store.getters.isLoggedIn) 
    	next()
    else{
    	return store.getters.isRefreshToken.then(response=> { console.log("response="+response); //if(response.data) next(); 
    	});
      return
   }
    next('/login') 
  } else {
    next() 
  }
})*/
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}
export default router;
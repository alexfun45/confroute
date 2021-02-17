import axios from 'axios'

export function login(data) {
  axios.post("./dist/api/api.php", JSON.stringify({ path: this.$router.currentRoute.fullPath, action: "deleteInterface", data: {iName
  return request({
    url: '/vue-element-admin/user/login',
    method: 'post',
    data
  })
}

export function getInfo(token) {
  return request({
    url: '/vue-element-admin/user/info',
    method: 'get',
    params: { token }
  })
}

export function logout() {
  return request({
    url: '/vue-element-admin/user/logout',
    method: 'post'
  })
}

// src/lib/axios.js
import { router } from '@/plugins/router'
import axios from 'axios'

const HTTP = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },
  withCredentials: true,
})

HTTP.interceptors.request.use(
  config => {
    const token = localStorage.getItem('authToken')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  error => Promise.reject(error)
)

HTTP.interceptors.response.use(
  response => response,
  error => {
    if (error.response && error.response.status === 401) {
      localStorage.removeItem('authToken') 
      localStorage.removeItem('user')
      // Utiliser router pour la redirection sans rechargement de page
      if (router && typeof router.push === 'function' && router.currentRoute.value.path !== '/login') {
        router.push('/login')
      }
    }
    return Promise.reject(error)
  }
)

export default HTTP

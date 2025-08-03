// src/lib/axios.js
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
      localStorage.removeItem('token') 
      window.location.href = '/login'  
    }
    return Promise.reject(error)
  }
)

export default HTTP

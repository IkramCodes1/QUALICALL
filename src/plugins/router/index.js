import { createRouter, createWebHistory } from 'vue-router'
import { routes } from './routes'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

export default function (app) {
  router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('authToken')
    const publicPages = ['/login', '/register']
    const authRequired = !publicPages.includes(to.path)

    // Si l'utilisateur est connecté et essaie d'accéder à /login ou /register, rediriger vers /dashboard
    if (isAuthenticated && (to.path === '/login' || to.path === '/register')) {
      return next('/dashboard')
    }

    // Si la page nécessite une authentification et que l'utilisateur n'est pas connecté, rediriger vers /login
    if (authRequired && !isAuthenticated) {
      return next('/login')
    }

    next()
  })

  app.use(router)
}
export { router }

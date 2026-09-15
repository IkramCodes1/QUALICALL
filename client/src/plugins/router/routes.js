export const routes = [
  { path: '/', redirect: '/login' },
  { // Default layout: For logged-in users, with navigation and structure.
    path: '/',
    component: () => import('@/layouts/default.vue'),
    children: [
      {
        path: 'dashboard',
        component: () => import('@/views/dashboard/dashboard.vue'),
      },
      {
        path: 'user',
        component: () => import('@/views/user/User.vue'),
      },
      {
        path: '/audio/:id',  
        name: 'audio',       
        component: () => import('@/views/audio/Audio.vue'),
      },
      {
        path: 'category',
        component: () => import('@/views/category/category.vue'),
      },
      {
        path: 'upload',
        component: () => import('@/views/upload/Upload.vue'),
      },
      {
        path: 'ai',
        component: () => import('@/views/ai/Ai.vue'),
      },
      {
        path: 'profile',
        component: () => import('@/views/profile/Profile.vue'),
      },
      {
        path: 'conversation',
        component: () => import('@/views/conversation/Conversation.vue'),
      },
    ],
  },
  {
    path: '/',
    component: () => import('@/layouts/blank.vue'),
    children: [ // Blank layout: For standalone pages like login/register, where you don’t want the full app UI
      {
        path: 'login',
        component: () => import('@/pages/login.vue'),
      },
      {
        path: 'register',
        component: () => import('@/pages/register.vue'),
      },
      {
        path: '/:pathMatch(.*)*',
        component: () => import('@/pages/[...error].vue'), // Wildcard route for handling 404 errors (any undefined path).
      },
    ],
  },
]

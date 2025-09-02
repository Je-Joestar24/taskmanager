import { requireAuth, requireAdmin, requireGuest } from './guards'

const routes = [
  // Authentication routes
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/Auth/LoginView.vue'),
    meta: { 
      title: 'Login',
      requiresGuest: true
    },
    beforeEnter: requireGuest
  },
  {
    path: '/signup',
    name: 'signup',
    component: () => import('@/views/Auth/SignupView.vue'),
    meta: { 
      title: 'Signup',
      requiresGuest: true
    },
    beforeEnter: requireGuest
  },

  // User dashboard routes
  {
    path: '/tasks',
    name: 'tasks',
    component: () => import('@/views/Tasks/TasksView.vue'),
    meta: { 
      title: 'My Tasks',
      requiresAuth: true
    },
    beforeEnter: requireAuth
  },

  // Admin routes
  {
    path: '/admin',
    name: 'admin',
    component: () => import('@/views/Admin/AdminLayout.vue'),
    meta: { 
      title: 'Admin',
      requiresAdmin: true
    },
    beforeEnter: requireAdmin,
    children: [
      {
        path: '',
        name: 'admin-dashboard',
        component: () => import('@/views/Admin/DashboardView.vue'),
        meta: { title: 'Admin Dashboard' }
      },
      {
        path: 'tasks',
        name: 'admin-tasks',
        component: () => import('@/views/Admin/TasksView.vue'),
        meta: { title: 'All Tasks' }
      },
      {
        path: 'users',
        name: 'admin-users',
        component: () => import('@/views/Admin/UsersView.vue'),
        meta: { title: 'All Users' }
      }
    ]
  },

  // 404 route
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: () => import('@/views/NotFoundView.vue'),
    meta: { title: 'Page Not Found' }
  }
]

export default routes
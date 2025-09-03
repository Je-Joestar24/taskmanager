import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

// Expose Pusher to window as required by Echo
if (typeof window !== 'undefined') {
  window.Pusher = Pusher
}

let echoInstance = null

export const getEcho = (token) => {
  if (echoInstance) return echoInstance

  echoInstance = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_KEY,
    cluster: import.meta.env.VITE_PUSHER_CLUSTER,
    wsHost: import.meta.env.VITE_PUSHER_HOST || window.location.hostname,
    wsPort: Number(import.meta.env.VITE_PUSHER_PORT || 6001),
    wssPort: Number(import.meta.env.VITE_PUSHER_PORT || 6001),
    forceTLS: String(import.meta.env.VITE_PUSHER_TLS || 'false') === 'true',
    enabledTransports: ['ws', 'wss'],
    authEndpoint: '/broadcasting/auth',
    auth: {
      headers: {
        Authorization: token ? `Bearer ${token}` : ''
      }
    }
  })

  return echoInstance
}

export const disconnectEcho = () => {
  if (echoInstance) {
    try {
      echoInstance.disconnect()
    } catch (_) {}
    echoInstance = null
  }
}




// resources/js/app.js

import '../css/app.css';
import './bootstrap';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { Provider } from 'react-redux';
import { store } from './Redux/store';
import { fetchUser } from './Redux/authSlice'; // ✅ import fetchUser

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.jsx`,
      import.meta.glob('./Pages/**/*.jsx'),
    ),
  setup({ el, App, props }) {
    // ✅ Fetch user and log result
    store
      .dispatch(fetchUser())
      .then((res) => {
        console.log('✅ User fetched from session:', res.payload);
      })
      .catch((err) => {
        console.log('❌ Failed to fetch user:', err);
      });

    const root = createRoot(el);
    root.render(
      <Provider store={store}>
        <App {...props} />
      </Provider>
    );
  },
  progress: {
    color: '#4B5563',
  },
});

import '../css/app.css'
import '@fortawesome/fontawesome-free/css/all.css'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import naive from 'naive-ui';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

import Antd from 'ant-design-vue';
import 'ant-design-vue/dist/reset.css';


// Install Naive UI globally

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  title: title => title ? `${title} - Interview Test` : 'Interview Test',
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(naive)
      .use(ZiggyVue)
      .use(Antd)
      .mount(el)
  },
})

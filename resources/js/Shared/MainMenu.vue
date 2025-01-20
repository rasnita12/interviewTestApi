<template>
  <div>
      <a-menu :openKeys="openKeys" :selectedKeys="selectedKeys" mode="inline" theme="dark" style="background-color: #222d32">
          <a-menu-item key="dashboard">
              <Link :href="route('dashboard')"> <DashboardOutlined /> Dashboard </Link>
          </a-menu-item>
          <a-menu-item key="customer">
              <Link :href="route('customers.index')"> <UserOutlined /> Customer </Link>
          </a-menu-item>
          <a-menu-item key="health-center">
              <Link :href="route('health-centers.index')"> <UserOutlined /> Health Center </Link>
          </a-menu-item>
      </a-menu>
  </div>
</template>

<script>
import {Link} from '@inertiajs/vue3'
import {
    DashboardOutlined,
    UserOutlined
} from "@ant-design/icons-vue";

export default {
  components: {
      DashboardOutlined,
      UserOutlined,
      Link
  },
    data() {
      return {

      }
    },

    computed: {
        openKeys() {
          return []
        },
        selectedKeys() {
            if(this.currentRoute('dashboard')) {
                return ['dashboard']
            } else if(this.currentRoute('customers.index')) {
                return ['customer']
            } else if(this.currentRoute('health-centers.index')) {
                return ['health-center']
            } else {
                return []
            }
        }
    },
  methods: {
      currentRoute(routeName) {
         return routeName === this.$page.props.current_route
      },
      getCurrentUrlSegment() {
          const currentUrl = window.location.pathname;
          const segments = currentUrl.split('/');
          return segments[segments.length - 1];
      }
  },
}
</script>
<style>
.ant-menu-item-active:hover, .ant-menu-submenu-active:hover{
    background-color: #f1f1f142 !important; /* Use the 'gold' color */
    /* Add any other styles you want for the hover effect */
}
.ant-menu-item-selected {
    background-color: #f1f1f142 !important;
}
.ant-menu-submenu-title:active{
    background-color:  transparent !important;
}
</style>

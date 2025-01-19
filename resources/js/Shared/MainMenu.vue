<template>
  <div>
    <n-list :show-divider="false" hoverable class="mt-3" style="background-color: #222d32">
      <n-list-item class="text-white"><Link :href="route('dashboard')"> <i class="fa fa-home"></i> Dashbaord </Link></n-list-item>
      <n-list-item class="text-white"><Link :href="route('customers')"> <i class="fa fa-user"></i> Customer </Link></n-list-item>
    </n-list>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'

export default {
  components: {
    Icon,
    Link,
  },
  data: () => ({
    menuOptions: [
      {
        key: 'dashboard',
        label: 'Dashboard',
      },
      {
        key: 'services',
        label: 'Services',
        children: [
          {
            key: 'web-development',
            label: 'Web Development',
          },
          {
            key: 'mobile-development',
            label: 'Mobile Development',
          },
        ],
      },
      {
        key: 'products',
        label: 'Products',
        children: [
          {
            key: 'software',
            label: 'Software',
          },
          {
            key: 'hardware',
            label: 'Hardware',
          },
        ],
      },
      {
        key: 'about-us',
        label: 'About Us',
      },
    ],
  }),
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length
    },
  },
}
</script>
<style scoped>
.n-list.n-list--hoverable .n-list-item:hover {
  background-color: #f1f1f142 !important
}
</style>

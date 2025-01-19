<template>
  <div>
    <Head title="Customers" />
    <div class="flex flex-row items-center">
      <h1 class="mb-4 text-3xl font-bold mr-3">Customers</h1>
      <n-breadcrumb separator="/">
        <n-breadcrumb-item> Dashbaord </n-breadcrumb-item>
        <n-breadcrumb-item> Customers </n-breadcrumb-item>
      </n-breadcrumb>
    </div>

    <n-card>
      <n-data-table :columns="columns" :data="users" bordered />
    </n-card>
  </div>
</template>
<script>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'

export default {
  components: {
    Head,
  },
  props: ['users'],
  layout: Layout,

  data() {
    return {
      columns: [
        {
          title: 'First Name',
          key: 'first_name',
        },
        {
          title: 'Last Name',
          key: 'last_name',
        },
        {
          title: 'Email',
          key: 'email',
        },
      ],
      data: [],
    }
  },
  mounted() {
    console.log(this.users)
    // this.users.map((item) => {
    //   console.log('item')
    //   console.log(item)
    // })
  },
}
</script>

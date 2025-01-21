<template>
  <div>
    <Head title="Dashboard" />
    <div class="flex items-center justify-between mb-4">
      <h1 class="mb-4 text-3xl font-bold mr-3">Dashboard</h1>
    </div>
    <div class="grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 grid gap-4 my-5">
      <Link :href="route('customers.index')">
        <div class="flex items-center p-5 rounded-md shadow text-black hover:shadow-lg flex-1 border cursor-pointer bg-white">
          <div class="flex-1">
            <p class="text-2xl font-bold mb-0">{{ customers }}</p>
            <p class="text-sm tracking-wide mb-0 text-gray-500">Customers</p>
          </div>
          <div>
            <a-avatar :size="50" style="background-color: rgba(115, 103, 240, 0.12) !important">
              <template #icon>
                <i class="fa-solid fa-users fa-1x" style="color: #7367f0; font-size: 22px"></i>
              </template>
            </a-avatar>
          </div>
        </div>
      </Link>
      <Link :href="route('health-centers.index')">
        <div class="flex items-center p-5 rounded-md shadow text-black hover:shadow-lg flex-1 border cursor-pointer bg-white">
          <div class="flex-1">
            <p class="text-2xl font-bold mb-0">{{ healthCenters }}</p>
            <p class="text-sm tracking-wide mb-0 text-gray-500">Health Centers</p>
          </div>
          <div>
            <a-avatar :size="50" style="background-color: rgba(115, 103, 240, 0.12) !important">
              <template #icon>
                <i class="fa-regular fa-hospital fa-1x" style="color: #7367f0; font-size: 22px"></i>
              </template>
            </a-avatar>
          </div>
        </div>
      </Link>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'

export default {
  components: {
    Head,
    Link,
  },
  layout: Layout,
  props: {
    customers: {
      type: Number,
      default: 0
    },
     healthCenters: {
      type: Number,
      default: 0
    }
  }
}
</script>

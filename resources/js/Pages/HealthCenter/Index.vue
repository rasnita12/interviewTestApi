<template>
  <Head title="Home" />
  <div class="container mx-auto">
    <h1 class="text-center text-lg md:text-3xl font-bold py-4 md:py-8">Select Your Health Center</h1>
    <a-dropdown :arrow="true" class="pl-8">
      <a class="ant-dropdown-link cursor-pointer text-sm md:text-base" @click.prevent>
        {{ $page?.props.auth.user.first_name + ' ' + $page?.props.auth.user.last_name }}
        <i class="fa fa-chevron-down"></i>
      </a>
      <template #overlay>
        <a-menu class="pl-8">
          <a-menu-item>
            <Link :href="this.route('logout')" method="delete" as="button">
              <LogoutOutlined />
              Logout
            </Link>
          </a-menu-item>
        </a-menu>
      </template>
    </a-dropdown>
    <div class="flex items-center justify-center min-h-screen rounded-lg p-4 md:p-8 md:mx-0">
      <div class="flex flex-wrap md:flex-nowrap bg-white shadow-xl overflow-hidden rounded-lg w-full">
        <!-- Left Side: Login Form -->
        <div class="w-full px-3 py-8 md:py-10 md:px-10">
          <FormInput :postcode="postcode" />
          <div class="mt-5">
            <a-alert v-if="healthCenters.length === 0" message="Health centers are avaialble for the given address." type="warning" show-icon />
            <ul class="h-[82vh] overflow-y-auto custom-scrollbar">
              <li v-for="(item, index) in healthCenters" :key="index" class="border p-4 border-gray-200 rounded-md shadow-sm mb-4 bg-white flex justify-between items-center">
                <div>
                  <p class="text-base md:text-lg font-semibold">{{ item.name }}</p>
                  <span class="text-sm md:text-base font-medium text-gray-700">{{ item.line_1 }}, {{ item.postcode }}, {{ item.city }}</span>
                  <p class="mt-1 text-sm md:text-base">Distance: {{ item.distance }} mile</p>
                </div>
                <div>
                  <a-button class="!rounded-md" :style="{ backgroundColor: 'orange', borderRadius: '0' }" type="primary" size="middle" html-type="submit">Select</a-button>
                </div>
              </li>
            </ul>
          </div>
        </div>

        <!-- Right Side: Image -->
        <GoogleMap api-key="AIzaSyCi327LFl7byyspi_v7snyRjEVQPptUBPc" mapId="30a0d969799b3759" style="width: 100%; height: 100vh" :center="center" :zoom="12">
          <AdvancedMarker v-for="(item, index) in healthCenters" :key="index" :options="{ position: { lat: parseFloat(item.latitude), lng: parseFloat(item.longitude) }, title: item.name }" />
        </GoogleMap>
      </div>
    </div>
  </div>
</template>
<script>
import { Head, Link } from '@inertiajs/vue3'
import { GoogleMap, AdvancedMarker } from 'vue3-google-map'
import TextInput from '../../Shared/TextInput.vue'
import { LogoutOutlined } from '@ant-design/icons-vue'
import FormInput from './Form.vue'

export default {
  components: {
    Head,
    GoogleMap,
    AdvancedMarker,
    TextInput,
    Link,
    LogoutOutlined,
    FormInput,
  },
  props: {
    healthCenters: {
      type: Array,
      default: [],
    },
    postcode: {
      type: String,
      default: null,
    },
    center: {
      type: Object,
      default: {},
    },
  },
  mounted() {},
  methods: {},
}
</script>

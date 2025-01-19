<template>
  <Head title="Home" />
  <div class="container mx-auto">
    <h1 class="text-center text-3xl font-bold py-8">Select Your Health Center</h1>
    <a-dropdown :arrow="true">
          <a class="ant-dropdown-link" @click.prevent>
            {{ $page?.props.auth.user.first_name + ' ' + $page?.props.auth.user.last_name }}
            <i class="fa fa-chevron-down"></i>
          </a>
          <template #overlay>
            <a-menu>
              <a-menu-item>
                <Link :href="this.route('logout')" method="delete" as="button">
                  <LogoutOutlined />
                  Logout
                </Link>
              </a-menu-item>
            </a-menu>
          </template>
        </a-dropdown>
    <div class="flex items-center justify-center min-h-screen rounded-lg mx-3 p-8 md:mx-0">
      <div class="flex bg-white shadow-xl overflow-hidden rounded-lg w-full">
        <!-- Left Side: Login Form -->
        <div class="w-full px-3 py-8 md:py-10 md:px-10">
          <a-form layout="inline" ref="form" :model="form" @finish="submit">
            <text-input class="!w-[85%]" name="postcode" size="large" v-model="form.postcode" :rules="[{ required: true, message: 'The postcode field is required.' }]" label="Postcode" placeholder="Enter Postcode" />

            <a-form-item>
              <a-button :style="{ backgroundColor: 'orange', borderRadius: '0' }" type="primary" size="large" html-type="submit">Submit</a-button>
            </a-form-item>
          </a-form>
          <div class="mt-5">
            <ul class="h-[82vh] overflow-y-auto custom-scrollbar">
              <li v-for="(item, index) in healthCenters" :key="index" class="border p-4 border-gray-200 rounded-md shadow-sm mb-4 bg-white">
                <div>
                  <p class="text-lg font-semibold">{{ item.name }}</p>
                  <span class="font-medium text-gray-700">{{ item.vicinity }}</span>
                  <p class="mt-1">Distance: {{ item.distance }} mile</p>
                </div>
              </li>
            </ul>
          </div>
        </div>

        <!-- Right Side: Image -->
        <GoogleMap api-key="AIzaSyCi327LFl7byyspi_v7snyRjEVQPptUBPc" mapId="30a0d969799b3759" style="width: 100%; height: 100vh" :center="center" :zoom="13">
          <AdvancedMarker v-for="(item, index) in healthCenters" :key="index" :options="{ position: { lat: item.geometry.location.lat, lng: item.geometry.location.lng }, title: item.name }" />
        </GoogleMap>
      </div>
    </div>
  </div>
</template>
<script>
import { Head, Link } from '@inertiajs/vue3'
import { GoogleMap, AdvancedMarker } from 'vue3-google-map'
import TextInput from '../../Shared/TextInput.vue'


export default {
  components: {
    Head,
    GoogleMap,
    AdvancedMarker,
    TextInput,
    Link
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
  data() {
    return {
      form: this.$inertia.form({
        postcode: '',
      }),
    }
  },
  mounted() {
    this.form.postcode = this.postcode
  },
  methods: {
    onMarkerClick(marker) {
      alert(`You clicked on ${marker.title}`)
    },
    submit() {
      this.$refs.form
        .validateFields()
        .then(() => {
          this.$inertia.get('/health-centers', {
            postcode: this.form.postcode,
          })
        })
        .catch((errorInfo) => {
          console.log(errorInfo)
        })
    },
  },
}
</script>

<template>
  <Head title="Home" />
  <div class="container mx-auto">
  <h1 class="text-center text-3xl font-bold py-8">Select Your Health Center</h1>
  <div class="flex items-center justify-center min-h-screen rounded-lg mx-3 p-8 md:mx-0">
    <div class="flex bg-white shadow-xl overflow-hidden rounded-lg w-full">

      <!-- Left Side: Login Form -->
      <div class="w-full px-3 py-8 md:py-10 md:px-10">
        <n-form :model="form" ref="formRef" inline @submit.prevent="submit">
                <n-form-item
                 label="Postcode"
                  path="postcode"
                  class="!w-[80%]"
                  :rule="[{
                    required: true,
                    message: 'Postcode field is required.',
                    trigger: ['input', 'blur'],
                  }]"
                >
                  <n-input v-model:value="form.postcode" size="large"/>
                </n-form-item>
                <n-form-item>
                  <n-button attr-type="submit" type="warning" size="large">
                    Submit
                  </n-button>
                </n-form-item>
        </n-form>
        <div class="mt-2">
          <ul class="h-[82vh] overflow-y-auto custom-scrollbar">
            <li v-for="(item,index) in healthCenters" :key="index" class="border p-4 border-gray-200 rounded-md shadow-sm mb-4 bg-white">
              <div>
                <!-- <pre>{{ item }}</pre> -->
                <p class="text-lg font-semibold">{{ item.name }}</p>
                <span class="font-medium text-gray-700">{{item.vicinity}}</span>
                <p class="mt-1">Distance: {{ item.distance }} mile</p>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <!-- Right Side: Image -->
      <GoogleMap
        api-key="AIzaSyCi327LFl7byyspi_v7snyRjEVQPptUBPc"
        mapId="30a0d969799b3759"
        style="width: 100%; height: 100vh"
        :center="center"
        :zoom="13"
      >
        <AdvancedMarker v-for="(item,index) in healthCenters" :key="index" :options="{ position: {lat:item.geometry.location.lat, lng:item.geometry.location.lng}, title:item.name }" />
      </GoogleMap>
    </div>
    </div>
  </div>
</template>
<script>
import { Head } from '@inertiajs/vue3'
import { GoogleMap, AdvancedMarker } from 'vue3-google-map'

export default {
  components: {
    Head,
    GoogleMap,
    AdvancedMarker,
  },
  props:{
    healthCenters: {
      type: Array,
      default: []
    },
    postcode: {
      type: String,
      default: null
    },
    center: {
      type: Object,
      default: {}
    }
  },
  data(){
    return{
      form:this.$inertia.form({
        postcode: ''
      })
    }
  },
  mounted(){
    this.form.postcode = this.postcode
  },
  methods: {
    onMarkerClick(marker) {
      alert(`You clicked on ${marker.title}`);
    },
    submit() {
      this.$refs.formRef.validate((errors) => {
        if (!errors) {
          this.$inertia.get('/health-centers', {
            postcode: this.form.postcode
          })
        }
      });
    }
  }
}
</script>

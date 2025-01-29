<template>
  <Head title="Home" />
  <div class="min-h-screen bg-white">
    <div class="">
      <div class="container mx-auto w-full p-10 flex justify-between items-center">
        <img src="../../../images/SH_Logo_B.png" alt="image" style="width: 235px" />

        <!-- <h1 class="font-bold text-lg md:text-4xl !text-center mb-0 md:mb-4">SIGMA HEALTH</h1> -->
        <a-dropdown :arrow="true" class="text-sm md:text-base">
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
      </div>

      <div class="flex flex-col justify-center items-center mt-3 md:mt-0">
        <div class="w-full max-w-4xl">
          <a-form :model="form" ref="form" class="bg-white" autocomplete="off" layout="vertical" @finish="submit">
            <div class="border border-2 border-gray-200 rounded-lg p-4">
              <h1 class="text-center text-lg md:text-3xl font-bold">Enter your postcode</h1>
              <p class="text-center text-sm font-medium mt-3">Your postcode helps us to show the local health centers near you!</p>

              <a-alert v-if="$page?.props?.flash?.error ?? null" class="my-3" message="Something went wrong." :description="$page?.props?.flash?.error ?? ''" type="error" show-icon />

              <input type="text" ref="addressInput" v-model="search" @focus="initAutocomplete" placeholder="Enter an address" required class="address-input w-full border border-gray-300 rounded-md p-3 focus:border-blue-300 focus:ring-0 focus:outline-none" />
              <div class="mt-4">
                <a-button :style="{ borderRadius: '0' }" block type="primary" size="large" html-type="submit" :loading="form.processing" style="background-color:#1f2835">Find</a-button>
              </div>
            </div>
          </a-form>
        </div>
      </div>
    </div>
  </div>
  <div class="hidden">
    <GoogleMap :api-key="setting.map_api_key" :mapId="setting.map_id" style="width: 100%; height: 100vh"> </GoogleMap>
  </div>
</template>
<script>
import { Head, Link } from '@inertiajs/vue3'
import { LogoutOutlined } from '@ant-design/icons-vue'
import TextInput from '../../Shared/TextInput.vue'
import { GoogleMap } from 'vue3-google-map'

export default {
  components: {
    Head,
    LogoutOutlined,
    Link,
    TextInput,
    GoogleMap,
  },
  props: {
    setting: {
      type: Object,
      default: {},
    },
  },
  data() {
    return {
      form: this.$inertia.form({
        postcode: '',
      }),
      search: '', // User's input
      autocomplete: null, // Google Autocomplete object
    }
  },

  mounted() {},
  methods: {
    initAutocomplete() {
      if (!this.autocomplete) {
        // Create the autocomplete object
        const input = this.$refs.addressInput // Get the input element
        this.autocomplete = new google.maps.places.Autocomplete(input, {
          types: ['geocode'], // Restrict to address suggestions
          componentRestrictions: { country: 'uk' }, // Restrict to UK
        })

        // Add a listener for when the user selects a place
        this.autocomplete.addListener('place_changed', this.onPlaceChanged)
      }
    },
    onPlaceChanged() {
      const place = this.autocomplete.getPlace()
      if (place && place.geometry && place.geometry.location) {
        // Retrieve latitude and longitude
        const lat = place.geometry.location.lat()
        const lng = place.geometry.location.lng()

        // Update the search field with the formatted address
        this.search = place.formatted_address
      } else {
        console.error('No details available for the selected place.')
      }
    },

    submit() {
      this.$refs.form
        .validateFields()
        .then(() => {
          this.$inertia.get(route('get.address'), {
            postcode: this.search,
          })
        })
        .catch((errorInfo) => {
          console.log(errorInfo)
        })
    },
    handleSelect() {
      this.$inertia.visit('/logout')
    },
  },
}
</script>

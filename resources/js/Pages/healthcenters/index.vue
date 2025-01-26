<template>
  <div class="flex flex-col items-center justify-center min-h-screen">
    <div class="bg-white p-8 border border-2 rounded-md">
      <p class="text-black">Address</p>
      <input type="text" class="border border-2 border-grey-400 rounded-md py-2" @focus="initializeAutocomplete" ref="addressInput" />
    </div>
    <div class="hidden">
      <GoogleMap api-key="AIzaSyBiE4Gzfy4RyVK7U_gQui7uFMt0FfA3W30" style="width: 100%; height: 500px" :center="center" :zoom="15">
        <Marker :options="{ position: center }" />
      </GoogleMap>
    </div>
  </div>
</template>
<script>
import { GoogleMap, AdvancedMarker } from 'vue3-google-map'
export default {
  components:{
    GoogleMap, AdvancedMarker
  },
  data() {
    return {
      autocomple: null,
    }
  },
  methods: {
    initializeAutocomplete() {
      if (!this.autocomplete) {
        const inputElement = this.$refs.addressInput

        // Initialize Google Places Autocomplete
        this.autocomplete = new google.maps.places.Autocomplete(inputElement, {
          types: ['address'], // Restrict to address type
          componentRestrictions: { country: 'uk' }, // Adjust as needed
        })

        // Add event listener for place selection
        this.autocomplete.addListener('place_changed', this.handlePlaceChanged)
      }
    },

    handlePlaceChanged() {
      const place = this.autocomplete.getPlace()

      if (place) {
        // Emit the place details to parent component
        this.$emit('place-selected', {
          address: place.formatted_address,
          coordinates: place.geometry.location.toJSON(),
        })
      }
    },
  },
}
</script>

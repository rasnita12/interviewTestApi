<template>
  <a-form layout="inline" ref="form" :model="form" @finish="submit">
    <input type="text" ref="addressInput" v-model="search" @focus="initAutocomplete" placeholder="Enter an address" required class="address-input !w-[80%] border border-gray-300 rounded-md p-3 focus:border-blue-300 focus:ring-0 focus:outline-none" />

    <a-form-item class="mt-3 md:mt-0">
      <a-button class="!rounded-md !ml-2 h-[50] md:h-[50px]" :style="{ borderRadius: '0' }" type="primary" size="small md:large" style="background-color:#1f2835" html-type="submit">Submit</a-button>
    </a-form-item>
  </a-form>
</template>
<script>

export default{
  name: 'FormInput',
  props: {
    postcode: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      form: this.$inertia.form({
        postcode: '',
      }),
      search: this.postcode
    }
  },
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
      const place = this.autocomplete.getPlace();
      if (place && place.geometry && place.geometry.location) {
        // Retrieve latitude and longitude
        const lat = place.geometry.location.lat();
        const lng = place.geometry.location.lng();

        // Update the search field with the formatted address
        this.search = place.formatted_address;
      } else {
        console.error("No details available for the selected place.");
      }
    },
    onMarkerClick(marker) {
      alert(`You clicked on ${marker.title}`)
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
  }
}
</script>


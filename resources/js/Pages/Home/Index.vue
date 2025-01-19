<template>
  <Head title="Home" />
  <div class="min-h-screen bg-white">
    <div class="container mx-auto w-full max-w-4xl p-10">
      <div class="flex justify-between">
        <h1 class="font-bold text-4xl !text-center mb-4">SIGMA HEALTH</h1>
        <n-dropdown class="!float-right" trigger="hover" :show-arrow="true" :options="options" @select="handleSelect" >
          <n-button text>{{$page?.props.auth.user.first_name +' '+$page?.props.auth.user.last_name}} <i class="fa fa-chevron-down ml-2"></i>
          </n-button>

        </n-dropdown>
      </div>
      <div class="flex flex-col justify-center items-center">
        <div class="w-full max-w-4xl ">
          <n-form :model="form" ref="formRef" class="bg-white" @submit.prevent="submit">
            <div class="border border-2 border-gray-200 rounded-lg p-4">
              <h1 class="text-center text-3xl font-bold">Enter your postcode</h1>
              <p class="text-center text-sm font-medium mt-3">Your postcode helps us to show the local health centers near you!</p>

              <n-alert v-if="($page?.props?.flash?.error ?? null )" class="my-3" title="Something went wrong." type="error">
                {{ $page?.props?.flash?.error ?? '' }}
              </n-alert>

              <n-form-item
                label="Postcode"
                path="postcode"
                :rule="[{
                  required: true,
                  message: 'Postcode field is required.',
                  trigger: ['input', 'blur'],
                }]"
              >
                <n-input size="large" v-model:value="form.postcode" type="text" placeholder="Enter your postcode" />
              </n-form-item>
              <div class="mt-2">
                <n-button type="warning" size="large" block :loading="form.processing" attr-type="submit">
                  <span class="text-xl">Find</span>
                </n-button>
              </div>
            </div>
          </n-form>

        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { Head } from '@inertiajs/vue3'
export default {
  components: {
    Head,
  },
  data(){
    return{
      form:this.$inertia.form({
        postcode: ''
      }),

      options: [
        {
          label: 'Logout',
          key: "logout",
        }
      ]
    }
  },

  mounted(){

  },
  methods: {
    submit() {
      this.$refs.formRef.validate((errors) => {
        if (!errors) {
          this.$inertia.get('/health-centers', {
            postcode: this.form.postcode
          })
        }
      });
    },
    handleSelect()
    {
      this.$inertia.visit('/logout');
    }
  }
}
</script>

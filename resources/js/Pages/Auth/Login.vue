<template>
  <Head title="Login" />
  <div class="flex items-center justify-center min-h-screen bg-gray-200 rounded-lg mx-3 md:mx-0">
    <div class="flex bg-white shadow-xl overflow-hidden rounded-lg max-w-4xl w-full">

      <!-- Left Side: Login Form -->
      <div class="w-full md:w-1/2 px-3 py-8 md:py-10 md:px-10">
        <n-form :model="form" ref="formRef" class="bg-white" @submit.prevent="login">
          <div>
            <h1 class="text-center text-3xl font-bold">Welcome!</h1>
            <p class="text-center text-sm font-medium mt-3">Login below to manage your account</p>

            <!-- Input -->
            <div class="mt-6">
              <n-form-item
                label="Email"
                path="email"
                :rule="[{
                  required: true,
                  message: 'Email field is required.',
                  trigger: ['input', 'blur'],
                }]"
              >
                <n-input v-model:value="form.email" type="text" placeholder="Enter your email" />
              </n-form-item>
              <n-form-item
                label="Password"
                path="password"
                :rule="[{
                  required: true,
                  message: 'Password field is required.',
                  trigger: ['input', 'blur'],
                }]"
              >
                <n-input v-model:value="form.password" type="password" placeholder="Enter your password" />
              </n-form-item>
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
              <n-button type="warning" block :loading="form.processing" attr-type="submit">
                Submit
              </n-button>
            </div>

            <!-- Register Link -->
            <p class="text-center text-sm font-medium mt-3">
              Don't have an account?
              <a class="cursor-pointer text-blue-700 underline" href="/register">Register</a>
            </p>
          </div>
        </n-form>
      </div>

      <!-- Right Side: Image -->
      <div class="hidden md:flex w-1/2 items-center justify-center bg-gray-100">
        <img src="../../../images/sideimage.png" alt="image" class="h-full mx-auto"/>
      </div>

    </div>
  </div>

</template>

<script>
import { Head } from '@inertiajs/vue3'
import Logo from '@/Shared/Logo.vue'
import TextInput from '@/Shared/TextInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: {
    Head,
    LoadingButton,
    Logo,
    TextInput,
  },
  data() {
    return {
      form: this.$inertia.form({
        email: '',
        password: '',
        remember: false,
      }),
    }
  },
  methods: {
    login() {
      this.$refs.formRef.validate((errors) => {
        if (!errors) {
          this.form.post('/login')
        }
      });

    },
  },
}
</script>

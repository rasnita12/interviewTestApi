<template>
  <Head title="Login" />
  <div class="flex items-center justify-center min-h-screen bg-gray-200 rounded-lg mx-3 md:mx-0">
    <div class="flex bg-white shadow-xl overflow-hidden rounded-lg max-w-4xl w-full">

      <!-- Left Side: Login Form -->
      <div class="w-full md:w-1/2 px-3 py-8 md:py-10 md:px-10">
        <n-form :model="form" ref="formRef" class="bg-white" @submit.prevent="register">
          <div>
            <h1 class="text-center text-3xl font-bold">Welcome!</h1>
            <p class="text-center text-sm font-medium mt-3">Register below to create your account</p>
            <n-alert v-if="Object.keys(form.errors).length > 0" title="Error" type="error" class="mt-4">
              <ul class="!mb-0">
                  <li v-for="(error, field) in form.errors" :key="field">{{ error }}</li>
              </ul>
            </n-alert>
            <!-- First Name Input -->
            <div class="mt-4">
              <n-form-item
                label="First Name"
                path="first_name"
                :rule="[{
                  required: true,
                  message: 'First Name field is required.',
                  trigger: ['input', 'blur'],
                }]"
              >
                <n-input v-model:value="form.first_name" type="text" placeholder="Enter your first name" />
              </n-form-item>
            </div>

            <!-- Last Name Input -->
            <div>
              <n-form-item
                label="Last Name"
                path="last_name"

                :rule="[{
                  required: true,
                  message: 'Last Name field is required.',
                  trigger: ['input', 'blur'],
                }]"

              >
                <n-input v-model:value="form.last_name" type="text" placeholder="Enter your last name" />
              </n-form-item>
            </div>

            <!-- Email Input -->
            <div>
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
            </div>

            <!-- Password Input -->
            <div>
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

            <!-- Password Confirmation Input -->
            <div>
              <n-form-item
                label="Password Confirmation"
                path="password"
                :rule="[{
                  required: true,
                  message: 'Password Confirmation field is required.',
                  trigger: ['input', 'blur'],
                }]"
              >
                <n-input v-model:value="form.password_confirmation" type="password" placeholder="Enter your password" />
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
              Do you have an account?
              <a class="cursor-pointer text-blue-700 underline" href="/login">Login</a>
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
        password_confirmation: '',
        password: '',
        first_name: '',
        last_name: '',
        remember: false,
      }),
    }
  },
  methods: {
    register() {
      this.$refs.formRef.validate((errors) => {
        if (!errors) {
          this.form.post('/register')
        }
      });

    },
  },
}
</script>

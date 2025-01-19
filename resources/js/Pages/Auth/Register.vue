<template>
  <Head title="Login" />
  <div class="flex items-center justify-center min-h-screen bg-gray-200 rounded-lg mx-3 md:mx-0">
    <div class="flex bg-white shadow-xl overflow-hidden rounded-lg max-w-6xl w-full">
      <!-- Left Side: Login Form -->
      <div class="w-full md:w-1/2 px-3 py-8 md:py-10 md:px-10">
        <a-form :model="form" ref="form" class="bg-white" autocomplete="off" layout="vertical" @finish="register">
          <div>
            <h1 class="text-center text-3xl font-bold">Sigma Health</h1>
            <p class="text-center text-sm font-medium mt-3">Register below to create your account</p>
            <a-alert v-if="Object.keys(form.errors).length > 0" class="!mb-3" type="error" show-icon>
              <template #description>
                <ul class="!mb-0">
                  <li v-for="(error, field) in form.errors" :key="field">{{ error }}</li>
                </ul>
              </template>
            </a-alert>
            <a-row class="mt-8" :gutter="{ xs: 8, sm: 16, md: 24, lg: 32 }">
              <!-- First Name Input -->
              <a-col class="gutter-row" :span="24" :md="{ span: 12 }">
                <text-input name="first_name" v-model="form.first_name" :rules="[{ required: true, message: 'First name field is required.' }]" label="First Name" placeholder="Enter First Name" />
              </a-col>
              <!-- Last Name Input -->
              <a-col class="gutter-row" :span="24" :md="{ span: 12 }">
                <text-input name="last_name" v-model="form.last_name" :rules="[{ required: true, message: 'Last name field is required.' }]" label="Last Name" placeholder="Enter Last Name" />
              </a-col>
              <!-- Email Input -->
              <a-col class="gutter-row" :span="24">
                <text-input name="email" v-model="form.email" :rules="[{ required: true, message: 'Email field is required.' }]" type="email" label="Email" placeholder="Enter Email" />
              </a-col>
              <!-- Password Input -->
              <a-col class="gutter-row" :span="24" :md="{ span: 12 }">
                <password-input name="password" v-model="form.password" placeholder="Enter Password" label="Password" :rules="[{ required: true, message: 'Password field is required!' }]" />
              </a-col>
              <a-col class="gutter-row" :span="24" :md="{ span: 12 }">
                <password-input name="password_confirmation" v-model="form.password_confirmation" :rules="[{ required: true, message: 'Re-type password is required' }]" label="Re-Type Password" placeholder="Enter Re-Type Password" />
              </a-col>
            </a-row>
            <!-- Submit Button -->
            <div class="mt-3">
              <a-button :style="{ backgroundColor: 'orange', borderRadius: '0' }" block type="primary" size="large" html-type="submit" :loading="form.processing">Register</a-button>
            </div>

            <!-- Register Link -->
            <p class="text-center text-sm font-medium mt-3">
              Do you have an account?
              <Link class="cursor-pointer text-blue-700 underline" :href="route('login')">Login</Link>
            </p>
          </div>
        </a-form>
      </div>

      <!-- Right Side: Image -->
      <div class="hidden md:flex w-1/2 items-center justify-center bg-gray-100">
        <img src="../../../images/sideimage.png" alt="image" class="h-full mx-auto" />
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import TextInput from '../../Shared/TextInput.vue'
import PasswordInput from '../../Shared/PasswordInput.vue'

export default {
  components: {
    Head,
    TextInput,
    Link,
    PasswordInput,
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
      this.$refs.form
        .validateFields()
        .then(() => {
          this.form.post(this.route('register.store'))
        })
        .catch((errorInfo) => {
          console.log(errorInfo)
        })
    },
  },
}
</script>

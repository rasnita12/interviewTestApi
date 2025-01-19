<template>
  <Head title="Login" />
  <div class="flex items-center justify-center min-h-screen bg-gray-200 rounded-lg mx-3 md:mx-0">
    <div class="flex bg-white shadow-xl overflow-hidden rounded-lg max-w-4xl w-full">
      <!-- Left Side: Login Form -->
      <div class="w-full md:w-1/2 px-3 py-8 md:py-10 md:px-10">
        <a-form :model="form" ref="form" class="bg-white" autocomplete="off" layout="vertical" @finish="login">
          <div>
            <h1 class="text-center text-3xl font-bold">Sigma Health</h1>
            <p class="text-center text-sm font-medium mt-3">Login below to manage your account</p>

            <a-alert v-if="Object.keys(form.errors).length > 0" class="!mb-3" type="error" show-icon>
              <template #description>
                <ul class="!mb-0">
                  <li v-for="(error, field) in form.errors" :key="field">{{ error }}</li>
                </ul>
              </template>
            </a-alert>
            <!-- Input -->
            <div class="mt-6">
              <text-input name="email" v-model="form.email" class="mt-10" :rules="[{ required: true, message: 'The email field is required.' }]" label="Email" type="email" placeholder="Enter Email" />

              <password-input name="password" v-model="form.password" placeholder="Enter Password" label="Password" :rules="[{ required: true, message: 'The password field is required!' }]" class="mt-5" />
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
              <a-button :style="{ backgroundColor: 'orange', borderRadius: '0' }" class="mt-4" block type="primary" size="large" html-type="submit" :loading="form.processing">Login</a-button>
            </div>

            <!-- Register Link -->
            <p class="text-center text-sm font-medium mt-3">
              Don't have an account?
              <Link class="cursor-pointer text-blue-700 underline" :href="route('register')">Register</Link>
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
import Logo from '@/Shared/Logo.vue'
import TextInput from '@/Shared/TextInput.vue'
import PasswordInput from '@/Shared/PasswordInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: {
    Head,
    LoadingButton,
    Logo,
    TextInput,
    PasswordInput,
    Link,
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
      this.$refs.form
        .validateFields()
        .then(() => {
          this.form.post(this.route('login.store'))
        })
        .catch((errorInfo) => {
          console.log(errorInfo)
        })
    },
  },
}
</script>

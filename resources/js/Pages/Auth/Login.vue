<template>
  <Head title="Login" />
  <div class="flex items-center justify-center min-h-screen bg-gray-200 rounded-lg mx-3 md:mx-0" style="background-color:#7fb1d3">
    <div class="flex bg-white shadow-xl overflow-hidden rounded-lg max-w-4xl w-full">
      <!-- Left Side: Login Form -->
      <div class="w-full md:w-1/2 px-3 py-8 md:py-10 md:px-10">
        <a-form :model="form" ref="form" class="bg-white" autocomplete="off" layout="vertical" @finish="login">
          <div>
            <img src="../../../images/SH_Logo_B.png" alt="image" class="mx-auto" style="width:235px"/>
            <!-- <h1 class="text-center text-3xl font-bold">Sigma Health</h1> -->
            <p class="text-center text-sm font-medium">Login below to manage your account</p>

            <a-alert v-if="successMessage" :message="successMessage" type="success" show-icon />

            <a-alert v-if="Object.keys(form.errors).length > 0" class="!mb-3" type="error" show-icon>
              <template #description>
                <ul class="!mb-0">
                  <li v-for="(error, field) in form.errors" :key="field">{{ error }}</li>
                </ul>
              </template>
            </a-alert>
            <!-- Input -->
            <div class="mt-6">
              <text-input name="username" v-model="form.username" class="mt-10" :rules="[{ required: true, message: 'The username field is required.' }]" label="Username" placeholder="Enter Username" />

              <password-input name="password" v-model="form.password" placeholder="Enter Password" label="Password" :rules="[{ required: true, message: 'The password field is required!' }]" class="mt-5" />

              <a-checkbox v-model:checked="form.remember">Remember Me</a-checkbox>

            </div>

            <!-- Submit Button -->
            <div class="mt-6">
              <a-button :style="{borderRadius: '0' }" class="mt-4" style="background-color:#1f2835" block type="primary" size="large" html-type="submit" :loading="form.processing">Login</a-button>
            </div>

            <!-- forgot password -->
            <p class="text-center text-sm font-medium mt-3">
              Don't remember a password?
              <Link class="cursor-pointer text-blue-700 underline" :href="route('forgot-password')">Reset Password</Link>
            </p>

            <!-- Register Link -->
            <p class="text-center text-sm font-medium mt-3">
              Don't have an account?
              <Link class="cursor-pointer text-blue-700 underline" :href="route('register')">Register</Link>
            </p>
          </div>
        </a-form>
      </div>

      <!-- Right Side: Image -->
      <div class="hidden md:flex w-1/2 items-center justify-center bg-white">
        <img src="../../../images/image-right.png" alt="image" class="mx-auto" />
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
        username: '',
        password: '',
        remember: false,
      }),
      successMessage: null,
    }
  },
  mounted() {
    this.successMessage = this.$page?.props?.flash?.success ?? null

    setTimeout(() => {
        this.successMessage = null;
    }, 10000);
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

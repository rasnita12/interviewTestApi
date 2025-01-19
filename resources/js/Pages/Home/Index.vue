<template>
  <Head title="Home" />
  <div class="min-h-screen bg-white">
    <div class="container mx-auto w-full max-w-4xl p-10">
      <div class="flex justify-between items-center">
        <h1 class="font-bold text-4xl !text-center mb-4">SIGMA HEALTH</h1>
        <a-dropdown :arrow="true">
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
      <div class="flex flex-col justify-center items-center">
        <div class="w-full max-w-4xl">
          <a-form :model="form" ref="form" class="bg-white" autocomplete="off" layout="vertical" @finish="submit">
            <div class="border border-2 border-gray-200 rounded-lg p-4">
              <h1 class="text-center text-3xl font-bold">Enter your postcode</h1>
              <p class="text-center text-sm font-medium mt-3">Your postcode helps us to show the local health centers near you!</p>

              <a-alert v-if="$page?.props?.flash?.error ?? null" class="my-3" message="Something went wrong." :description="$page?.props?.flash?.error ?? ''" type="error" show-icon />

              <text-input name="postcode" size="large" v-model="form.postcode" :rules="[{ required: true, message: 'The postcode field is required.' }]" label="Postcode" placeholder="Enter Postcode" />

              <div class="mt-2">
                <a-button :style="{ backgroundColor: 'orange', borderRadius: '0' }" block type="primary" size="large" html-type="submit" :loading="form.processing">Find</a-button>
              </div>
            </div>
          </a-form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { Head, Link } from '@inertiajs/vue3'
import { LogoutOutlined } from '@ant-design/icons-vue'
import TextInput from '../../Shared/TextInput.vue'
export default {
  components: {
    Head,
    LogoutOutlined,
    Link,
    TextInput,
  },
  data() {
    return {
      form: this.$inertia.form({
        postcode: '',
      }),
    }
  },

  mounted() {},
  methods: {
    submit() {
      this.$refs.form
        .validateFields()
        .then(() => {
          this.$inertia.get('/health-centers', {
            postcode: this.form.postcode,
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

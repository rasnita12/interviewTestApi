<template>
  <div>
    <Head title="Health Centers" />
    <div class="flex items-center justify-between">
      <div class="flex flex-row items-center">
        <h1 class="mb-4 text-3xl font-bold mr-3">Setting</h1>
        <a-breadcrumb>
          <a-breadcrumb-item><Link :href="route('dashboard')">Dashbaord</Link></a-breadcrumb-item>
          <a-breadcrumb-item>Setting</a-breadcrumb-item>
        </a-breadcrumb>
      </div>
    </div>

    <a-card :bordered="true">
      <a-alert v-if="Object.keys(form.errors).length > 0" class="!mb-3" type="error" show-icon>
        <template #description>
          <ul class="!mb-0">
            <li v-for="(error, field) in form.errors" :key="field">{{ error }}</li>
          </ul>
        </template>
      </a-alert>

      <a-form :model="form" ref="form" autocomplete="off" layout="vertical" @finish="onFinish">
        <a-row :gutter="{ xs: 8, sm: 16, md: 24, lg: 32 }">
          <a-col class="gutter-row" :span="24" :sm="{ span: 12 }">
            <text-input name="map_api_key" v-model="form.map_api_key" :rules="[{ required: true, message: 'The map api key field is required.' }]" label="Map API Key" placeholder="Enter Map API Key" />
          </a-col>

          <a-col class="gutter-row" :span="24" :sm="{ span: 12 }">
            <text-input name="map_id" v-model="form.map_id" :rules="[{ required: true, message: 'The map id field is required.' }]" label="Map ID" placeholder="Enter Map ID" />
          </a-col>
        </a-row>

        <a-button class="mt-3" key="submit" type="primary" html-type="submit" :loading="form.processing" size="large">Submit</a-button>
      </a-form>
    </a-card>
  </div>
</template>
<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import { PlusOutlined } from '@ant-design/icons-vue'
import TextInput from '../../Shared/TextInput.vue'

export default {
  components: {
    Head,
    Link,
    TextInput,
    PlusOutlined,
  },
  props: ['setting'],
  layout: Layout,

  data() {
    return {
      form: this.$inertia.form({
        map_api_key: this.setting?.map_api_key ?? null,
        map_id: this.setting?.map_id ?? null,
      }),
      submitting: false,
    }
  },

  watch: {},

  mounted() {},

  methods: {
    onFinish() {
      this.$refs.form
        .validateFields()
        .then(() => {
          this.form.post(this.route('settings.store'), {
            preserveState: true,
            onSuccess: (page) => {
              this.submitting = false
              this.$message.success('Setting Updated Successfully!')
              this.open = false
            },
            onError: (errors) => {
              this.submitting = false
            },
          })
        })
        .catch((errorInfo) => {
          console.log(errorInfo)
        })
        .finally(() => {
          this.submitting = false
        })
    },
  },
}
</script>

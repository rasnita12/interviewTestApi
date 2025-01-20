<template>
  <div>
    <Head title="Health Centers" />
    <div class="flex items-center justify-between">
      <div class="flex flex-row items-center">
        <h1 class="mb-4 text-3xl font-bold mr-3">Health Center</h1>
        <a-breadcrumb>
          <a-breadcrumb-item><Link :href="route('dashboard')">Dashbaord</Link></a-breadcrumb-item>
          <a-breadcrumb-item>Health Centers</a-breadcrumb-item>
          <a-breadcrumb-item>Create</a-breadcrumb-item>
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
        <a-card :bordered="true" title="Basic Information">
          <a-row :gutter="{ xs: 8, sm: 16, md: 24, lg: 32 }">
            <a-col class="gutter-row" :span="24" :sm="{ span: 12 }">
              <text-input name="name" v-model="form.name" :rules="[{ required: true, message: 'The name field is required.' }]" label="Name" placeholder="Enter Name" />
            </a-col>
            <a-col class="gutter-row" :span="24" :sm="{ span: 12 }">
              <text-input name="phone" v-model="form.phone" :rules="[{ required: true, message: 'The phone field is required.' }]" label="Phone" placeholder="Enter Phone Name" />
            </a-col>
          </a-row>
        </a-card>
        <a-card class="!mt-4" :bordered="true" title="Address Information">
          <a-row :gutter="{ xs: 8, sm: 16, md: 24, lg: 32 }">
            <a-col class="gutter-row" :span="24" :sm="{ span: 12 }">
              <text-input name="line_1" v-model="form.line_1" :rules="[{ required: true, message: 'The line 1 field is required.' }]" label="Line 1" placeholder="Enter Line 1" />
            </a-col>
            <a-col class="gutter-row" :span="24" :sm="{ span: 12 }">
              <text-input name="line_2" v-model="form.line_2" label="Line 2" placeholder="Enter Line 2" />
            </a-col>
            <a-col class="gutter-row" :span="24" :sm="{ span: 12 }">
              <text-input name="postcode" v-model="form.postcode" :rules="[{ required: true, message: 'The line 2 field is required.' }]" label="Postcode" placeholder="Enter Postcode" />
            </a-col>
            <a-col class="gutter-row" :span="24" :sm="{ span: 12 }">
              <text-input name="city" v-model="form.city" label="City" :rules="[{ required: true, message: 'The city field is required.' }]" placeholder="Enter City" />
            </a-col>
            <a-col class="gutter-row" :span="24" :sm="{ span: 12 }">
              <text-input name="county" v-model="form.county" label="County" :rules="[{ required: true, message: 'The county field is required.' }]" placeholder="Enter County" />
            </a-col>
            <a-col class="gutter-row" :span="24" :sm="{ span: 12 }">
              <text-input name="latitude" v-model="form.latitude" label="Latitude" :rules="[{ required: true, message: 'The latitude field is required.' }]" placeholder="Enter Latitude" />
            </a-col>
            <a-col class="gutter-row" :span="24" :sm="{ span: 12 }">
              <text-input name="longitude" v-model="form.longitude" label="Longitude" :rules="[{ required: true, message: 'The longitude field is required.' }]" placeholder="Enter Longitude" />
            </a-col>
          </a-row>
        </a-card>
        <a-button class="mt-3" key="submit" type="primary" html-type="submit" :loading="form.processing" size="large">Submit</a-button>
      </a-form>
    </a-card>
  </div>
</template>
<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import { PlusOutlined } from '@ant-design/icons-vue'
import TextInput from '../../../Shared/TextInput.vue'

export default {
  components: {
    Head,
    Link,
    TextInput,
    PlusOutlined,
  },
  layout: Layout,

  data() {
    return {
      form: this.$inertia.form({
        name: this.healthCenter?.name ?? null,
        phone: this.healthCenter?.phone ?? null,
        line_1: this.healthCenter?.line_1 ?? null,
        line_2: this.healthCenter?.line_2 ?? null,
        postcode: this.healthCenter?.postcode ?? null,
        city: this.healthCenter?.city ?? null,
        county: this.healthCenter?.county ?? null,
        latitude: this.healthCenter?.latitude ?? null,
        longitude: this.healthCenter?.longitude ?? null,
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
          this.form.post(this.route('health-centers.store'), {
            preserveState: true,
            onSuccess: (page) => {
              this.submitting = false
              this.$message.success('Health Center Added Successfully!')
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

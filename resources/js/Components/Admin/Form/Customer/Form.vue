<script>
import axios from "axios";
import Dialog from '../../../../Shared/Dialog.vue'
import TextInput from '../../../../Shared/TextInput.vue'
import PasswordInput from '../../../../Shared/PasswordInput.vue'
import Spin from '../../../Shared/Spin.vue'

export default {
    name: "CustomerForm",
    components: { Spin, PasswordInput, TextInput, Dialog },
    props: {
        showModal: {
            type: Boolean,
            default: false,
            required: true
        },
        editItem: {
            type: [String, Number],
            required: false,
            default: null
        },
    },
    data: () => ({
       form: {
           email: '',
           username: '',
           password_confirmation: '',
           password: '',
           first_name: '',
           last_name: '',
        },
        submitting: false,
        errors: [],
        spinning: false,
        user: null
    }),
    mounted() {
        if(this.editItem) {
          this.fetchDetail()
        }
    },
  methods: {
        async fetchDetail() {
          this.spinning = true
          await axios.get(this.route('customers.detail', { id: this.editItem}))
            .then(({ data }) => {
               const response = data?.data ?? null
               this.form.first_name = response.first_name
               this.form.last_name = response.last_name
               this.form.email = response.email
               this.form.username = response.username
            })
            .catch((error) => {

            })
            .finally(() => {
              this.spinning = false
            })
        },
        submit() {
            this.submitting = true
            this.$refs.form.validateFields()
              .then(() => {
                  if(this.editItem) {
                    axios.patch(this.route('customers.update', {id: this.editItem }),
                      this.form,
                      {
                        headers: { 'Content-Type': 'application/json' },
                      })
                      .then((response) => {
                        this.$message.success(response.data.message)
                        this.handleSubmit()
                      })
                      .catch((error) => {
                        this.errors = error.response.errors
                      })
                      .finally(() => {
                        this.submitting = false
                      })
                  } else {
                    axios.post(this.route('customers.store'),
                      this.form,
                      {
                        headers: { 'Content-Type': 'application/json' },
                      })
                      .then((response) => {
                        this.$message.success(response.data.message)
                        this.handleSubmit()
                      })
                      .catch((error) => {
                        // console.log(error)
                        this.errors = error.response.data.errors
                        console.log(this.errors)
                      })
                      .finally(() => {
                        this.submitting = false
                      })
                  }
              })
              .catch(errorInfo => {
                console.log(errorInfo)
              })
              .finally(() => {
                this.submitting = false
              })
        },

        resetForm() {
            this.form.first_name = this.editItem?.first_name ?? null
            this.form.last_name = this.editItem?.last_name ?? null
            this.form.email = this.editItem?.email ?? null
            this.form.username = this.editItem?.username ?? null
            this.form.password = this.editItem?.password ?? null
            this.form.password_confirmation = this.editItem?.password_confirmation ?? null
        },

        handleSubmit() {
          this.resetForm()
          this.$emit('closeModal')
          this.$emit('refreshData')
        },

        handleCancel() {
          this.resetForm()
          this.$emit('closeModal')
        }
    }
}
</script>

<template>
  <Dialog :width="1000" :open="showModal" :title="editItem != null ? 'Update Customer' : 'Create Customer'" @accept="submit" @cancel="handleCancel" ok-button-text="Submit" cancel-button-text="Cancel" :submitting="submitting">

    <a-alert v-if="errors.length > 0" class="!mb-3" type="error" show-icon>
      <template #description>
        <ul class="!mb-0">
          <li v-for="(error, field) in errors" :key="field">{{ error.message }}</li>
        </ul>
      </template>
    </a-alert>

    <Spin :spinning="spinning">
      <a-form :model="form" ref="form" autocomplete="off" layout="vertical" @finish="submit">
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
          <a-col class="gutter-row" :span="24" :md="{ span: 12 }">
            <text-input name="email" v-model="form.email" type="email" label="Email" placeholder="Enter Email" />
          </a-col>

          <!-- Email Input -->
          <a-col class="gutter-row" :span="24" :md="{ span: 12 }">
            <text-input name="username" v-model="form.username" :rules="[{ required: true, message: 'Username field is required.' }]" label="Username" placeholder="Enter Username" />
          </a-col>
          <!-- Password Input -->
          <a-col class="gutter-row" :span="24" :md="{ span: 12 }" v-if="!editItem">
            <password-input name="password" v-model="form.password" placeholder="Enter Password" label="Password" :rules="[{ required: true, message: 'Password field is required!' }]" />
          </a-col>
          <a-col class="gutter-row" :span="24" :md="{ span: 12 }" v-if="!editItem">
            <password-input name="password_confirmation" v-model="form.password_confirmation" :rules="[{ required: true, message: 'Re-type password is required' }]" label="Re-Type Password" placeholder="Enter Re-Type Password" />
          </a-col>
        </a-row>
      </a-form>
    </Spin>
  </Dialog>
</template>

<style>

</style>

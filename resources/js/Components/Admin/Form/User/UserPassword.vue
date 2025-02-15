<script>
import Spin from "../../../Shared/Spin.vue";
import Dialog from "../../../../Shared/Dialog.vue";
import axios from "axios";
import PasswordInput from "../../../../Shared/PasswordInput.vue";

export default {
  name: 'UserPassword',
  components: {
    PasswordInput,
    Dialog,
    Spin
  },
  props: {
    showModal: {
      type: Boolean,
      default: false,
      required: true
    },
    user: {
      type: Object,
      required: true
    },
  },
  data: () => ({
    submitting: false,
    form: {
      password: null,
      password_confirmation: null,
    },
    errors: [],
    spinning: false,
  }),
  methods: {
    async submit() {
      await this.$refs.form.validateFields()
        .then(() => {
          this.submitting = true
          axios.patch(this.route('users.changePassword', {id: this.user.id }), this.form, {
            headers: { 'Content-Type': 'application/json' },
          })
            .then((response) => {
              this.$message.success(response.data.message)
              this.handleModal()
            })
            .catch((error) => {
              this.errors = error.response.data.errors
            })
            .finally(() => {
              this.submitting = false
            })
        })
        .catch(errorInfo => {
          console.log(errorInfo)
        })
    },

    resetForm() {
      this.form.password = null
      this.form.password_confirmation = null
    },
    handleModal() {
      this.resetForm()
      this.$emit('closeModal')
    }
  }
}
</script>

<template>
  <div>
    <Dialog :open="showModal" :title="`Change password of ${user?.full_name ?? ''}`" @accept="submit" @cancel="handleModal" ok-button-text="Submit" cancel-button-text="Cancel" :submitting="submitting">
      <Spin :spinning="spinning">
        <a-alert v-if="errors.length > 0" class="!mb-3" type="error" show-icon>
          <template #description>
            <ul class="!mb-0">
              <li v-for="(error, field) in errors" :key="field">{{ error.message }}</li>
            </ul>
          </template>
        </a-alert>
        <a-form :model="form" ref="form" autocomplete="off" layout="vertical"
                @finish="submit">
          <password-input name="password" v-model="form.password" :rules="[{ required: true, message: 'The new password is required' }]" label="New Password" placeholder="Enter New Password" />
          <password-input name="password_confirmation" v-model="form.password_confirmation" :rules="[{ required: true, message: 'The re-type password is required' }]" label="Re-Type Password" placeholder="Enter Re-Type Password" />
        </a-form>
      </Spin>
    </Dialog>
  </div>
</template>

<style scoped>

</style>

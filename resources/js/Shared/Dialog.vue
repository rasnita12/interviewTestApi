<script>
export default {
    props: {
        open: {
            type: Boolean,
            default: false,
        },
        title: {
            type: String,
            default: ''
        },
        submitting: {
            type: Boolean,
            default: false
        },
        cancelButtonText: {
            type: String,
            default: 'Cancel'
        },
        okButtonText: {
            type: String,
            default: 'Ok'
        },
        width: {
            type: Number,
            default: 520
        },
        showOkButton: {
            type: Boolean,
            default: true
        }
    },
    data: () => ({
        openModal: false,
    }),
    watch: {
      open(newValue) {
          this.openModal = newValue
      },
    },
    mounted() {
      this.openModal = this.open
    },
    emits: ["accept", "cancel"],
    methods: {
        acceptAction() {
            this.$emit("accept");
        },
        cancelAction() {
            this.$emit("cancel");
        }
    }
}
</script>

<template>
    <a-modal :open="openModal" :title="title" @ok="acceptAction()" @cancel="cancelAction()" :width="width" :mask-closable="false">
        <template #footer>
            <a-button key="back" type="primary" :danger="true" @click="cancelAction">{{ cancelButtonText }}</a-button>
            <a-button v-if="showOkButton" key="submit" type="primary" html-type="submit" :disabled="submitting" :loading="submitting" @click="acceptAction">{{ okButtonText }}</a-button>
        </template>

        <slot></slot>
    </a-modal>
</template>

<style scoped>

</style>

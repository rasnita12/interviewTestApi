<script>
export default {
    inheritAttrs: false,
    props: {
        label: {
            type: String,
            default: ''
        },
        modelValue: String,
        placeholder: {
            type: String,
            default: ''
        },
        name: {
            type: String,
            default: ''
        },
        size: {
            type: String,
            default: 'large'
        },
        rules: {
            type: Array,
            default: []
        }
    },
    emits: ['update:modelValue'],
}
</script>

<template>
    <div :class="$attrs.class">
        <a-form-item
            :label="label"
            :name="name"
            :rules="rules"
        >
            <a-input-password :value="modelValue" v-bind="{ ...$attrs, class: null }" :placeholder="placeholder" :size="size" @input="$emit('update:modelValue', $event.target.value)" />
        </a-form-item>
    </div>
</template>

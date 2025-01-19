<template>
    <div :class="$attrs.class">
        <a-form-item
            :label="label"
            :name="name"
            :rules="rules"
        >
            <a-input :id="id" :value="modelValue" v-bind="{ ...$attrs, class: null }" :placeholder="placeholder" :size="size" @input="handleChange" @change="handleInputChange" :allowClear="allowClear">
                <template v-if="prefixIcon" #prefix>
                    <component v-if="(typeof prefixIcon) === 'function'" :is="prefixIcon" />
                    <span v-else>{{ prefixIcon }}</span>
                </template>
                <template v-if="suffixIcon" #suffix>
                    <component v-if="(typeof suffixIcon) === 'function'" :is="suffixIcon" />
                    <span v-else>{{ suffixIcon }}</span>
                </template>
            </a-input>
        </a-form-item>
    </div>
</template>

<script>
import { v4 as uuid } from 'uuid'

export default {
  inheritAttrs: false,
  props: {
      id: {
          type: String,
          default() {
              return `text-input-${uuid()}`
          },
      },
    type: {
      type: String,
      default: 'text',
    },
    label: {
          type: String,
        default: null
    },
    modelValue: String,
    placeholder: {
        type: String,
        default: ''
    },
    name: {
        type: [String, Array],
        default: ''
    },
    size: {
        type: String,
        default: 'large'
    },
    rules: {
        type: Array,
        default: []
    },
    error: {
        type: String,
        default: null,
        required: false
    },
    prefixIcon: {
          type: [Function,String],
          default: null
    },
    suffixIcon: {
          type: [Function,String],
          default: null
    },
    allowClear: {
          type: Boolean,
          default: false
    },
  },
  emits: ['update:modelValue'],
  methods: {
      handleInputChange(value) {
          this.$emit('update:modelValue', value);
      },
      handleChange(event) {
          this.$emit('update:modelValue', event.target.value);
      },
  },
}
</script>

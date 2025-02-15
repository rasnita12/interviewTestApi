<script>
    import { h } from 'vue';
    import { LoadingOutlined } from '@ant-design/icons-vue';
    export default {
        props: {
            spinning: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                indicator:  h(LoadingOutlined, {
                    style: {
                        fontSize: '24px',
                    },
                    spin: true,
                })
            }
        }
    }
</script>

<template>
    <div>
        <a-spin :indicator="indicator" :spinning="spinning">
            <slot></slot>
        </a-spin>
    </div>
</template>

<style scoped>

</style>

<script>
import { Link } from '@inertiajs/vue3'
export default {
    components: {Link},
    props: {
        title: {
            type: String,
            default: null
        },
        showFirst: {
            type: Boolean,
            default: true
        },
        routeName: {
            type: String,
            default: 'dashboard'
        }
    }
}
</script>

<template>
    <div>
        <div class="flex flex-row items-center">
            <h3 class="text-xl font-semibold inline mr-3">{{ title }}</h3>
            <a-breadcrumb class="inline">
                <a-breadcrumb-item v-if="showFirst"><Link :href="route(routeName)">Dashboard</Link></a-breadcrumb-item>
                <slot />
            </a-breadcrumb>
        </div>
    </div>
</template>

<style scoped>

</style>

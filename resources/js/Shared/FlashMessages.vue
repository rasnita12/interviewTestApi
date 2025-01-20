<template>
    <div>
        <!-- Your template content goes here -->
    </div>
</template>

<script>

export default {
    data() {
        return {
            errorMessage: null,
            type: null
        };
    },
    watch: {
        '$page.props.flash': {
            handler() {
                if (this.$page.props && this.$page.props.flash) {

                    //Error message
                    if(this.$page.props.flash.error) {
                        this.errorMessage = this.$page.props.flash.error;
                        this.type = 'error'
                    }

                    //success message
                    if(this.$page.props.flash.success) {
                        this.errorMessage = this.$page.props.flash.success;
                        this.type = 'success'
                    }

                    // Use setTimeout to delay execution
                    setTimeout(() => {
                        this.showToastMessage(this.type, this.errorMessage);

                        // Reset errorMessage after displaying
                        this.errorMessage = null;
                        this.type = null;
                    }, 0);
                }
            },
            deep: true,
        },
    },
    methods: {
        showToastMessage(type, msg) {
            if (type === 'error') {
                this.$message.error(msg);
            } else if (type === 'success') {
                this.$message.success(msg);
            }
        },
    },
};
</script>

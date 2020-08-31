<template>
    <div>
        DROPDOWN
    </div>
</template>

<script>
    export default {

        props: [ 'field' ],

        data() {
            return {
                currentPermissions: [],
            }
        },

        created() {
            for (var i = 0; i < this.field.value.length; i++) {
                this.currentPermissions.push(this.field.value[i].name)
            }
            this.field.value = this.currentPermissions
        },

        methods: {
            optionClass(option) {
                return {
                    'bg-success': this.field.value ? this.field.value.includes(option) : false,
                    'bg-danger': this.field.value ? !this.field.value.includes(option) : true,
                }
            }
        }

    }
</script>

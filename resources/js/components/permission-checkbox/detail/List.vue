<template>
    <div class="flex flex-wrap">
        <div
            v-for="(permissions, group) in field.permissions"
            :key="group"
            class="flex flex-col mb-4 pr-4 w-1/2"
        >
            <div class="p-3 bg-40 border-l border-r border-t border-60">
                <h4>{{ group }}</h4>
            </div>

            <div class="flex-grow bg-20 p-3 border border-60">
                <div
                    v-for="(permission, name) in permissions"
                    :key="name"
                    class="p-1"
                >
                    <span
                        class="inline-block rounded-full w-2 h-2"
                        :class="optionClass(permission.name)"
                    ></span>
                    <span class="pl-2">{{ permission.name }}</span>
                </div>
            </div>
        </div>
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

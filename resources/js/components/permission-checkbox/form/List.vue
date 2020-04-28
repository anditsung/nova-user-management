<template>
    <div>
        <div class="w-full mb-4">
            <span class="ml-auto btn btn-primary btn-default mr-3" @click="checkAll()">{{ __('Check all')}}</span>
            <span class="ml-auto btn btn-danger btn-default mr-3" @click="uncheckAll()">{{ __('Uncheck all') }}</span>
            <span class="ml-auto btn bg-success btn-default text-white hover:bg-success-dark" @click="reset()">{{ __("Reset")}}</span>
        </div>

        <div class="flex flex-wrap">
            <div
                v-for="(permissions, group) in field.permissions"
                :key="group"
                class="flex flex-col mb-4 pr-4 w-1/2"
            >
                <div class="p-3 bg-40 border-l border-t border-r border-60">
                    <h4>{{ group }}</h4>
                </div>

                <div class="flex-grow bg-20 p-3 border border-60">
                    <div
                        v-for="(permission, name) in permissions"
                        :key="name"
                        class="p-1"
                    >
                        <checkbox
                            :value="permission.name"
                            :checked="isChecked(permission.name)"
                            @input="toggleOption(permission.name)"
                        ></checkbox>
                        <label
                            :for="field.label"
                            v-text="permission.label"
                            @click="toggleOption(permission.name)"
                            class="pl-2"
                        ></label>
                    </div>
                </div>
            </div>
        </div>

        <p
            v-if="hasError"
            class="my-2 text-danger"/>
    </div>
</template>

<script>
    import {FormField, HandlesValidationErrors} from "laravel-nova";

    export default {
        mixins: [FormField, HandlesValidationErrors],

        props: [ "field" ],

        data() {
            return {
                oldPermissions: [],
            }
        },

        mounted() {
            var permissions = []
            for (var i = 0; i < this.field.value.length; i++) {
                permissions.push(this.field.value[i].name)
                this.oldPermissions.push(this.field.value[i].name)
            }
            this.value = permissions
        },

        methods: {
            isChecked(option) {
                return this.value ? this.value.includes(option) : false;
            },

            check(option) {
                if (!this.isChecked(option)) {
                    this.value.push(option);
                }
            },

            uncheck(option) {
                if (this.isChecked(option)) {
                    this.$set(this, "value", this.value.filter(item => item !== option));
                }
            },

            checkAll() {
                let permissions = _.flatMap(this.field.permissions);
                for (var i = 0; i < permissions.length; i++) {
                    this.check(permissions[i].name);
                }
            },

            uncheckAll() {
                let permissions = _.flatMap(this.field.permissions);
                for (var i = 0; i < permissions.length; i++) {
                    this.uncheck(permissions[i].name);
                }
            },

            reset() {
                let permissions = _.flatMap(this.field.permissions);
                for (var i = 0; i < permissions.length; i++) {
                    if(this.oldPermissions.includes(permissions[i].name)) {
                        this.check(permissions[i].name);
                    }
                    else {
                        this.uncheck(permissions[i].name);
                    }
                }
            },

            toggleOption(option) {
                if (this.isChecked(option)) {
                    return this.uncheck(option);
                }
                this.check(option);
            },
        }
    }
</script>

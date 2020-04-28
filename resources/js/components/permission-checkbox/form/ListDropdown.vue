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
                class="flex flex-col mb-4 pr-2 w-1/2"
            >
                <div class="cursor-pointer flex p-3 bg-40 border border-60" @click="showItem(group)">
                    <div class="flex flex-grow">
                        <h4 class="flex-grow">{{ group }}</h4>
                        <div
                            v-for="(permission, name) in permissions"
                            :key="name"
                            class="pr-1"
                        >
                            <span
                                class="inline-block rounded-full w-2 h-2"
                                :class="optionClass(permission.name)"></span>
                        </div>
                    </div>
                    <div class="pl-2">
                        <span class="font-bold" v-if="activeItem == group">-</span>
                        <span class="font-bold" v-else>+</span>
                    </div>
                </div>
                <div v-show="activeItem == group" class="bg-20 p-3 border-l border-b border-r border-60">
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
                activeItem: null,
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
            showItem(group) {
                if( !this.activeItem) {
                    this.activeItem = group
                }
                else if(this.activeItem !== group) {
                    this.activeItem = group
                }
                else {
                    this.activeItem = null;
                }
            },

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

            optionClass(option) {
                return {
                    'bg-success': this.value ? this.value.includes(option) : false,
                    'bg-danger': this.value ? !this.value.includes(option) : true,
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

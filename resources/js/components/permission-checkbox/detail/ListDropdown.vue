<template>
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
                        <span
                            class="inline-block rounded-full w-2 h-2"
                            :class="optionClass(permission.name)"></span>
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
                activeItem: null,
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

            optionClass(option) {
                return {
                    'bg-success': this.field.value ? this.field.value.includes(option) : false,
                    'bg-danger': this.field.value ? !this.field.value.includes(option) : true,
                }
            }
        }

    }
</script>

<template>
    <panel-item :field="field">
        <div slot="value">
            <template v-if="field.displayType == 1">
                <List :field="field"></List>
            </template>
            <template v-else-if="field.displayType == 2">
                <ListDropdown :field="field"></ListDropdown>
            </template>
            <template v-else>
                <Dropdown :field="field"></Dropdown>
            </template>
        </div>
    </panel-item>
</template>

<script>
    import List from './List'
    import ListDropdown from "./ListDropdown";
    import Dropdown from "./Dropdown";

    export default {
        components: {Dropdown, ListDropdown, List},
        props: [ 'field' ],

    }
</script>

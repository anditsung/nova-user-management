<template>
    <default-field :field="field" :full-width-content="true">
        <template slot="field">
            <template v-if="field.displayType == 1">
                <List :field="field"></List>
            </template>
            <template v-else-if="field.displayType == 2">
                <ListDropdown :field="field"></ListDropdown>
            </template>
            <template v-else>

            </template>
        </template>
    </default-field>
</template>

<script>
    import List from "./List";
    import ListDropdown from "./ListDropdown";

    export default {
        components: {ListDropdown, List},

        props: ["field"],
    }
</script>

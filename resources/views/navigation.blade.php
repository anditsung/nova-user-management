@canany(['viewAny users', 'viewAny roles', 'viewAny Permissions'])
<nova-sidebar>
    <template>
        <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="var(--sidebar-icon)" d="M11.85 17.56a1.5 1.5 0 0 1-1.06.44H10v.5c0 .83-.67 1.5-1.5 1.5H8v.5c0 .83-.67 1.5-1.5 1.5H4a2 2 0 0 1-2-2v-2.59A2 2 0 0 1 2.59 16l5.56-5.56A7.03 7.03 0 0 1 15 2a7 7 0 1 1-1.44 13.85l-1.7 1.71zm1.12-3.95l.58.18a5 5 0 1 0-3.34-3.34l.18.58L4 17.4V20h2v-.5c0-.83.67-1.5 1.5-1.5H8v-.5c0-.83.67-1.5 1.5-1.5h1.09l2.38-2.39zM18 9a1 1 0 0 1-2 0 1 1 0 0 0-1-1 1 1 0 0 1 0-2 3 3 0 0 1 3 3z"/></svg>
        <span class="sidebar-label">
            {{ __('User Management') }}
        </span>
    </template>

    <template v-slot:menu>

        @can('viewAny users')
            <li class="leading-wide mb-4 text-sm">
                <router-link :to="{
                name: 'index',
                params: {
                    resourceName: 'users'
                }
            }" class="text-white ml-8 no-underline dim">
                    {{ __("Users") }}
                </router-link>
            </li>
        @endcan

        @can('viewAny roles')
            <li class="leading-wide mb-4 text-sm">
                <router-link :to="{
                name: 'index',
                params: {
                    resourceName: 'roles'
                }
            }" class="text-white ml-8 no-underline dim">
                    {{ __("Roles") }}
                </router-link>
            </li>
        @endcan

        @can('viewAny permissions')
            <li class="leading-wide mb-4 text-sm">
                <router-link :to="{
                name: 'index',
                params: {
                    resourceName: 'permissions'
                }
            }" class="text-white ml-8 no-underline dim">
                    {{ __("Permissions") }}
                </router-link>
            </li>
        @endcan

    </template>
</nova-sidebar>
@endcanany

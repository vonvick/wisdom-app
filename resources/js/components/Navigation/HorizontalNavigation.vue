<template>
    <nav class="flex items-center justify-between p-6">
        <div class="flex items-center ">
            <a class="w-64" href="{{ url('/') }}">
                <slot name="appLogo"></slot>
            </a>
        </div>

        <div class="flex items-center">
            <ul class="md:flex md:flex-row hidden items-center">
                <li v-for="(route, routeIndex) in routeLinks" :key="routeIndex"></li>
            </ul>
        </div>
    </nav>
</template>

<script>
export default {
    name: 'HorizontalNavigation',
    props: {
        routeLinks: {
            type: Array,
            default: () => ([]),
        }
    },
};
</script>

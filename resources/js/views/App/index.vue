<template>
    <div>
        <HorizontalNavigation :route-links="userRoutes" />
        <router-view></router-view>
    </div>
</template>
<script>
import HorizontalNavigation from '../../components/Navigation/HorizontalNavigation';

export default {
    name: 'UserIndex',
    components: { HorizontalNavigation },
    data() {
        return {
            userRoutes: {
                news: '/app/news',
                events: '/app/events',
                members: '/app/members',
            }
        };
    },
    created() {

    },
};
</script>

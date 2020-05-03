<template>
  <div>
    <Authentication/>
  </div>
</template>
<script>
import Authentication from '../components/Auth/Authentication';

export default {
  name: 'LoginComponent',
  components: { Authentication },
  props: {

  },
  created() {
    console.log(' I am here');
  }
};
</script>

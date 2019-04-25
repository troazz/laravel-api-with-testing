<template>
    <div class="row">
      <div class="col-md-12">
        <h1>Hallo <span class="font-weight-bold" style="text-decoration: underline;">{{ currentUser ? currentUser.name : '' }}</span></h1>
        <h2>welcome to your work space.</h2>
      </div>
    </div>
</template>
<script>
export default {
  computed: {
    currentUser() {
      return this.$store.getters.currentUser;
    }
  }
};
</script>

<template>
  <div>
    <Form @save="save" @cancel="cancel"></Form>
  </div>
</template>
<script>
import Form from "./Form.vue";

export default {
  components: { Form },
  data() {
    return {
      title: "Add Amenity",
      form: {
        name: "",
        description: ""
      },
      error: null,
      submitting: false
    };
  },
  methods: {
    cancel() {
      this.$router.push({ path: "/amenities" });
    },
    save() {
      let self = this;
      self.$data.submitting = true;
      let loader = this.$loading.show();
      axios
        .post("/api/amenities", this.$data.form)
        .then(res => {
          loader.hide();
          self.$data.submitting = false;
          this.$router.push({ path: "/amenities" });
          this.$notify({
            title: "Add Success",
            text: "Success add new amenity to our system."
          });
        })
        .catch(error => {
          loader.hide();
          self.$data.error = error.response.data;
          self.$data.submitting = false;
        });
    }
  }
};
</script>

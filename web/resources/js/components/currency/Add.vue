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
      title: "Add Currency",
      form: {
        name: "",
        code: "",
        symbol: "",
        symbol_location: "prefix"
      },
      error: null,
      submitting: false
    };
  },
  methods: {
    cancel() {
      this.$router.push({ path: "/currencies" });
    },
    save() {
      let self = this;
      self.$data.submitting = true;
      let loader = this.$loading.show();
      axios
        .post("/api/currencies", this.$data.form)
        .then(res => {
          loader.hide();
          self.$data.submitting = false;
          this.$router.push({ path: "/currencies" });
          this.$notify({
            title: "Add Success",
            text: "Success add new currency to our system."
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

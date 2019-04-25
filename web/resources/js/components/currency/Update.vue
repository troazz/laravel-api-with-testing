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
      title: "Update Currency",
      id: 0,
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
  mounted() {
    let self = this;
    this.$data.submitting = true;
    this.$data.id = this.$route.params.id;
    let loader = this.$loading.show();
    axios
      .get("/api/currencies/" + this.$data.id)
      .then(res => {
        loader.hide();
        let { name, code, symbol, symbol_location } = res.data;
        self.$data.form.name = name;
        self.$data.form.code = code;
        self.$data.form.symbol = symbol;
        self.$data.form.symbol_location = symbol_location;
        self.submitting = false;
      })
      .catch(err => {
        loader.hide();
        self.$router.push({ path: "/currencies" });
        self.$notify({
          title: "Error",
          type: "error",
          text:
            "You can't edit currency data, because those data was not found."
        });
        self.submitting = false;
      });
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
        .put("/api/currencies/" + this.$data.id, this.$data.form)
        .then(res => {
          loader.hide();
          self.$data.submitting = false;
          this.$router.push({ path: "/currencies" });
          this.$notify({
            title: "Update Success",
            text: "Success update existing currency on our system."
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

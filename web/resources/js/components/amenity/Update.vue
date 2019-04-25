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
      title: "Update Amenity",
      id: 0,
      form: {
        name: "",
        description: ""
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
      .get("/api/amenities/" + this.$data.id)
      .then(res => {
        let { name, description } = res.data;
        loader.hide();
        self.$data.form.name = name;
        self.$data.form.description = description;
        self.submitting = false;
      })
      .catch(err => {
        loader.hide();
        self.$router.push({ path: "/amenities" });
        self.$notify({
          title: "Error",
          type: "error",
          text: "You can't edit amenity data, because those data was not found."
        });
        self.submitting = false;
      });
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
        .put("/api/amenities/" + this.$data.id, this.$data.form)
        .then(res => {
          loader.hide();
          self.$data.submitting = false;
          this.$router.push({ path: "/amenities" });
          this.$notify({
            title: "Update Success",
            text: "Success update existing amenity on our system."
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

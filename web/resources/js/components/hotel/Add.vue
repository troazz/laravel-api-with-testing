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
      title: "Add Hotel",
      form: {
        name: "",
        description: "",
        address: "",
        longitude: "",
        latitude: "",
        commission_type: "percentage",
        commission_amount: "0",
        rooms: [
          {
            id: "",
            name: "",
            description: "",
            access_code: "",
            max_occupancy: "",
            net_rate: "",
            currency_id: "",
            amenities_id: []
          }
        ]
      },
      error: null,
      submitting: false
    };
  },
  methods: {
    cancel() {
      this.$router.push({ path: "/hotels" });
    },
    save() {
      let self = this;
      self.$data.submitting = true;
      let loader = this.$loading.show();
      axios
        .post("/api/hotels", this.$data.form)
        .then(res => {
          loader.hide();
          self.$data.submitting = false;
          this.$router.push({ path: "/hotels" });
          this.$notify({
            title: "Add Success",
            text: "Success add new hotel and it's rooms to our system."
          });
        })
        .catch(error => {
          loader.hide();
          self.$data.submitting = false;
          self.$data.error = error.response.data;
        });
    }
  }
};
</script>

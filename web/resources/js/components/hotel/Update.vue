<template>
  <div>
    <Form @save="save" @cancel="cancel"></Form>
  </div>
</template>
<script>
import Form from "./Form.vue";

export default {
  components: { Form },
  mounted() {
    let self = this;
    this.$data.submitting = true;
    this.$data.id = this.$route.params.id;
    let loader = this.$loading.show();
    axios
      .get("/api/hotels/" + this.$data.id)
      .then(res => {
        loader.hide();
        let {
          name,
          description,
          address,
          longitude,
          latitude,
          commission_type,
          commission_amount,
          rooms
        } = res.data;
        self.$data.form.name = name;
        self.$data.form.description = description;
        self.$data.form.address = address;
        self.$data.form.latitude = latitude;
        self.$data.form.longitude = longitude;
        self.$data.form.commission_type = commission_type;
        self.$data.form.commission_amount = commission_amount;
        self.$data.form.rooms = [];
        rooms.map(function(room) {
          let {
            id,
            name,
            description,
            access_code,
            max_occupancy,
            net_rate,
            currency_id,
            amenities
          } = room;
          amenities = amenities.map(function(amenity) {
            return amenity.id;
          });
          self.$data.form.rooms.push({
            id: id,
            name: name,
            description: description,
            access_code: access_code,
            max_occupancy: max_occupancy,
            net_rate: net_rate,
            currency_id: currency_id,
            amenities_id: amenities
          });
        });

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
  data() {
    return {
      title: "Update Hotel",
      id: 0,
      form: {
        name: "",
        description: "",
        address: "",
        longitude: "",
        latitude: "",
        commission_type: "percentage",
        commission_amount: "0",
        rooms: []
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
        .put("/api/hotels/" + this.$data.id, this.$data.form)
        .then(res => {
          loader.hide();
          self.$data.submitting = false;
          this.$router.push({ path: "/hotels" });
          this.$notify({
            title: "Update Success",
            text: "Success update hotel and it's rooms on our system."
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

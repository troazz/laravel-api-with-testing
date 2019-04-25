<template>
  <form @submit.prevent="save">
    <div class="login row justify-content-center">
      <div class="col-md-12">
        <h4>{{ title }}</h4>
        <div class="alert alert-danger" v-if="error">
          {{error.message}}
          <ol v-show="!!error.errors">
            <li v-for="(err, index) in error.errors" v-bind:key="index">{{err}}</li>
          </ol>
        </div>
      </div>
      <div class="col-md-7">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">Hotel General Information</h5>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="name">Name</label>
              <input
                type="text"
                class="form-control"
                id="name"
                v-model="form.name"
                placeholder="Hotel Name"
              >
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <textarea
                class="form-control"
                id="description"
                v-model="form.description"
                placeholder="Hotel Description"
              ></textarea>
            </div>
            <div class="form-group">
              <label for="address">Address</label>
              <textarea
                class="form-control"
                id="address"
                v-model="form.address"
                placeholder="Hotel Address"
              ></textarea>
            </div>
            <div class="form-group row">
              <div class="col-md-12">
                <label for="latitude">Lat, Long</label>
              </div>
              <div class="col-md-6">
                <input
                  type="text"
                  class="form-control"
                  id="latitude"
                  v-model="form.latitude"
                  placeholder="Latitude"
                >
              </div>
              <div class="col-md-6">
                <input
                  type="text"
                  class="form-control"
                  id="longitude"
                  v-model="form.longitude"
                  placeholder="Longitude"
                >
              </div>
            </div>
            <div class="form-group">
              <label for="commission_type">Commission Type</label>
              <br>
              <div class="form-check form-check-inline">
                <input
                  class="form-check-input"
                  type="radio"
                  id="inlineRadio1"
                  v-model="form.commission_type"
                  name="commission_type"
                  value="percentage"
                >
                <label class="form-check-label" for="inlineRadio1">Percentage</label>
              </div>
              <div class="form-check form-check-inline">
                <input
                  class="form-check-input"
                  type="radio"
                  id="inlineRadio2"
                  v-model="form.commission_type"
                  name="commission_type"
                  value="exact"
                >
                <label class="form-check-label" for="inlineRadio2">Exact</label>
              </div>
            </div>
            <div class="form-group">
              <label for="commission_amount">Commission Amount</label>
              <input
                type="text"
                class="form-control"
                id="commission_amount"
                v-model="form.commission_amount"
                placeholder="Commission Amount"
              >
            </div>
            <div class="form-group">
              <input type="submit" value="Save" :disabled="submitting" class="btn btn-primary">
              <input
                type="button"
                value="Back"
                :disabled="submitting"
                @click.prevent="cancel"
                class="btn btn-outline-secondary"
              >
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-5">
        <h5>
          Rooms
          <a href="#" @click.prevent="addroom" class="btn btn-outline-success btn-sm">Add</a>
        </h5>
        <div class="card">
          <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
              <li class="nav-item" v-for="(room, index) in form.rooms" v-bind:key="'title-'+index">
                <a
                  :class="active_room == index ? 'nav-link active' : 'nav-link'"
                  @click.prevent="openTab(index)"
                  href="#"
                >Room {{ index+1 }}</a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div
              v-for="(room, index) in form.rooms"
              v-bind:key="'container-'+index"
              :class="index == active_room ? 'd-block' : 'd-none'"
            >
              <div class="form-group">
                <label :for="index+'_room_name'">Name</label>
                <input
                  type="text"
                  class="form-control input-sm"
                  :id="index+'_room_name'"
                  v-model="room.name"
                  placeholder="Room Name"
                >
              </div>
              <div class="form-group">
                <label :for="index+'_room_desc'">Description</label>
                <textarea
                  class="form-control input-sm"
                  :id="index+'_room_desc'"
                  v-model="room.description"
                  placeholder="Hotel Description"
                ></textarea>
              </div>
              <div class="form-group">
                <label :for="index+'_room_ac'">Access Code</label>
                <input
                  type="text"
                  class="form-control input-sm"
                  :id="index+'_room_ac'"
                  v-model="room.access_code"
                  placeholder="Access Code"
                >
              </div>
              <div class="form-group">
                <label :for="index+'_room_max'">Max Occupancy</label>
                <input
                  type="text"
                  class="form-control input-sm"
                  :id="index+'_room_max'"
                  v-model="room.max_occupancy"
                  placeholder="Max Accoupancy"
                >
              </div>
              <div class="form-group">
                <label :for="index+'_room_net'">Net Rate</label>
                <input
                  type="text"
                  class="form-control input-sm"
                  :id="index+'_room_net'"
                  v-model="room.net_rate"
                  placeholder="Net Rate"
                >
              </div>
              <div class="form-group">
                <label :for="index+'_room_curr'">Currency</label>
                <select
                  class="form-control input-sm"
                  :id="index+'_room_curr'"
                  v-model="room.currency_id"
                >
                  <option value>-- Select --</option>
                  <option
                    v-for="(curr, cid) in currencies"
                    v-bind:key="'cur-'+cid"
                    :value="curr.id"
                  >{{ curr.name + ' ('+curr.code+')' }}</option>
                </select>
              </div>
              <div class="form-group">
                <label :for="index+'_room_amenity'">Amenities</label>
                <select
                  class="form-control input-sm"
                  :id="index+'_room_amenity'"
                  v-model="room.amenities_id"
                  multiple
                >
                  <option
                    v-for="(ame, aid) in amenities"
                    v-bind:key="'cur-'+aid"
                    :value="ame.id"
                  >{{ ame.name }}</option>
                </select>
              </div>
              <a
                v-show="index > 0"
                href="#"
                @click.prevent="removeRoom(index)"
                class="btn btn-outline-danger btn-sm"
              >Delete</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</template>
<script>
export default {
  mounted() {
    let self = this;
    axios.get("/api/amenities").then(res => {
      self.$data.amenities = res.data;
    });
    axios.get("/api/currencies").then(res => {
      self.$data.currencies = res.data;
    });
  },
  data() {
    return {
      amenities: [],
      currencies: [],
      active_room: 0
    };
  },
  methods: {
    save() {
      this.$emit("save");
    },
    cancel() {
      this.$emit("cancel");
    },
    addroom() {
      let roomCount = this.$parent.$data.form.rooms.length;
      if (roomCount >= 5) {
        this.$notify({
          title: "Whoops!",
          type: "error",
          text:
            "Sorry, you can't add anymore room. Because maximum capacity is 5."
        });
      } else {
        this.$parent.$data.form.rooms.push({
          id: "",
          name: "",
          description: "",
          access_code: "",
          max_occupancy: "",
          net_rate: "",
          currency_id: "",
          amenities_id: []
        });
      }
    },
    removeRoom(index) {
      this.$parent.$data.form.rooms.splice(index, 1);
      let roomCount = this.$parent.$data.form.rooms.length - 1;
      if (this.$data.active_room > roomCount) {
        this.$data.active_room = roomCount;
      }
    },
    openTab(index) {
      this.$data.active_room = index;
    }
  },
  computed: {
    error() {
      return this.$parent.$data.error;
    },
    form() {
      return this.$parent.$data.form;
    },
    title() {
      return this.$parent.$data.title;
    },
    submitting() {
      return this.$parent.$data.submitting;
    }
  }
};
</script>

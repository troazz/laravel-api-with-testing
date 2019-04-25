<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <h4>Hotels</h4>
        <h5>List all hotels for rooms.</h5>
        <br>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 form-inline">
        <router-link to="/hotels/add" class="btn btn-success btn-sm">Create</router-link>
      </div>
      <div class="col-md-6 form-inline justify-content-end mb-2">
        <label for="per_page">Per Page:</label> &nbsp;
        <select
          v-model="perPage"
          @change="get(1)"
          class="form-control form-control-sm"
          id="per_page"
        >
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
        &nbsp; &nbsp;
        <label for="search">Search:</label> &nbsp;
        <input
          type="text"
          @keyup="get(1)"
          class="form-control form-control-sm"
          id="search"
          v-model="q"
        >
      </div>
    </div>
    <div class="row">
      <div class="col">
        <table class="table table-sm">
          <thead>
            <tr>
              <th style="width: 35px;">No</th>
              <th style="width: 305px;">Name</th>
              <th>Address</th>
              <th>Commision</th>
              <th>Room</th>
              <th style="width: 95px;">Created At</th>
              <th style="width: 95px;">Updated At</th>
              <th style="width: 95px;">Action</th>
            </tr>
          </thead>
          <tbody v-if="!!records">
            <template v-for="(rec, index) in records">
              <tr v-bind:key="index">
                <td>{{ (((page - 1) * perPage) + index + 1) }}</td>
                <td>
                  <strong>{{ rec.name }}</strong>
                  <small v-show="rec.description">
                    <br>
                    {{ rec.description }}
                    <a
                      href="#"
                      @click.prevent="expand(false, index)"
                      v-if="rec.expanded"
                    >[hide rooms]</a>
                    <a href="#" @click.prevent="expand(true, index)" v-else>[show rooms]</a>
                  </small>
                </td>
                <td>
                  <small>
                    {{ rec.address }}
                    <br>
                    <div v-show="rec.latitude && rec.longitude">
                      <strong>Lat, Lang</strong>
                      : {{ rec.latitude }}, {{ rec.longitude }}
                    </div>
                  </small>
                </td>
                <td style="text-align:right;">
                  <small>
                    {{ rec.commission_amount }}
                    <span v-show="rec.commission_type == 'percentage'">%</span>
                  </small>
                </td>
                <td style="text-align:right;">
                  <small>{{ rec.rooms.length }}</small>
                </td>
                <td>
                  <small>{{rec.created_at}}</small>
                </td>
                <td>
                  <small>{{rec.updated_at}}</small>
                </td>
                <td>
                  <div class="btn-group" role="group">
                    <router-link :to="'/hotels/update/'+rec.id" class="btn btn-primary btn-sm">Edit</router-link>
                    <button
                      type="button"
                      class="btn btn-danger btn-sm"
                      @click.prevent="del(rec.id)"
                    >Delete</button>
                  </div>
                </td>
              </tr>
              <tr v-bind:key="'room'+index" v-show="rec.expanded">
                <td colspan="8">
                  <table class="table table-sm table-dark">
                    <thead>
                      <tr>
                        <th style="width: 35px;">
                          <small>No</small>
                        </th>
                        <th>
                          <small>Name</small>
                        </th>
                        <th style="width: 85px;">
                          <small>Access Code</small>
                        </th>
                        <th style="width: 65px;">
                          <small>Max Occ.</small>
                        </th>
                        <th style="width: 105px;">
                          <small>Net Rate</small>
                        </th>
                        <th style="width: 105px;">
                          <small>Sell Rate</small>
                        </th>
                      </tr>
                    </thead>
                    <tbody v-if="!!rec.rooms">
                      <tr v-for="(room, ri) in rec.rooms" v-bind:key="ri">
                        <td>{{ (ri + 1) }}</td>
                        <td>
                          <small>
                            <strong>{{ room.name }}</strong>
                            <br>
                            {{ room.description }}
                          </small>
                        </td>
                        <td>
                          <small>{{ room.access_code }}</small>
                        </td>
                        <td>
                          <small>{{ room.max_occupancy }}</small>
                        </td>
                        <td>
                          <small>{{ room.currency.symbol_location == 'prefix' ? room.currency.symbol + ' ' + room.net_rate : room.net_rate + ' ' + room.currency.symbol }}</small>
                        </td>
                        <td>
                          <small>{{ room.currency.symbol_location == 'prefix' ? room.currency.symbol + ' ' + room.sell_rate : room.sell_rate + ' ' + room.currency.symbol }}</small>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </template>
          </tbody>
          <tbody v-else>
            <tr>
              <td colspan="4">Sorry, data hotels is empty.</td>
            </tr>
          </tbody>
        </table>
        <div class="text-right">
          <nav aria-label="Page navigation" style="display: inline-block;" v-show="last_page > 1">
            <ul class="pagination">
              <li :class="page == 1 ? 'page-item disabled' : 'page-item'">
                <template v-if="page == 1">
                  <a class="page-link" href="#" @click.prevent>Previous</a>
                </template>
                <template v-else>
                  <a class="page-link" href="#" @click.prevent="get(page-1)">Previous</a>
                </template>
              </li>
              <li :class="page == last_page ? 'page-item disabled' : 'page-item'">
                <template v-if="page == last_page">
                  <a class="page-link" href="#" @click.prevent>Next</a>
                </template>
                <template v-else>
                  <a class="page-link" href="#" @click.prevent="get(page+1)">Next</a>
                </template>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      page: 1,
      last_page: 0,
      perPage: 10,
      total: 0,
      records: [],
      q: "",
      isLoading: false
    };
  },
  mounted: function() {
    this.get(1);
  },
  methods: {
    expand(value, i) {
      this.$data.records[i].expanded = value;
    },
    del(id) {
      let self = this;
      let loader = this.$loading.show();
      if (confirm("Are you sure want to delete this?")) {
        axios.delete("/api/hotels/" + id).then(res => {
          loader.hide();
          this.$notify({
            title: "Delete Success",
            text: "Success delete hotel from our system."
          });
          let curTotal = self.$data.total - 1;
          let curLastPage = Math.ceil(curTotal / self.$data.perPage);
          if (curLastPage < self.$data.page) {
            self.$data.page = curLastPage;
          }
          self.get();
        });
      }
    },
    get(i) {
      let page = i == undefined ? this.$data.page : i;
      let params = {
        per_page: this.$data.perPage,
        page: page,
        q: this.$data.q
      };
      let loader = this.$loading.show();
      axios.get("/api/hotels", { params: params }).then(res => {
        loader.hide();
        let r = res.data;
        this.$data.records = r.data.map(i => {
          i.expanded = false;
          return i;
        });
        this.$data.page = r.current_page;
        this.$data.last_page = r.last_page;
        this.$data.total = r.total;
      });
    }
  }
};
</script>

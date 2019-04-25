<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <h4>Amenities</h4>
        <h5>List all amenities for rooms.</h5>
        <br>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 form-inline">
        <router-link to="/amenities/add" class="btn btn-success btn-sm">Create</router-link>
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
        <table class="table table-sm table-hover">
          <thead>
            <tr>
              <th style="width: 35px;">No</th>
              <th>Name</th>
              <th style="width: 95px;">Created At</th>
              <th style="width: 95px;">Updated At</th>
              <th style="width: 95px;">Action</th>
            </tr>
          </thead>
          <tbody v-if="!!records">
            <tr v-for="(rec, index) in records" v-bind:key="index">
              <td>{{ (((page - 1) * perPage) + index + 1) }}</td>
              <td>
                <strong>{{ rec.name }}</strong>
                <small v-show="rec.description"><br />{{ rec.description }}</small>
              </td>
              <td><small>{{rec.created_at}}</small></td>
              <td><small>{{rec.updated_at}}</small></td>
              <td>
                <div class="btn-group" role="group">
                  <router-link :to="'/amenities/update/'+rec.id" class="btn btn-primary btn-sm">Edit</router-link>
                  <button
                    type="button"
                    class="btn btn-danger btn-sm"
                    @click.prevent="del(rec.id)"
                  >Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td colspan="4">Sorry, data amenities is empty.</td>
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
    del(id) {
      let self = this;
      let loader = this.$loading.show();
      if (confirm("Are you sure want to delete this?")) {
        axios.delete("/api/amenities/" + id).then(res => {
          loader.hide();
          this.$notify({
            title: "Delete Success",
            text: "Success delete amenity from our system."
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
      axios.get("/api/amenities", { params: params }).then(res => {
        loader.hide()
        let r = res.data;
        this.$data.records = r.data;
        this.$data.page = r.current_page;
        this.$data.last_page = r.last_page;
        this.$data.total = r.total;
      });
    }
  }
};
</script>

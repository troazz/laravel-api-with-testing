<template>
  <div class="login row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">{{ title }}</h5>
        </div>
        <div class="card-body">
          <div class="alert alert-danger" v-if="error">
            {{error.message}}
            <ol v-show="!!error.errors">
              <li v-for="(err, index) in error.errors" v-bind:key="index">{{err}}</li>
            </ol>
          </div>
          <form @submit.prevent="save">
            <div class="form-group">
              <label for="name">Name</label>
              <input
                type="text"
                class="form-control"
                id="name"
                v-model="form.name"
                placeholder="Amenity Name"
              >
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <textarea
                class="form-control"
                id="description"
                v-model="form.description"
                placeholder="Aminity Description"
              ></textarea>
            </div>
            <div class="form-group">
              <input
                type="submit"
                value="Save"
                :disabled="submitting"
                class="btn btn-primary"
              >
              <input
                type="button"
                value="Back"
                :disabled="submitting"
                @click.prevent="cancel"
                class="btn btn-outline-secondary"
              >
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  methods: {
    save() {
      this.$emit("save");
    },
    cancel() {
      this.$emit("cancel");
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

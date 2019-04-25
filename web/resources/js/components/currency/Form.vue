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
                id="name"
                class="form-control"
                v-model="form.name"
                placeholder="Currency Name"
              >
            </div>
            <div class="form-group">
              <label for="code">Code</label>
              <input
                type="text"
                id="code"
                class="form-control"
                v-model="form.code"
                placeholder="Currency Code"
              >
            </div>
            <div class="form-group">
              <label for="symbol">Symbol</label>
              <input
                type="text"
                id="symbol"
                class="form-control"
                v-model="form.symbol"
                placeholder="Currency Symbol"
              >
            </div>
            <div class="form-group">
              <label for="symbol_location">Symbol Location</label><br>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="inlineRadio1" v-model="form.symbol_location" name="symbol_location" value="prefix">
                <label class="form-check-label" for="inlineRadio1">Prefix</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="inlineRadio2" v-model="form.symbol_location"  name="symbol_location" value="suffix">
                <label class="form-check-label" for="inlineRadio2">Suffix</label>
              </div>
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

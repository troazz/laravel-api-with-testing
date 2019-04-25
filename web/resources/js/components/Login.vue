<template>
  <div class="login row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3>Login</h3>
        </div>
        <div class="card-body">
          <div class="alert alert-danger" v-if="error">
            {{error.message}}
            <ol v-show="!!error.errors">
              <li v-for="(err, index) in error.errors" v-bind:key="index">{{err}}</li>
            </ol>
          </div>
          <form @submit.prevent="authenticate">
            <div class="form-group">
              <label for="email">Email</label>
              <input
                type="email"
                class="form-control"
                v-model="formLogin.email"
                placeholder="Email address"
              >
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input
                type="password"
                class="form-control"
                v-model="formLogin.password"
                placeholder="password"
              >
            </div>
            <div class="form-group">
              <input type="submit" value="Login" class="btn btn-outline-primary">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { login } from "../partials/auth";
export default {
  data() {
    return {
      formLogin: {
        email: "",
        password: ""
      },
      error: null
    };
  },
  methods: {
    authenticate() {
      let loader = this.$loading.show();
      login(this.$data.formLogin)
        .then(res => {
          loader.hide();
          this.$store.commit("loginSuccess", res);
          this.$router.push({ path: "/dashboard" });
        })
        .catch(error => {
          loader.hide();
          this.$data.error = error;
        });
    }
  }
};
</script>
<style scoped>
.error {
  text-align: center;
  color: red;
}
</style>

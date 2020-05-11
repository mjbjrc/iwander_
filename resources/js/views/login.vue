
<template>
<div class="container">
    <div class="body-content">
        <div class="row">

            <div class="col-lg-4 offset-lg-4 col-sm-12">
              <div class="col-12 auth-form-heading">
                        <h2>Sign In</h2>
                        <p>Don't have an account yet? <router-link to="/register-account">Sign Up</router-link></p>
                    </div>
                    <!-- LOG IN FORM -->
                    <form v-on:submit.prevent="login">
                        <div class="row">
                            <div class="form-group col-12">
                              <label for="email">Email Address</label>
                              <input id="email" type="email" v-model="email" class="form-control" name="email" required autocomplete="email">
                              <span class="errors">{{ getErrors('email') }}</span>
                            </div>

                            <div class="col-12">
                              <label for="password">Password</label>
                              <input id="password" v-model="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                              <span class="errors">{{ getPasswordError() }}</span>
                            </div>

                            <div class="form-msg col-12">
                                <a href="#">Forgot Password?</a>
                            </div>

                            <div class="form-button col-12">
                                <button class="btn btn-primary" type="submit">Sign In</button>
                            </div>
                        </div>
                    </form>
                    <!-- END LOG IN FORM -->
            </div>
        </div>
    </div>
</div>
</template>

<script>

export default {
    name: 'login',
    props: ['app'],
    components: {},
    data() {
        return {
            email: "",
            password: "",
            errors: []
        }
    },
    mounted(){
      window.scrollTo(0, 0);
    },
    methods: {
        login() {
            let app = this;
            axios.post('/api/login', {
                    email: app.email,
                    password: app.password
                })
                .then(function(response) {
                    console.log(response.data);
                    localStorage.setItem("token", response.data.token);
                    app.$emit('login');
                    //go to home
                    app.$router.push({
                        name: 'home'
                    });
                })
                .catch(function(error) {
                    console.log(error.response.data.errors);
                    app.errors = error.response.data.errors;
                });
        },
        getErrors(field){
          if(this.errors[field]){
            return this.errors[field][0];
          }
        },
        getPasswordError(){
          if(this.errors === "Unauthorised"){
            return "Password do not match. Try again!";
          }
        }
    }
}
</script>

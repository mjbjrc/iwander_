<template>
  <div class="container" style="margin-top: 200px;">
    <div class="row iwander-row">
      <div class="col-lg-12">
        <h4>Personal Settings</h4>
      </div>
      <div class="col-lg-12 mt-2">
        <form v-on:submit.prevent="editProfile">
            <div class="form-group row">
              <div class="col-md-6">
                <label for="name">Name</label>
                <input id="name" type="text" v-model="user.name" class="form-control" name="name" required autocomplete="name" autofocus>
                <span class="errors">{{ getErrors('name') }}</span>
            </div>
          </div>
            <div class="form-group row">
              <div class="col-md-6">
                <label for="email">Email</label>
                <input id="email" v-model="user.email" type="email" class="form-control" name="email" required autocomplete="email">
                <span class="errors">{{ getErrors('email') }}</span>
              </div>
            </div>
            <hr/>
            <h4>Update Password</h4>
            <div class="form-group row">
              <div class="col-md-6">
                <label for="password">Password</label>
                <input id="password" v-model="user.password" type="password" class="form-control" name="password"  autocomplete="password">
                <span class="errors">{{ getErrors('password') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-6">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" v-model="user.password_confirmation" type="password" class="form-control" name="password_confirmation"  autocomplete="password_confirmation">
                <span class="errors">{{ getErrors('password') }}</span>
              </div>
            </div>
            <div class="form-group row mb-0">
                <div class="col-md-8">
                    <button type="submit" class="btn btn-primary buttons">
                        Save
                    </button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
export default{
  name: 'settings',
  props: ['app'],
  components: {},
  data(){
    return{
      user:{},
      errors: []
    }
  },
  created(){
    this.getUser();
  },
  methods:{
    getUser(){
      let app = this;
      let token = localStorage.getItem("token");
      axios.get('/api/user',{
        headers: { Authorization: "Bearer " + token }
      })
      .then(response => {
        console.log('user:', response.data.user);
        app.user = response.data.user;
      })
      .catch(function(error){
        console.log(error);
      });
    },
    editProfile(){
      let app = this;
      let token = localStorage.getItem("token");

      let data = {
        name: app.user.name,
        email: app.user.email,
        password: app.user.password,
        password_confirmation: app.user.password_confirmation
      }

      axios.post('/api/updateDetails', data,{
        headers: { Authorization: "Bearer " + token}
      })
      .then(res => {
        console.log(res);
        app.getUser();
      })
      .catch(function(error){
        app.errors = error.response.data.errors;
          console.log(error);
      });
    },
    getErrors(field){
      if(this.errors[field]){
        return this.errors[field][0];
      }
    },
  }
}
</script>
<style>

</style>

<template>
  <div>
    <navbar :app="this" v-if="!$route.meta.hideNavigation" :isLoggedIn="isLoggedIn" @login="login()" @logout="logout()"></navbar>

      <router-view :user="this.user" :isLoggedIn="isLoggedIn" @login="login()" @logout="logout()"> </router-view>
    <footerbar v-if="!$route.meta.hideFooter"></footerbar>
  </div>
</template>

<script>
import navbar from './components/navbar'
import footerbar from './components/footerbar'
import itinerarybuilderNav from './components/itinerarybuilderNav'

  export default{
    name: 'app',
    components: {
      navbar, footerbar, itinerarybuilderNav
    },
    data(){
      return{
      isLoggedIn: false,
      user: {}
      }
    },
    created(){
      this.checkIfLoggedIn();
    },
    methods: {
      checkIfLoggedIn(){
        let token = localStorage.getItem("token");
        console.log("Check if log in");
        if(token) {
          this.isLoggedIn = true;
          //get user data
          this.getUser();
        } else {
          this.isLoggedIn = false;
        }
      },
      login(){
        this.isLoggedIn = true;
      },
      logout(){
        this.isLoggedIn = false;
      },
      getUser(){
        let token = localStorage.getItem("token");
        axios.get('/api/user',{
          headers: { Authorization: "Bearer " + token }
        })
        .then(response => {
          console.log('USER DEETS',response.data.user);
          this.user = response.data.user;
          // console.log(this.user.id);
        })
        .catch(function(error){
          console.log(error);
        });
      }
    }
  }
</script>

<style>
</style>

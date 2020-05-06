<template>
  <div>
    <navbar :app="this" v-if="!$route.meta.hideNavigation" :isLoggedIn="isLoggedIn" @login="login()" @logout="logout()"></navbar>
    <!-- <itinerarybuilderNav v-if="$route.meta.showItineraryBuilder" @proceed="proceed()"></itinerarybuilderNav> -->

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
          this.init();
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
      init(){
        let token = localStorage.getItem("token");
        axios.get('/api/user',{
          headers: { Authorization: "Bearer " + token }
        })
        .then(response => {
          console.log('USER DEETS',response.data.user);
          this.user = response.data.user;
          console.log(this.user.id);
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

<template>
<div>
    <div class="jumbotron jumbotron-fluid index-banner">
        <div class="container">
            <h2>Browse Itineraries</h2>
        </div>
    </div>


    <!---------- LIST OF ITINERARIES ---------->
    <div class="container" style="margin-bottom: 100px;">
        <div class="row itineraries-row">

            <div class="col-lg-4 col-md-3 col-sm-12"  v-for="itinerary in itineraries">
                <div class="card-d">

                    <router-link :to="{ name: 'itinerary', params: {itinerary_id: itinerary.itinerary.id} }">
                        <div class="itinerary-name">
                            <h3>
                                {{itinerary.days}} in <br />
                                {{itinerary.itinerary.destination}}
                            </h3>
                        </div>
                    </router-link>

                    <p>
                        By <a href="#">{{itinerary.itinerary.user.name}}</a><br />
                        Created on {{moment(itinerary.itinerary.created_at, "Y-m-d H:i:s").format('DD MMMM YYYY')}}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!---------- LIST END ---------->





    <!-- ASDASD -->
    <!-- <div class="row iwander-row">

        <div class="col-lg-12 mt-4">
            <div class="row">
                <div class="col-6 col-lg-3 col-sm-6" v-for="itinerary in itineraries">

                    <div class="card-b">
                        <div class="card-buttons">
                            <a class="card-action" title="Add to Bookmarks" @click="addToBookmarks(itinerary.itinerary.id)">
                                <span class="lnr lnr-bookmark"></span>
                            </a>
                        </div>
                        <a>
                            <router-link :to="{ name: 'itinerary', params: {itinerary_id: itinerary.itinerary.id} }">
                                <div class="box" style="background-color: #ccc; height:200px; padding: 30px 0px;">
                                    <h3>{{itinerary.days}} in {{itinerary.itinerary.destination}}</h3>
                                </div>
                                <div class="card-b-body">
                                    <p class="city">
                                        <p>{{itinerary.itinerary.start_date}} to {{itinerary.itinerary.end_date}}</p>
                                        <p>
                                            By: {{itinerary.itinerary.user.name}}
                                        </p>
                                    </p>
                                </div>
                            </router-link>
                        </a>
                    </div>

                </div>
                <alert v-if="show_alert" @close="show_alert = false" :alertMessage="alertMessage"> </alert>
            </div>
        </div> -->
    </div>
    <!-- ASDASD -->

</div>
</template>
<script>
var moment = require('moment')
import alert from '../components/alert';

export default {
    name: 'itinerariesIndex',
    components: {
        alert
    },
    data() {
        return {
            moment: moment,
            itineraries: [],
            images: [],
            user_id: '',
            alertMessage: "",
            show_alert: false
        }
    },
    created() {
        this.getUser();
        this.getAllItineraries();
    },
    methods: {
        getUser() {
            let app = this;
            let token = localStorage.getItem("token");
            axios.get('/api/user', {
                    headers: {
                        Authorization: "Bearer " + token
                    }
                })
                .then(response => {
                    app.user_id = response.data.user.id;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getAllItineraries() {
            let app = this;
            let token = localStorage.getItem("token");
            axios.get('/api/getAllItineraries', {
                    headers: {
                        Authorization: "Bearer " + token
                    }
                })
                .then(function(response) {
                    let itineraries = response.data.data;
                    // app.itineraries = response.data.data;
                    // console.log(app.itineraries);
                    // console.log('itineraries', response.data.data);

                    itineraries.forEach((itinerary) => {
                        let start = moment(itinerary.start_date).subtract(1, 'd');
                        let end = moment(itinerary.end_date);
                        end.from(start); // "in 5 days"
                        let day = end.from(start, true); // "5 days"


                        itinerary.start_date = moment(itinerary.start_date).format('MMM Do YYYY');
                        itinerary.end_date = moment(itinerary.end_date).format('MMM Do YYYY');

                        app.itineraries.push({
                            itinerary: itinerary,
                            days: day
                        });
                    })
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getCityData(city) {
            let app = this;
            axios.get(`/api/get-city/${city}`)
                .then(response => {
                    console.log(response.data.data);
                    let results = response.data.data;

                    results.forEach((result) => {
                        app.images.push(result.image);
                        // console.log('District:', app.district);
                    })
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        addToBookmarks(id) {
            let app = this;
            let token = localStorage.getItem("token");
            if (token !== null) {
                let data = {
                    user_id: app.user_id,
                    restaurant_id: null,
                    itinerary_id: id,
                    attraction_id: null
                }
                axios.post('/api/createBookmarks', data, {
                        headers: {
                            Authorization: "Bearer " + token
                        }
                    })
                    .then(response => {
                        console.log("Itinerary SUCCESSFULLY ADDED TO BOOKMARK");
                        // console.log(response.data);
                        app.alertMessage = "Added to bookmarks!";
                        app.show_alert = true;
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            } else {
                app.$router.push({
                    name: 'login'
                });
                window.scrollTo(0, 0);
            }
        },
    }
}
</script>
<style>
</style>

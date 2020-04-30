<template>
<div class="container">
    <div class="row">
        <div class="col-12">
          <div class="row" style="margin-top: 200px;">
            <div class="col-lg-4 order-2">
                <div class="justify-content-center text-center avatar">
                    <img v-if="user.image === 'image.jpg'" class="img-fluid" :src="publicPath + user.image" />
                    <img v-else class="img-fluid " :src="storagePath + user.image" />
                </div>
                <div class="profile-deets">
                    <p>{{user.name}}</p>
                    <p>{{user.email}}</p>
                </div>
                <div class="col-12 section-button mt-2">
                    <button type="submit" class="btn btn-primary" @click="showModal" id="show_modal">Change Avatar</button>
                </div>
            </div>

            <div class="col-lg-6 offset-lg-1 order-lg-2">
                <b-tabs content-class="mt-3" fill pills>
                    <b-tab title="ITINERARIES">
                        <div v-for="(itinerary, index) in itineraries">
                            <router-link :to="{ name: 'itinerary', params: {itinerary_id: itinerary.id} }">{{days[index]}} trip in {{itinerary.destination}}</router-link>
                            <button type="button" class="btn btn-primary float-right" @click="deleteItinerary(itinerary.id)">DELETE</button>
                            <p class="mt-2">
                                {{itinerary.start_date}} to {{itinerary.end_date}}
                            </p>
                            <hr />
                        </div>
                        <div class="mb-3" v-if="noItineraries">
                            <div class="row">
                                <div class="col-md-12 mt-2">
                                    <h3>{{this.imessage}}</h3>
                                </div>
                            </div>
                        </div>
                    </b-tab>
                    <b-tab title="BOOKMARKS">
                        <div v-for="bookmark in bookmarks">
                            <div v-if="bookmark.key === 0">
                                <router-link :to="{ name: 'restaurant', params: {res_id: bookmark.restaurants.id} }">
                                    {{bookmark.restaurants.name}}
                                </router-link>
                                <button type="button" class="btn btn-primary float-right" @click="deleteBookmark(bookmark.bookmark_id)">DELETE</button>
                                <p class="mt-2">
                                    {{bookmark.restaurants.location.locality_verbose}}
                                </p>
                            </div>
                            <div v-else-if="bookmark.key === 1">
                                <router-link :to="{ name: 'attraction', params: {att_id: bookmark.attractions.id} }">{{bookmark.attractions.name}}</router-link>
                                <button type="button" class="btn btn-primary float-right" @click="deleteBookmark(bookmark.bookmark_id)">DELETE</button>
                                <p class="mt-2">
                                    {{bookmark.attractions.addresses.address1}}, {{bookmark.attractions.addresses.city.name}}
                                </p>
                            </div>
                            <div v-else-if="bookmark.key === 2">
                                <router-link :to="{ name: 'itinerary', params: {itinerary_id: bookmark.itineraries.id} }">Itinerary: Trip to {{bookmark.itineraries.destination}}</router-link>
                                <button type="button" class="btn btn-primary float-right" @click="deleteBookmark(bookmark.bookmark_id)">DELETE</button>
                                <p class="mt-2">
                                    By {{bookmark.itineraries.user.name}}
                                </p>
                            </div>
                            <hr />

                        </div>
                        <div class="mb-3 body-content" v-if="noBookmarks">
                            <div class="row">
                                <div class="col-md-12 mt-2">
                                    <h3>{{this.bmessage}}</h3>
                                </div>
                            </div>
                        </div>
                    </b-tab>
                </b-tabs>
            </div>
            <editprofile v-if="show_modal" @submit="show_modal = false" @close="hide()"> </editprofile>
          </div>
        </div>
    </div>
</div>
</template>
<script>
import editprofile from '../components/editprofile'
var moment = require('moment');

const ZomatoBASEURL = "https://developers.zomato.com/api/v2.1/restaurant?";
const API_key = "223bdf4a4bffe3c07d3766b4be4efa5a";

const config = {
    headers: {
        'user-key': API_key
    }
};

//get restaurant URL
function getRestaurant(res_id) {
    return ZomatoBASEURL + "res_id=" + res_id;
}

export default {
    name: 'myprofile',
    components: {
        editprofile
    },
    data() {
        return {
            bookmarks: [],
            allBookmarks: [],
            user: "",
            moment: moment,
            show_modal: false,
            itineraries: [],
            days: [],
            bmessage: "",
            noBookmarks: false,
            imessage: "",
            noItineraries: false,
            defaultImage: '',
            publicPath: '/images/',
            storagePath: '../storage/images/'
        }
    },
    mounted() {
        this.viewItineraries();
        this.getUser();
        this.viewBookmark();
    },
    created() {
        this.debouncedGetUser = _.debounce(this.getUser);
    },
    methods: {
        hide() {
            this.show_modal = false;
            this.debouncedGetUser();
        },
        viewBookmark() {
            let app = this;
            // app.allBookmarks = [];
            app.bookmarks = [];
            let token = localStorage.getItem("token");
            axios.get('/api/viewbookmarks', {
                    headers: {
                        Authorization: "Bearer " + token
                    }
                })
                .then(response => {
                    if (response.data.data == "") {
                        console.log("NO BOOKMARKS");
                        app.bmessage = "You have no bookmarks!"
                        app.noBookmarks = true;
                    } else {
                        let results = response.data.data;
                        app.allBookmarks = results;
                        // console.log('results', results);
                        this.viewRestaurant();
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        viewRestaurant() {
            let app = this;
            //for each bookmarks restaurant_id, get restaurant data
            app.allBookmarks.forEach((bookmark) => {

                if (bookmark.restaurant_id !== null) {
                    let res_id = bookmark.restaurant_id;
                    // console.log('res', res_id);
                    axios.get(getRestaurant(res_id), config)
                        .then(response => {
                            let results = response.data;
                            // console.log('restaurants:', results);
                            app.bookmarks.push({
                                bookmark_id: bookmark.id,
                                attractions: null,
                                restaurants: results,
                                itineraries: null,
                                key: 0
                            });
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                } else if (bookmark.attraction_id !== null) {
                    app.bookmarks.push({
                        bookmark_id: bookmark.id,
                        attractions: bookmark.attraction,
                        restaurants: null,
                        itineraries: null,
                        key: 1
                    });
                } else {
                    app.bookmarks.push({
                        bookmark_id: bookmark.id,
                        attractions: null,
                        restaurants: null,
                        itineraries: bookmark.itinerary,
                        key: 2
                    });
                }
            })
        },
        getUser() {
            let app = this;
            let token = localStorage.getItem("token");
            axios.get('/api/user', {
                    headers: {
                        Authorization: "Bearer " + token
                    }
                })
                .then(response => {
                    app.user = response.data.user;
                    console.log('user', app.user);

                    // console.log(app.user);

                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        showModal() {
            this.show_modal = true;
        },
        viewItineraries() {
            let app = this;
            app.itineraries = [];
            let token = localStorage.getItem("token");
            axios.get('/api/viewItineraries', {
                    headers: {
                        Authorization: "Bearer " + token
                    }
                })
                .then(response => {
                    if (response.data.data == "") {
                        console.log("NO ITINERARIES");
                        app.imessage = "You have not created any itineraries!";
                        app.noItineraries = true;
                    } else {
                        let results = response.data.data;
                        results.forEach((result) => {
                            let start = moment(result.start_date).subtract(1, 'd');
                            let end = moment(result.end_date);
                            end.from(start); // "in 5 days"
                            let day = end.from(start, true); // "5 days"
                            app.days.push(day);

                            //change start date format to Month Day Year
                            let sdate_format = moment(result.start_date).format('MMM Do YYYY');
                            result.start_date = sdate_format;

                            //change end_date format to Month Day Year
                            let edate_format = moment(result.end_date).format('MMM Do YYYY');
                            result.end_date = edate_format;
                            app.itineraries.push(result);
                        })
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        deleteBookmark(id) {
            // console.log('bookmark id:', id);
            let token = localStorage.getItem("token");
            axios.delete(`/api/deleteBookmark/${id}`, {
                    headers: {
                        Authorization: "Bearer " + token
                    }
                })
                .then(response => {
                    console.log(response);
                    this.viewBookmark();
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        deleteItinerary(id) {
            // console.log('itinerary id:', id);
            let token = localStorage.getItem("token");
            axios.delete(`/api/deleteItinerary/${id}`, {
                    headers: {
                        Authorization: "Bearer " + token
                    }
                })
                .then(response => {
                    console.log(response);
                    this.viewItineraries();
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
    }
}
</script>
<style scoped>
.profile-deets {
    font-family: "Poppins";
    text-align: center;
    align-content: center;
}

.editbtn {
    color: white;
    margin-top: 30px;
    margin-bottom: 30px;
}

.avatar {
    border-radius: 50%;
    margin: 40px 0px;
}

</style>

<template>
<div class="container">
    <div class="body-content">
        <div class="row">

            <div class="col-lg-6 offset-lg-3 col-sm-12">

                <div class="col-12 auth-form-heading">
                    <h2>Create a Travel Itinerary</h2>
                </div>

                <form v-on:submit.prevent="createItinerary">
                    <div class="row">

                        <div class="form-group col-12">
                            <label for="destination">Destination</label>
                            <input id="destination" type="text" class="form-control" v-on:keyup="getSearchData" placeholder="Enter city" v-model="itinerary.destination" name="destination" autocomplete="off">
                            </input>
                            <div class="dropdown-results" v-if="results.length">
                                <div class="dropdown" v-for="result in results" aria-haspopup="true" aria-expanded="false">
                                    <a class="dropdown-item">
                                        <li class="dropdown-item" @click="getResult(result.name)">{{ result.name }}, {{result.districts.country.name}}</li>
                                    </a>
                                </div>
                            </div>
                            <span class="errors">{{ getErrors('destination') }}</span>
                        </div>

                        <div class="form-group col-6">
                            <label for="start_date">Start Date</label>
                            <input id="start_date" type="date" class="form-control" v-model="itinerary.start_date" name="start_date" required>
                        </div>

                        <div class="form-group col-6">
                            <label for="end_date">End Date</label>
                            <input id="end_date" type="date" class="form-control" v-model="itinerary.end_date" name="end_date" required>
                        </div>

                        <div class="form-button col-12">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>

                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
</template>

<script>
import itinerarybuilderNav from '../components/itinerarybuilderNav'

export default {
    name: 'createItinerary',
    props: ['app', 'user'],
    components: {
        itinerarybuilderNav,
    },
    mounted() {
      window.scrollTo(0, 0);
        console.log("Create itinerary page");
    },
    data() {
        return {
            itinerary: {},
            errors: [],
            search: '',
            results: [],
        }
    },
    methods: {
        createItinerary() {
            let app = this;
            let token = localStorage.getItem("token");

            axios.post('/api/createitinerary', {
                    destination: app.itinerary.destination,
                    start_date: app.itinerary.start_date,
                    end_date: app.itinerary.end_date,
                    user_id: app.user.id
                }, {
                    headers: {
                        'Authorization': "Bearer " + token
                    },
                })
                .then(function(response) {
                    console.log("Itinerary Id:" + response.data.data.id);
                    app.itinerary.id = response.data.data.id;
                    //go to addtoitinerary page and pass parameters
                    app.$router.push({
                        name: 'addtoitinerary',
                        params: {
                            destination: app.itinerary.destination
                        },
                        query: {
                            itinerary_id: app.itinerary.id
                        }
                    });
                })
                .catch(function(error) {
                    app.errors = error.response.data.errors;
                    console.log(error.response.data.errors);
                });
        },
        getErrors(field) {
            if (this.errors[field]) {
                return this.errors[field][0];
            }
        },
        getSearchData() {
            this.results = [];
            if (this.itinerary.destination.length > 0) {
                axios.get('/api/search', {
                    params: {
                        search: this.itinerary.destination
                    }
                }).then(response => {
                    this.results = response.data;
                });
            }
        },
        getResult(result) {
            // this.results = [];
            console.log(result);
            this.itinerary.destination = result;
            this.results = [];
        }
    }
}
</script>

<style scoped>
.auth-form-heading h2 {
    margin-bottom: 0px;
}
</style>

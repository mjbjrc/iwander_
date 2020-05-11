<template>
<div class="container">
    <div class="body-content">
        <div class="row">

            <div class="col-lg-4 offset-lg-4 col-sm-12">

                <div class="col-12 auth-form-heading">
                    <h2>Settings</h2>
                    <p>Edit Account Info</p>
                </div>
                <!-- PROFILE FORM -->
                <form v-on:submit.prevent="editProfile">
                    <div class="row">
                        <div class="form-group col-12">
                            <label for="name">Name</label>
                            <input id="name" type="text" v-model="user.name" class="form-control" name="name" required autocomplete="name" autofocus>
                            <span class="errors">{{ getErrors('name') }}</span>
                        </div>

                        <div class="form-group col-12">
                            <label for="email">Email Address</label>
                            <input id="email" v-model="user.email" type="email" class="form-control" name="email" required autocomplete="email">
                            <span class="errors">{{ getErrors('email') }}</span>
                        </div>

                        <div class="form-group col-12">
                            <label for="password">Password</label>
                            <input id="password" v-model="user.password" type="password" class="form-control" name="password" autocomplete="password">
                            <span class="errors">{{ getErrors('password') }}</span>
                        </div>

                        <div class="form-group col-12">
                            <label for="password_confirmation">Confirm Password</label>
                            <input id="password_confirmation" v-model="user.password_confirmation" type="password" class="form-control" name="password_confirmation" autocomplete="password_confirmation">
                            <span class="errors">{{ getErrors('password') }}</span>
                        </div>

                        <div class="form-button col-12">
                            <button class="btn btn-primary" type="submit">Save Changes</button>
                        </div>
                    </div>
                </form>
                <!-- END PROFILE FORM -->
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: 'settings',
    props: ['app'],
    components: {},
    data() {
        return {
            user: {},
            errors: []
        }
    },
    created() {
        window.scrollTo(0, 0);
        this.getUser();
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
                    console.log('user:', response.data.user);
                    app.user = response.data.user;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        editProfile() {
            let app = this;
            let token = localStorage.getItem("token");

            //update user details
            axios.post('/api/updateDetails', {
                    name: app.user.name,
                    email: app.user.email,
                    password: app.user.password,
                    password_confirmation: app.user.password_confirmation
                }, {
                    headers: {
                        Authorization: "Bearer " + token
                    }
                })
                .then(res => {
                    console.log(res);
                    //go back to my profile page
                    app.$router.push({
                        name: 'myprofile'
                    });
                })
                .catch(function(error) {
                    app.errors = error.response.data.errors;
                    console.log(error);
                });
        },
        getErrors(field) {
            if (this.errors[field]) {
                return this.errors[field][0];
            }
        },
    }
}
</script>

<style scoped>

</style>

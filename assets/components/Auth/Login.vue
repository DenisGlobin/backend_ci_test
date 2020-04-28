<template>
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Log in</h5>
                    <router-link :to="{ name: 'main_page' }" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </router-link>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="inputEmail">Please enter Email</label>
                            <input type="email" class="form-control" id="inputEmail" aria-describedby="emailHelp" v-model="login" required>
                            <div class="invalid-feedback" v-if="invalidLogin">
                                <!--                {{ errorMessage }}-->
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword">Please enter password</label>
                            <input type="password" class="form-control" id="inputPassword" v-model="pass" required>
                            <div class="invalid-feedback" v-show="invalidPass" v-html="errorMessage"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" @click.prevent="logIn">Login</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Login",
        mounted() {
            setTimeout(function () {
                $('#loginModal').modal('show');
            }, 100);
        },
        data() {
            return {
                login: '',
                pass: '',
                invalidLogin: false,
                invalidPass: false,
                errorMessage: '',
            }
        },
        methods: {
            logIn: function () {
                let self = this;
                let formData = new FormData();

                self.invalidLogin = false;
                self.invalidPass = false;

                formData.append("login", self.login);
                formData.append("password", self.pass);

                axios.post('/main_page/login', formData)
                    .then(function (response) {

                        if (response.data.error_message) {
                            self.invalidLogin = true;
                            self.errorMessage = response.data.error_message;
                            console.log(response.data.error_message);
                            self.invalidPass = true;
                        } else {
                            location.replace('/');
                        }
                    })
            },
        }
    }
</script>

<style scoped>

</style>
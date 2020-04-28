<template>
    <div>
        <div class="header">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">

                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">

                    <ul class="navbar-nav">
                        <li v-if="auth" class="nav-item">
                        <a href="/main_page/logout" class="btn btn-primary my-2 my-sm-0">
                            Log out, {{ userName }}
                        </a>
                        </li>
                        <li v-else class="nav-item">
                            <router-link :to="{ name: 'login' }" type="button" class="btn btn-success my-2 my-sm-0">Log IN</router-link>
                        </li>
                        <li v-if="auth" class="nav-item">
                            <router-link :to="{ name: 'fill.balance' }" type="button" class="btn btn-success my-2 my-sm-0">Fill in balance</router-link>
                        </li>
                        <li v-if="auth" class="nav-item">
                            <router-link :to="{ name: 'purchase.likes' }" type="button" class="btn btn-success my-2 my-sm-0">Purchase likes</router-link>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="main">
            <div class="boosterpacks">
                <h1 class="text-center">Boosterpack's</h1>
                <div class="container">
                    <div class="row">
                        <div class="col-4" v-for="box in packs" v-if="packs">
                            <div class="card">
                                <img :src="'/images/box.png'" class="card-img-top" alt="Photo">
                                <div class="card-body">
                                    <button type="button" class="btn btn-outline-success my-2 my-sm-0" @click="buyPack(box.id)">Buy boosterpack {{box.price}}$
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        name: "App",
        data() {
            return {
                auth: false,
                userName: '',
                posts: [],
                packs: [
                    {
                        id: 1,
                        price: 5
                    },
                    {
                        id: 2,
                        price: 20
                    },
                    {
                        id: 3,
                        price: 50
                    },
                ],
            }
        },
        created() {
            let self = this;

            axios
                .get('/main_page/get_auth_user')
                .then(function (response) {
                    self.auth = response.data.auth;
                    if (typeof response.data.user_name !== 'undefined') {
                        self.userName = response.data.user_name;
                    }
                });

            axios
                .get('/main_page/get_all_posts')
                .then(function (response) {
                    self.posts = response.data.posts;
                });
        },
        methods: {
            // buyPack: function (id) {
            //     let self = this;
            //     axios.post('/main_page/buy_boosterpack', {
            //         id: id,
            //     })
            //         .then(function (response) {
            //             self.amount = response.data.amount;
            //             if(self.amount !== 0){
            //                 setTimeout(function () {
            //                     $('#amountModal').modal('show');
            //                 }, 500);
            //             }
            //         });
            // }
        },
    }
</script>

<style scoped>

</style>
<template>
    <div class="posts">
        <h1 class="text-center">Posts</h1>
        <div class="container">
            <div class="row" v-if="posts">
                <div class="col-4" v-for="post in posts">
                    <div class="card">
                        <img :src="post.img" class="card-img-top" alt="Photo">
                        <div class="card-body">
                            <h5 class="card-title">Post - {{post.id}}</h5>
                            <p class="card-text">{{post.text}}</p>
                            <!--<button type="button" class="btn btn-outline-success my-2 my-sm-0" @click="openPost(post.id)">Open post-->
                            <!--</button>-->
                            <router-link :to="{ name: 'post.show', params: { id: post.id } }" type="button" class="btn btn-success my-2 my-sm-0">Open post</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PostList",
        // props: {
        //     post: {
        //         type: Object,
        //         required: true
        //     }
        // },
        data() {
            return {
                auth: false,
                userName: '',
                login: '',
                pass: '',
                post: false,
                invalidLogin: false,
                invalidPass: false,
                invalidSum: false,
                errorMessage: '',
                posts: [],
                addSum: 0,
                likesBalance: 0,
                moneyBalance: 0.0,
                postLikes: 0,
                commentLikes: 0,
                commentLikedId: 0,
                commentText: '',
                assignCommentID: null,
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

            axios
                .get('/main_page/get_user_balance')
                .then(function (response) {
                    self.likesBalance = response.data.likes_balance;
                    self.moneyBalance = response.data.wallet_balance;
                });
        },
        methods: {
            logIn: function () {
                let self = this;
                let formData = new FormData();

                self.invalidLogin = false;
                self.invalidPass = false;

                formData.append("login",self.login);
                formData.append("password",self.pass);

                axios.post('/main_page/login', formData)
                    .then(function (response) {

                        if (response.data.error_message) {
                            self.invalidLogin = true;
                            self.errorMessage = response.data.error_message;
                            console.log(response.data.error_message);
                            self.invalidPass = true;
                        } else {
                            location.reload();
                        }
                    })
            },
            // openPost: function (id, post) {
            //     let self = this;
            //     self.post = post;
            //     axios
            //         .get('/main_page/get_post/' + id)
            //         .then(function (response) {
            //             self.post = response.data.post;
            //             // console.log(self.post.comments);
            //             // self.setPadding();
            //             if (self.post) {
            //                 setTimeout(function () {
            //                     $('#postModal').modal('show');
            //                 }, 500);
            //             }
            //         })
            // },
            fiilIn: function () {
                let self = this;
                if(self.addSum === 0) {
                    self.invalidSum = true
                }
                else {
                    self.invalidSum = false;
                    let formData = new FormData();
                    formData.append("sum", self.addSum);

                    axios.post('/main_page/add_money', formData)
                        .then(function (response) {
                            setTimeout(function () {
                                $('#addModal').modal('hide');
                            }, 500);
                            self.addSum = 0;
                        })
                }
            },
            addLike: function (objType, id) {
                let self = this;
                let formData = new FormData();

                formData.append("id", id);
                formData.append("obj_type", objType);

                axios
                    .post('/main_page/like', formData)
                    .then(function (response) {
                        if (typeof response.data.post_likes !== 'undefined') {
                            self.postLikes = response.data.post_likes;
                        }
                        if (typeof response.data.comment_likes !== 'undefined') {
                            self.commentLikedId = response.data.comment_id;
                            self.commentLikes = response.data.comment_likes;
                        }
                    })

            },
            buyLikes: function () {
                let self = this;
                let likesAmount = $("select#amountSelectPref").val();

                let formData = new FormData();
                formData.append("likes_amount", likesAmount);

                axios.post('/main_page/buy_likes', formData)
                    .then(function (response) {
                        self.likesBalance = response.data.likes_balance;
                        self.moneyBalance = response.data.wallet_balance;
                    });
            },
            buyPack: function (id) {
                let self = this;
                axios.post('/main_page/buy_boosterpack', {
                    id: id,
                })
                    .then(function (response) {
                        self.amount = response.data.amount;
                        if(self.amount !== 0){
                            setTimeout(function () {
                                $('#amountModal').modal('show');
                            }, 500);
                        }
                    });
            }
        },
    }
</script>

<style scoped>

</style>
<template>
    <div class="modal fade bd-example-modal-xl" id="postModal" tabindex="-1" role="dialog" aria-labelledby="postModal" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Post {{post.id}}</h5>
                    <router-link :to="{ name: 'main_page' }" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </router-link>
                </div>
                <div class="modal-body">
                    <div class="user">
                        <div class="avatar"><img :src="post.user.avatarfull" alt="Avatar"></div>
                        <div class="name">{{post.user.personaname}}</div>
                    </div>
                    <div class="card mb-3">
                        <div class="post-img" :style="{ backgroundImage: 'url(' + post.img + ')' }"></div>
                        <div class="card-body">
                            <div class="likes">
                                <div class="heart-wrap" v-if="!postLikes">
                                    <div class="heart" @click="addLike('post', post.id)">
                                        <svg class="bi bi-heart" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 01.176-.17C12.72-3.042 23.333 4.867 8 15z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span>{{post.likes}}</span>
                                </div>
                                <div class="heart-wrap" v-else>
                                    <div class="heart" @click="addLike('post', post.id)">
                                        <svg class="bi bi-heart-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span>{{postLikes}}</span>
                                </div>
                            </div>
                            <CommentList
                                    :comments="post.comments"
                            />
                            <br>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <router-link :to="{ name: 'main_page' }" type="button" class="btn btn-secondary" data-dismiss="modal">Back to main page</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CommentList from "../Comments/CommentList";
    export default {
        name: "PostItem",
        components: {
            CommentList
        },
        mounted() {
            let id = this.$route.params.id;
            let self = this;

            axios
                .get('/main_page/get_post/' + id)
                .then(function (response) {
                    self.post = response.data.post;
                    // console.log(self.post.comments);
                    if (self.post) {
                        setTimeout(function () {
                            $('#postModal').modal('show');
                        }, 100);
                    }
                });
        },
        data() {
            return {
                post: {},
                errorMessage: '',
                postLikes: 0,
                // commentLikes: 0,
            }
        },
        methods: {
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
                        // if (typeof response.data.comment_likes !== 'undefined') {
                        //     self.commentLikedId = response.data.comment_id;
                        //     self.commentLikes = response.data.comment_likes;
                        // }
                    })

            },
        }
    }
</script>

<style scoped>

</style>
<template>
    <div>
        <div class="form-inline" :class="{ 'valid-feedback': isSelected }">
            <p class="card-text">
                <small class="text-muted">{{comment.time_created + ' / '}}</small>
                <strong>{{comment.user.personaname + ' - '}}</strong>
                <a href="#" @click="respondComment(comment.id)" class="btn-light">Ответить</a>
            </p>
            <div class="likes" style="margin-left: 10px">
                <div class="heart-wrap" v-if="comment.id !== commentLikedId">
                    <div class="heart" @click="addLike('comment', comment.id)">
                        <svg class="bi bi-heart" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 01.176-.17C12.72-3.042 23.333 4.867 8 15z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span>{{comment.likes}}</span>
                </div>
                <div class="heart-wrap" v-else>
                    <div class="heart" @click="addLike('comment', comment.id)">
                        <svg class="bi bi-heart-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span>{{commentLikes}}</span>
                </div>
            </div>
        </div>
        <p>{{comment.text}}</p>

        <ul class="list-none mx-10" v-if="comment.child_node && comment.child_node.length > 0">
            <CommentItem
                    v-for="(child, subIndex) in comment.child_node"
                    :comment="child"
                    :index="subIndex"
                    :key="child.id"
                    @respond="setAssignComment"
                />
        </ul>

    </div>
</template>

<script>
    export default {
        name: "CommentItem",
        data() {
            return {
                post: {},
                errorMessage: '',
                likesBalance: 0,
                moneyBalance: 0.0,
                // postLikes: 0,
                commentLikes: 0,
                isSelected: false
            }
        },
        props: {
            comment: {
                type: Object,
                required: true
            },
            index: {
                type: Number,
                required: true
            },
            // selected: {
            //     required: false,
            // }
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
                        // if (typeof response.data.post_likes !== 'undefined') {
                        //     self.postLikes = response.data.post_likes;
                        // }
                        if (typeof response.data.comment_likes !== 'undefined') {
                            self.commentLikedId = response.data.comment_id;
                            self.commentLikes = response.data.comment_likes;
                        }
                    })

            },
            // isSelectedComment: function () {
            //     return (this.comment.id === this.selected)
            // },
            respondComment: function (id) {
                // this.isSelected = true;
                this.$emit('respond', id);
            },
            setAssignComment: function (id) {
                this.$parent.setAssignComment(id);
            }
        }
    }
</script>

<style scoped>

</style>
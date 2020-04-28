<template>
    <div>
       <CommentItem
               v-for="(comment, index) in comments"
               :index="index"
               :comment="comment"
               :key="comment.id"
               @respond="setAssignComment"
       />
        <form id="addCommentForm" class="form-inline">
            <div class="form-group">
                <input type="text" class="form-control" id="addComment" v-model="commentText">
            </div>
            <button type="submit" class="btn btn-primary" @click.prevent="addComment(post.id)">Add comment</button>
        </form>
    </div>
</template>

<script>
    import CommentItem from "./CommentItem";
    export default {
        name: "CommentList",
        components: {
            CommentItem
        },
        data() {
            return {
                assignCommentID: null,
                commentText: '',
            }
        },
        props: {
            comments: {
                type: Array
            }
        },
        methods: {
            setAssignComment: function (id) {
                // Show button for cancel edition
                $("form#addCommentForm").before("" +
                    "<div class='alert alert-warning alert-dismissible fade show' role='alert' id='cancelEdt'>\n" +
                    "        <strong>Написать ответ</strong>\n" +
                    "        <button type='button' class='close' data-dismiss='alert' aria-label='Close' @onclick='responseCancel'>\n" +
                    "            <span aria-hidden='true'>&times;</span>\n" +
                    "        </button>\n" +
                    "</div>");
                this.assignCommentID = id;
            },
            responseCancel: function () {
                this.assignCommentID = null;
                this.selectedComment = false;
            },
            addComment: function (postID) {
                let self = this;
                let formData = new FormData();

                formData.append("post_id", postID);
                formData.append("message", self.commentText);
                formData.append("assign_comment", self.assignCommentID);

                axios.post('/main_page/store_comment', formData)
                    .then(function (response) {
                        self.$parent.post = response.data.post;
                        self.commentText = '';
                        self.assignCommentID = null;
                        self.selectedComment = false;
                    });
            },
        }
    }
</script>

<style scoped>

</style>
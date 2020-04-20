var app = new Vue({
	el: '#app',
	data: {
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
	},
	computed: {
		test: function () {
			let data = [];
			return data;
		}
	},
	created(){
		let self = this;
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
            })
	},
	methods: {
		// logout: function () {
		// 	console.log ('logout');
		// },
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
		openPost: function (id) {
			let self = this;
			axios
				.get('/main_page/get_post/' + id)
				.then(function (response) {
					self.post = response.data.post;
					self.setPadding();
					if(self.post){
						setTimeout(function () {
							$('#postModal').modal('show');
						}, 500);
					}
				})
		},
        getPadding: function (comment) {
            return comment.paddingL;
        },
		setPadding: function () {
            let self = this;
			if (self.post.comments.length > 0)	{
                self.post.comments.forEach(function (comment) {
                    if (comment.assign_comment_id !== null) {
                        let padding = self.getParentTab(comment.assign_comment_id);
                        comment.paddingL = padding + 24;
					} else {
                        comment.paddingL = 0;
                    }
                });
			}
		},
		getParentTab: function (id) {
            let self = this;
            let tabulation = 0;
            // Search parent comment.
            self.post.comments.forEach(function (comment) {
				if (comment.id === id) {
					if (typeof comment.paddingL !== 'undefined') {
                        // console.log("Parent id: "+ comment.id + " Parent tab: " + comment.paddingL + " Type: " + typeof comment.paddingL);
                        // Get parent padding
						tabulation = Number(comment.paddingL);
                    }
				}
            });
            return tabulation;
		},
        setAssignComment: function (id) {
            // Show button for cancel edition
            $("form#addCommentForm").before("" +
                "<div class='alert alert-warning alert-dismissible fade show' role='alert' id='cancelEdt'>\n" +
                "        <strong>Написать ответ</strong>\n" +
                "        <button type='button' class='close' data-dismiss='alert' aria-label='Close' onclick='responseCancel()'>\n" +
                "            <span aria-hidden='true'>&times;</span>\n" +
                "        </button>\n" +
                "</div>");
			this.assignCommentID = id;
		},
        responseCancel: function () {
			this.assignCommentID = null;
		},
		addComment: function (postID) {
            let self = this;
            let formData = new FormData();

            formData.append("postID", postID);
            formData.append("message", self.commentText);
            formData.append("assignComment", self.assignCommentID);

            axios.post('/main_page/store_comment', formData)
                .then(function (response) {
                    self.post = response.data.post;
                    self.commentText = '';
                    self.assignCommentID = null;
                    self.setPadding();
                });
		},
		addLike: function (objType, id) {
			let self = this;
            let formData = new FormData();

            formData.append("id", id);
            formData.append("objType", objType);

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
            formData.append("likesAmount", likesAmount);

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
				})
		}
	}
});


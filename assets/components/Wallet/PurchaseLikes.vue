<template>
    <div class="modal fade" id="amountModal" tabindex="-1" role="dialog" aria-labelledby="amountModal" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Amount</h5>
                    <router-link :to="{ name: 'main_page' }" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </router-link>
                </div>
                <div class="modal-body">
                    <h2 class="text-center">Likes: {{likesBalance}}</h2>
                    <h2 class="text-center">Wallet balance: {{moneyBalance}}</h2>
                    <hr>
                    <div class="form-inline">
                        <label class="my-1 mr-2" for="amountSelectPref">Likes available to buy:</label>
                        <select class="custom-select my-1 mr-sm-2" id="amountSelectPref">
                            <option v-for="money in moneyBalance">{{money}}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <router-link :to="{ name: 'main_page' }" type="button" class="btn btn-secondary" data-dismiss="modal">Close</router-link>
                    <button type="button" class="btn btn-success" @click="buyLikes">Buy</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PurchaseLikes",
        data() {
            return {
                likesBalance: 0,
                moneyBalance: 0.0,
            }
        },
        mounted() {
            let self = this;

            axios
                .get('/main_page/get_user_balance')
                .then(function (response) {
                    self.likesBalance = response.data.likes_balance;
                    self.moneyBalance = response.data.wallet_balance;
                });

            setTimeout(function () {
                $('#amountModal').modal('show');
            }, 100);
        },
        methods: {
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
        },
    }
</script>

<style scoped>

</style>
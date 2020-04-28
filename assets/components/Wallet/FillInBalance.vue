<template>
    <div class="modal fade bd-example-modal-sm" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModal" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add money</h5>
                    <router-link :to="{ name: 'main_page' }" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </router-link>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="addBalance">Enter sum</label>
                            <input type="text" class="form-control" id="addBalance" v-model="addSum" required>
                            <div class="invalid-feedback" v-if="invalidSum">
                                Please write a sum.
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" @click="fillIn">Add</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "FillInBalance",
        data() {
            return {
                invalidSum: false,
                addSum: 0,
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
                $('#addModal').modal('show');
            }, 100);
        },
        methods: {
            fillIn: function () {
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
        },
    }
</script>

<style scoped>

</style>
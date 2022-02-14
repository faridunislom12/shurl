<template>
    <div class="wrapper" style="display: table; width: 100%; height: 100%;">
        <section id="content vh-100" style="display: table-cell; vertical-align: middle;">
            <div class="container mt-4 mb-4" style="width:100%; max-width: 525px;">
                <!-- <div class="card rounded-0 auth-card" style="width: 30rem"> -->
                <div class="card rounded-0 auth-card">
                    <div class="card-body p-sm-5">
                        <div class="alert alert-danger" role="alert" v-if="message">
                            {{ message }}
                        </div>
                        <h2 class="card-title fw-bold">Вход</h2>
                        <form action="" class="" id="login" @submit.prevent="login" method="post">
                            <div class="form-floating mb-3" v-if="isLogin !== true">
                                <input required v-model="auth.login" ref="login" type="text" class="form-control feedback-input"
                                       id="name" placeholder="Введите телефон">
                                <!--                                       maxlength="9" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"-->

                                <label for="name">Телефон или email</label>
                            </div>
                            <div class="form-floating mb-5" v-if="isLogin === true">
                                <input required v-model="auth.password" ref="password" type="password"
                                       class="form-control feedback-input" id="password" placeholder="Введите пароль">
                                <label for="password">Пароль</label>
                            </div>
                        </form>
                    </div>
                </div>
                <button type="submit" form="login" class="btn btn-primary text-white w-100 rounded-0 mt-4 fs-4">
                    {{isLogin===true ? 'Вход' : 'Подтвердить'}}
                </button>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                isLogin: null,
                auth: {
                    login: '',
                    password: ''
                },
                message: ''
            }
        },
        mounted() {
            this.$refs.login.focus()
        },
        methods: {
            login() {
                this.message = '';
                if (this.isLogin !== true) {
                    axios.post(route('auth.check-login'), this.auth)
                        .then(response => {
                            console.log(response)
                            if (response.data.status === 'exist') {
                                this.isLogin = true;
                                this.$nextTick(() => {
                                    this.$refs.password.focus()
                                });
                            } else {
                                this.isLogin = false;
                                this.message = 'Логин не существует!';
                            }
                        })
                        .catch(error => {
                            this.message = error.response.data.message
                        })
                } else {
                    // if (this.auth.login.match(/^\d{9}$/)) {
                    axios.post(route('auth.sign-in'), this.auth)
                        .then(response => {
                            console.log('response')
                            console.log(response.data.status)
                            if (response.data.status === 'signed') {
                                window.location.href = route('urls.index')
                            }
                        })
                        .catch(error => {
                            console.log(error)
                            // this.message = error.response.data.message
                        })
                    // }
                }
            }
        }
    }
</script>

<style>

</style>

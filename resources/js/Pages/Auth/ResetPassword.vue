<template>
    <div class="wrapper align-self-center" style="display: table; height: 100%; width: 100%;">
        <section id="content vh-100" style="display: table-cell; vertical-align: middle;">
            <form class="container mb-4 mt-4" style="max-width: 525px; width: 100%;" v-if="step===1" @submit.prevent="resetPassword" method="post">
                <!-- <div class="card rounded-0 auth-card" style="width: 30rem"> -->
                <div class="card rounded-0 auth-card">
                    <div class="card-body p-sm-5">
                        <h2 class="card-title fw-bold">Восстановить пароль</h2>
                        <form action="" class="" id="register">
                            <div class="form-floating mb-3">
                                <input type="text" v-model="auth.login" class="form-control feedback-input" id="name" placeholder="Введите email">
                                <label for="name">Введите email</label>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <a :href="route('auth.login')" class="btn btn-outline-dark w-100 rounded-0 mt-2 mt-md-4 fs-4 btn-login">Вход</a>
                    </div>
                    <div class="col-12 col-md-6">
                        <button type="submit" class="btn btn-danger text-white  w-100 rounded-0 mt-2 mt-md-4 fs-4">Восстановить</button>
                    </div>
                </div>
            </form>
            <form @submit.prevent="confirmCode" method="post" style="max-width: 525px; width: 100%;"
                class="container mt-4 mb-4" v-if="step===2">
                <!-- <div class="card rounded-0 auth-card" style="width: 30rem"> -->
                <div class="card rounded-0 auth-card">
                    <div class="card-body p-lg-5">
                        <h2 class="card-title fw-bold">Введите код подтверждения</h2>
                        <div>
                            <div class="form-floating mb-3">
                                <input required v-model="register.confirmCode" type="text" maxlength="4"  class="form-control feedback-input" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" id="name" placeholder="Введите код подтверждения">
                                <label for="name">Код подтверждения</label>
                            </div>
                            <div class="col">
                                <p>Отправить код повторно
                                    <span class="text-secondary" v-if="!resent">(Через {{ confirmTime }} секунд)</span>
                                    <button type="button" @click="resentCode" v-if="resent" class="btn btn-primary btn-sm">Повторить</button>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 com-sm-6">
                        <a :href="route('auth.login')" class="btn btn-outline-dark w-100 rounded-0 mt-4 fs-4 btn-login">Вход</a>
                    </div>
                    <div class="col-12 col-sm-6">
                        <button type="submit" class="btn btn-danger text-white  w-100 rounded-0 mt-4 fs-4">Регистрация</button>
                    </div>
                </div>
            </form>
        </section>
    </div>
</template>

<script>
export default {
    data() {
        return {
            auth: {
                login: '',
                confirm: ''
            },
            confirmTime: "00:00",
            step: 1,
            resent: true,
            isDisable: false
        }
    },
    methods: {
        resetPassword() {
            this.isDisable = true
            if (this.auth.login) {
                axios.post(route('auth.confirm.password', 'reset-password'), this.auth)
                    .then(response=> {
                        if (this.step === 1) {
                            this.confirmTimer(5)
                            this.step = 2
                        }
                    })
                    .catch(error=> {
                        this.$swal({
                            position: 'top-end',
                            icon: 'error',
                            title: error.response.data.message,
                            showConfirmButton: true,
                            timer: 2000
                        })
                    })
                    .finally(response=> {
                        this.isDisable = false
                    })
            } else {
                 this.$swal({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Введите корректные данные',
                    showConfirmButton: true,
                    timer: 2000
                })
            }
        },
        confirmTimer(duration) {
            let _this = this
            this.resent = false
            var timer = duration, minutes, seconds;
            let clearTimer = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;
                _this.confirmTime = minutes + ":" + seconds
                if (--timer < 0) {
                    _this.confirmTime = '00:00'
                    _this.resent = true
                    clearInterval(clearTimer)
                }
            }, 1000);
        }
    }
}
</script>

<style>

</style>

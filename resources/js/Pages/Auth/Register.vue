<template>
    <div class="wrapper" style="display: table; width: 100%; height: 100%;">
        <section id="content vh-100" style="display: table-cell; vertical-align: middle;">
            <form @submit.prevent="registerUser" method="post" class="container mt-4 mb-4" style="width: 100%; max-width: 525px;"
             v-if="step===1">
                <!-- <div class="card rounded-0 auth-card" style="width: 30rem"> -->
                <div class="card rounded-0 auth-card">
                    <div class="card-body p-sm-5">
                        <p class="card-text lh-lg"><a :href="route('home')" class="text-dark">Главная</a>
                        </p>
                        <h2 class="card-title fw-bold">Регистрация</h2>
                        <div>
                            <div class="form-floating mb-3">
                                <div class="btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-white">
                                        <input type="radio" name="options" id="option1" checked="" @click="register.country='tj'"> <i
                                        class="fe fe-check-circle"></i> Таджикистан
                                    </label>
                                    <label class="btn btn-white active">
                                        <input type="radio" name="options" id="option2" @click="register.country='other'"> <i
                                        class="fe fe-check-circle"></i> Другая страна
                                    </label>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input v-model="register.name" required type="text" class="form-control feedback-input"
                                       id="name" placeholder="Введите имя">
                                <label for="name">Имя</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="register.email" type="email" class="form-control feedback-input"
                                       id="email" placeholder="Введите Email">
                                <label for="email">Email</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="register.phone" type="text" @keyup="validate" maxlength="9"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                       class="form-control feedback-input" id="phone"
                                       placeholder="Введите номер телефона">
                                <label for="phone">Номер телефона</label>
                                <small class="text-danger text-sm"
                                       v-if="vaidateMessage.vaidatePhone">{{ vaidateMessage.vaidatePhone }}</small>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="register.password" @keyup="validate" required type="password"
                                       class="form-control feedback-input" id="password" placeholder="Введите пароль">
                                <label for="password">Пароль</label>
                            </div>
                            <div class="form-floating mb-5">
                                <input v-model="register.confirmPassword" @keyup="validate" required type="password"
                                       class="form-control feedback-input" id="password" placeholder="Введите пароль">
                                <label for="password">Подвердите пароль</label>
                                <small class="text-danger text-sm" v-if="vaidateMessage.vaidateConfirmPassword">{{
                                    vaidateMessage.vaidateConfirmPassword
                                    }}</small>
                                <small class="text-danger text-sm" v-if="vaidateMessage.vaidatePasswordLength">{{
                                    vaidateMessage.vaidatePasswordLength
                                    }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <a :href="route('auth.login')" class="btn btn-outline-dark w-100 rounded-0 mt-2 mt-sm-4 fs-4 btn-login">Вход</a>
                    </div>
                    <div class="col-12 col-sm-6">
                        <button type="submit" :disabled="isDisable"
                                class="btn btn-danger text-white w-100 rounded-0 mt-2 mt-sm-4 fs-4">Получить код
                        </button>
                    </div>
                </div>
            </form>
            <form @submit.prevent="confirmCode" method="post" class="container" style="width: 100%; max-width: 525px;"
             v-if="step===2">
                <!-- <div class="card rounded-0 auth-card" style="width: 30rem"> -->
                <div class="card rounded-0 auth-card">
                    <div class="card-body p-sm-5">
                        <h2 v-if="register.country==='tj'" class="card-title fw-bold">Введите код подтверждения, который мы отправили на указанный номер телефона</h2>
                        <h2 v-if="register.country==='other'" class="card-title fw-bold">Введите код подтверждения, который мы отправили на указанный адрес электронной почты</h2>
                        <div>
                            <div class="form-floating mb-3">
                                <input required v-model="register.confirmCode" type="text" maxlength="4"
                                       class="form-control feedback-input"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                       id="name" placeholder="Введите код подтверждения">
                                <label for="name">Код подтверждения</label>
                            </div>
                            <div class="col">
                                <p>Отправить код повторно
                                    <span class="text-secondary" v-if="!resent">(Через {{ confirmTime }} секунд)</span>
                                    <button type="button" @click="resentCode" v-if="resent"
                                            class="btn btn-primary btn-sm">Повторить
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <a :href="route('auth.login')" class="btn btn-outline-dark w-100 rounded-0 mt-2 mt-md-4 fs-4 btn-login">Вход</a>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-danger text-white w-100 rounded-0 mt-2 mt-md-4 fs-4">Регистрация
                        </button>
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
            register: {
                name: '',
                email: '',
                phone: '',
                password: '',
                confirmPassword: '',
                confirmCode: '',
                country: 'tj',
            },
            vaidateMessage: {
                vaidateConfirmPassword: '',
                vaidatePasswordLength: '',
                vaidatePhone: ''
            },
            confirmTime: "00:00",
            step: 1,
            resent: true,
            isDisable: false,
        }
    },
    methods: {
        registerUser() {
            if (this.register.phone.length >= 9 || this.register.email) {
                this.isDisable = true
                axios.post(route('auth.register.store'), this.register)
                    .then(response => {
                        console.log(response)
                        if (this.step === 1) {
                            this.confirmTimer(5)
                            this.step = 2
                        }
                    })
                    .catch(error => {
                        this.$swal({
                            position: 'top-end',
                            icon: 'error',
                            title: error.response.data.message,
                            showConfirmButton: true,
                            timer: 1000000
                        })
                    })
                    .finally(response => {
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
        validate() {
            this.register.password !== '' && this.register.password === this.register.confirmPassword ? this.vaidateMessage.vaidateConfirmPassword = '' : this.vaidateMessage.vaidateConfirmPassword = 'Пароли не совпадают.'
            this.register.password.length >= 7 ? this.vaidateMessage.vaidatePasswordLength = '' : this.vaidateMessage.vaidatePasswordLength = 'Пароль должен содержать более 7 символов.'
            this.register.phone.length === 9 || this.register.phone.length === 0 ? this.vaidateMessage.vaidatePhone = '' : this.vaidateMessage.vaidatePhone = 'Номер телфона должен содержать 9 символов.'
        },
        resentCode() {
            if (this.step === 2) {
                axios.post(route('auth.confirm.password', 'resent'), this.register)
                    .then(response => {
                        this.confirmTimer(5)
                    })
                    .catch(error => {
                        this.$swal({
                            position: 'top-end',
                            icon: 'error',
                            title: error.response.data.message,
                            showConfirmButton: true,
                            timer: 2000
                        })
                    })
            }
        },
        confirmCode() {
            if (this.step === 2 && this.register.confirmCode.length === 4) {
                axios.post(route('auth.confirm.password', 'confirm'), this.register)
                    .then(response => {
                        this.$swal({
                            position: 'top-end',
                            icon: 'success',
                            title: response.data.message,
                            showConfirmButton: true,
                            timer: 2000
                        }).then(response => {
                            window.location.href = route('auth.login')
                        })
                    })
                    .catch(error => {
                        this.$swal({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Введите корректные данные ddddd',
                            showConfirmButton: true,
                            timer: 2000
                        })
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
    },
}
</script>

<template>
    <div class="main-content">
        <div class="header">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-end">
                        <div class="col">
                            <h6 class="header-pretitle">
                                Сервис коротких ссылок
                            </h6>
                            <h1 class="header-title">
                                Ссылки
                            </h1></div>
                        <div class="col-auto"><a
                            :href="route('logout')"
                            class="btn btn-danger lift">
                            Выход
                        </a></div>
                    </div>
                </div>
            </div>
        </div>

        <!--            {{ urls }}-->

        <div class="container-fluid">

            <form class="mb-4">
                <div class="row">
                    <div class="col-12 col-lg-12">
                        <div class="form-group">
                            <label>Название <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="url.long">
                        </div>
                    </div>
                    <button type="button" class="btn btn-block btn-primary"
                            @click="create_record()">
                        Сгенерировать короткую ссылку
                    </button>
                </div>

            </form>


            <div class="row">
                <div class="col-12 col-lg-6 col-xl">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col"><h4 class="card-header-title">
                                            Ссылки
                                        </h4></div>
                                    </div>
                                </div>


                                <div
                                    data-list="{&quot;valueNames&quot;: [&quot;goal-project&quot;, &quot;goal-status&quot;, &quot;goal-progress&quot;, &quot;goal-date&quot;]}"
                                    class="table-responsive mb-0">
                                    <div v-if="$userPermissions.includes('urls-read')==true">
                                        <vue-good-table
                                            :columns="columns"
                                            :rows="rows"
                                            :fixed-header="false"
                                            :line-numbers="true"
                                            :search-options="{
                                                    enabled: true,
                                                    skipDiacritics: true,
                                                    placeholder: 'Поиск',
                                                }"
                                            :pagination-options="{
                                                    enabled: true,
                                                    mode: 'pages',
                                                    perPage: 10,
                                                    perPageDropdown: [10, 20, 30, 50],
                                                    position: 'bottom',
                                                    dropdownAllowAll: true,
                                                    setCurrentPage: 1,
                                                    nextLabel: 'след',
                                                    prevLabel: 'пред',
                                                    rowsPerPageLabel: 'Строк на странице',
                                                    ofLabel: 'из',
                                                    pageLabel: 'страница', // for 'pages' mode
                                                    allLabel: 'Все',
                                                  }"
                                        >
                                            <div slot="emptystate">
                                                Нет записей
                                            </div>
                                            <template slot="table-row" slot-scope="props">
                                                    <span v-if="props.column.field == 'actions'">

                                                     <a :href="'/'+props.row.short"
                                                        title="Открыть ссылку"
                                                        class="badge badge-info">
                                                         <i class="fe fe-eye"></i>
                                                     </a>

                                                     <button
                                                         v-if="$userPermissions.includes('urls-delete')==true"
                                                         title="Удалить" class="btn badge badge-danger"
                                                         @click="delete_record(props.row.id)">
                                                            <i class="fe fe-trash"></i>
                                                    </button>
                                                    </span>
                                                <span v-else-if="props.column.field == 'is_active'">
                                                         <span class="badge" v-if="props.row.is_active==0">не активен</span>
                                                         <span class="badge"
                                                               v-else-if="props.row.is_active==1">активен</span>
                                                    </span>
                                            </template>

                                        </vue-good-table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import axios from "axios";

export default {
    components: {},

    props: {
        urls: Array,
        columns: Array,
    },

    name: 'my-component',

    data() {
        return {
            rows: this.urls,
            url: {}
        };
    },
    methods: {
        delete_record: function (id) {
            this.$swal({
                title: 'Вы уверены?',
                text: "Ссылка будет удалена!",
                type: 'warning',
                buttons: {
                    confirm: {
                        text: "Да",
                        value: true,
                        visible: true,
                        className: "isConfirm",
                        closeModal: true
                    },
                    cancel: {
                        text: "Нет",
                        value: false,
                        visible: true,
                        className: "",
                        closeModal: true,
                    }
                }
            }).then((isConfirm) => {
                if (isConfirm === true) {
                    axios.delete(route("urls.destroy", id))
                        .then(
                            (response) => {
                                const index = this.urls.map(url => url.id).indexOf(id);
                                this.urls.splice(index, 1);
                                toast.fire({title: response.data.message})
                            },
                            (error) => {
                                console.log(error)
                            }
                        )
                }
            });
        },
        create_record: async function () {
            axios.post(route("urls.store"), this.url)
                .then(
                    (response) => {
                        toast.fire({title: response.data.message})
                        this.urls.push(response.data.url)
                        this.url = {}
                        // this.$inertia.visit(route('applications.index'));
                    },
                    (error) => {
                        this.$swal('Ошибка', 'Введите корректные данные', 'error')
                    }
                )
        }
    }

};


</script>

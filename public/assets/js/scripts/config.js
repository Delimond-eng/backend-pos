import {get, postJson } from "../main/http.js";

new Vue({
    el: "#AppConfig",
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            isDataLoading: false,
            search: "",
            load_id: "",
            items: [],
            form: {
                item_libelle: "",
                natures: [{
                    item_nature_libelle: "",
                    item_nature_prix: "",
                    item_nature_prix_devise: "CDF",
                }, ],
            },
        };
    },

    methods: {
        loadItems() {
            this.isDataLoading = true;
            get("/configs.all")
                .then((res) => {
                    this.isDataLoading = false;
                    this.items = res.data.items;
                })
                .catch((err) => { console.log("error", err);
                    this.isDataLoading = false; });
        },
        deleteNatureField(index) {
            if (this.form.natures.length === 1) {
                return;
            }
            this.form.natures.splice(index, 1);
        },

        addNatureField() {
            this.form.natures.push({
                item_nature_libelle: "",
                item_nature_prix: "",
                item_nature_prix_devise: "CDF",
            });
        },

        cleanAllField() {
            this.form.item_libelle = "";
            this.form.item_prix = "";
            this.form.item_prix_devise = "CDF";
            this.form.item_id = "";
            this.form.natures = [{
                item_nature_libelle: "",
                item_nature_prix: "",
                item_nature_prix_devise: "CDF",
            }, ];
        },
        submitForm(event) {
            this.isLoading = true;
            postJson("/item.create", this.form)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors;
                    }
                    if (data.result !== undefined) {
                        this.error = null;
                        this.cleanAllField();
                        $("#config-modal").modal("hide");
                        new Swal({
                            icon: "success",
                            title: "Opération effectuée !",
                            text: "La création de l'item produit effectué avec succès!",
                            timer: 2000,
                            showConfirmButton: false,
                        });
                        this.loadItems();
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                });
        },

        showEditItem(item) {
            this.form = JSON.parse(JSON.stringify(item));
            this.form.item_id = item.id;
            setTimeout(() => {
                $("#config-modal").modal("show");
            }, 100);
        },
        deleteItem(item) {
            let self = this;
            new Swal({
                title: "Etes-vous sûr ?",
                text: "Voulez-vous vraiment supprimer ce item ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirmer",
                cancelButtonText: "Annuler",
            }).then((result) => {
                if (result.isConfirmed) {
                    self.load_id = item.id;
                    postJson("/data.delete", {
                            table: "items",
                            id: item.id,
                            id_field: "id",
                            state: "item_state",
                        })
                        .then((res) => {
                            self.load_id = "";
                            self.loadItems();
                        })
                        .catch((err) => {
                            self.load_id = "";
                        });
                }
            });
        },
    },
    mounted() {
        this.$nextTick(() => {
            this.loadItems();
        });
        let myModal = document.getElementById("config-modal");
        let self = this;
        myModal.addEventListener("hidden.bs.modal", function(event) {
            self.cleanAllField();
        });
    },

    computed: {
        allItems() {
            if (this.search && this.search.trim()) {
                return this.items.filter((el) =>
                    el.item_libelle
                    .toLowerCase()
                    .includes(this.search.toLowerCase())
                );
            } else {
                return this.items;
            }
        },
    },
});

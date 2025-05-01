import {get, post } from "../main/http.js";
new Vue({
    el: "#AppAccount",
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            isDataLoading: false,
            accounts: [],
            load_id: "",
            form: {
                libelle: "",
                devise: "",
            },
        };
    },

    mounted() {

        this.$nextTick(() => {
            this.loadAccountsDatas();
        });
        let myModal = document.getElementById("account-modal");
        let self = this;
        // Add an event listener for the 'hidden.bs.modal' event
        myModal.addEventListener("hidden.bs.modal", function(event) {
            self.loadAccountsDatas();
        });
    },

    methods: {
        loadAccountsDatas() {
            this.isDataLoading = true;
            get("/configs.all")
                .then((res) => {
                    this.isDataLoading = false;
                    this.accounts = res.data.all_comptes;
                })
                .catch((err) => {
                    console.log("error", err);
                    this.isDataLoading = false;
                });
        },

        submitForm(event) {
            const formData = new FormData();
            formData.append("compte_libelle", this.form.libelle);
            formData.append("compte_devise", this.form.devise);
            this.isLoading = true;
            post("/compte.create", formData)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors;
                    }
                    if (data.compte !== undefined) {
                        this.error = null;
                        this.result = data.result;
                        this.form.libelle = "";
                        this.form.devise = "";
                        $("#account-modal").modal("hide");
                        new Swal({
                            icon: "success",
                            title: "Opération effectuée !",
                            text: "Le compte créé avec succès.",
                        });
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                });
        },

        deleteAccount(id) {
            let self = this;
            new Swal({
                title: "Etes-vous sûr ?",
                text: "Voulez-vous vraiment supprimer ce compte ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirmer",
                cancelButtonText: "Annuler",
            }).then((result) => {
                if (result.isConfirmed) {
                    self.load_id = id;
                    const formData = new FormData();
                    formData.append("table", "comptes");
                    formData.append("id", id);
                    formData.append("id_field", "id");
                    formData.append("state", "compte_state");
                    post("/data.delete", formData)
                        .then((res) => {
                            self.load_id = "";
                            self.loadAccountsDatas();
                        })
                        .catch((err) => {
                            self.load_id = "";
                        });
                }
            });
        },
    },

    computed: {},
});

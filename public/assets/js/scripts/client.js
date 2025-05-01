import { get, post } from "../main/http.js";
new Vue({
    el: "#AppClient",
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            isDataLoading: false,
            clients: [],
            load_id: "",
            search: "",
            form: {
                name: "",
                phone: "",
                address: "",
            },
        };
    },

    mounted() {
        let self = this;
        let myModal = document.getElementById("client-modal");
        // Add an event listener for the 'hidden.bs.modal' event
        myModal.addEventListener("hidden.bs.modal", function (event) {
            self.loadClientsDatas();
        });
        this.$nextTick(() => {
            this.loadClientsDatas();
        });
    },

    methods: {
        loadClientsDatas() {
            this.isDataLoading = true;
            get("/clients.all")
                .then((res) => {
                    this.isDataLoading = false;
                    this.clients = res.data.clients;
                })
                .catch((err) => {
                    console.log("error", err);
                    this.isDataLoading = false;
                });
        },

        submitForm(event) {
            const formData = new FormData();
            formData.append("client_nom", this.form.name);
            formData.append("client_tel", this.form.phone);
            formData.append("client_adresse", this.form.address);
            this.isLoading = true;
            post("/client.create", formData)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors;
                    }
                    if (data.result !== undefined) {
                        this.error = null;
                        this.result = data.result;
                        this.form.name = "";
                        this.form.phone = "";
                        this.form.adresse = "";
                        $("#client-modal").modal("hide");
                        new Swal({
                            icon: "success",
                            title: "Opération effectuée !",
                            text: "La création du nouveau client est effectuée avec succès!",
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                });
        },

        deleteClient(id) {
            const formData = new FormData();
            formData.append("table", "clients");
            formData.append("id", id);
            formData.append("id_field", "id");
            formData.append("state", "client_state");
            new Swal({
                title: "Delete",
                text: "lorem",
                timer: 2000,
            });
            this.load_id = id;
            let self = this;
            Swal.fire({
                title: "Êtes-vous sûr ?",
                text: "Cette action est irréversible !",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Oui, supprimer",
                cancelButtonText: "Annuler",
            }).then((result) => {
                if (result.isConfirmed) {
                    post("/data.delete", formData)
                        .then((res) => {
                            self.load_id = "";
                            self.loadClientsDatas();
                        })
                        .catch((err) => {
                            self.load_id = "";
                        });
                } else {
                    self.load_id = "";
                }
            });
        },
    },

    computed: {
        clientsData() {
            if (this.search && this.search.trim()) {
                return this.clients.filter((el) =>
                    el.client_nom
                        .toLowerCase()
                        .includes(this.search.toLowerCase())
                );
            } else {
                return this.clients;
            }
        },
    },
});

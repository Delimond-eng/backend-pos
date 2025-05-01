import {get, postJson } from "../main/http.js";
new Vue({
    el: "#AppPayment",
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            isDataLoading: false,
            search: '',
            load_id: '',
            load_id_detail: "",
            delete_id: '',
            paiements: [],
            details: []
        };
    },

    mounted() {
        this.$nextTick(() => {
            this.loadPaymentDatas("all");
        });
    },

    methods: {
        loadPaymentDatas(key) {
            this.isDataLoading = true;
            get("payments/" + key)
                .then((res) => {
                    this.isDataLoading = false;
                    this.paiements = res.data.results;
                })
                .catch((err) => {
                    console.log("error", err);
                    this.isDataLoading = false;
                });
        },
        loadDetail(id) {
            this.load_id_detail = id;
            get("payment.details/" + id)
                .then((res) => {
                    this.load_id_detail = "";
                    this.details = res.data.details;
                    $('#detail-modal').modal("show");
                })
                .catch((err) => {
                    console.log("error", err);
                    this.load_id_detail = "";
                });
        },

        deleteAllPayment(id) {
            let self = this;
            new Swal({
                title: "Attention! Action irréversible.",
                text: "Voulez-vous vraiment supprimer ce paiement ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirmer",
                cancelButtonText: "Annuler",
            }).then((result) => {
                if (result.isConfirmed) {
                    self.load_id = id;
                    postJson("/data.delete", {
                            table: "operations",
                            id: id,
                            id_field: "facture_id",
                            state: "operation_state",
                        })
                        .then((res) => {
                            self.load_id = "";
                            new Swal({
                                title: "Opération effectuée avec succès .",
                                icon: "success",
                                toast: true,
                                showConfirmButton: false,
                                timer: 2000
                            });
                            self.loadPaymentDatas("all");
                        })
                        .catch((err) => {
                            self.load_id = "";
                        });
                }
            });
        },

        deleteOne(id, index) {
            let self = this;
            new Swal({
                title: "Attention! Action irréversible.",
                text: "Voulez-vous vraiment supprimer ce paiement ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#029318",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirmer",
                cancelButtonText: "Annuler",
            }).then((result) => {
                if (result.isConfirmed) {
                    self.delete_id = id;
                    postJson("/data.delete", {
                            table: "operations",
                            id: id,
                            id_field: "id",
                            state: "operation_state",
                        })
                        .then((res) => {
                            self.delete_id = "";
                            new Swal({
                                title: "Opération effectuée avec succès .",
                                icon: "success",
                                toast: true,
                                showConfirmButton: false,
                                timer: 2000
                            });
                            self.details.splice(index, 1);
                            self.loadPaymentDatas("all");
                        })
                        .catch((err) => {
                            self.delete_id = "";
                        });
                }
            });
        },


    },

    computed: {
        allDetails() {
            return this.details;
        },
        allPayments() {
            if (this.search && this.search.trim()) {
                return this.paiements.filter((el) =>
                    el.client_nom
                    .toLowerCase()
                    .includes(this.search.toLowerCase())
                );
            } else {
                return this.paiements;
            }
        }
    },
});

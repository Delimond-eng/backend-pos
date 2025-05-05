import { get, postJson, post } from "../main/http.js";
import Select2 from "../components/select2Component.js";

new Vue({
    el: "#AppSales",
    components: {
        Select2,
    },
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            isDataLoading: false,
            selectedSaleItem: null,
            search: "",
            load_id: "",
            reports: [],
            returns: [],
            form: {
                quantity: "",
            },
        };
    },

    methods: {
        getSales() {
            this.isDataLoading = true;
            get("/reports.sales")
                .then((res) => {
                    this.isDataLoading = false;
                    this.reports = res.data.sales_report;
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error", err);
                });
        },

        getSalesReturns() {
            this.isDataLoading = true;
            get("/sales.returns")
                .then((res) => {
                    this.isDataLoading = false;
                    this.returns = res.data.returns;
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error", err);
                });
        },

        validReturn(e) {
            const self = this;
            new Swal({
                title: "Confirmez le retour du produit ",
                text: "Vous êtes sur le point de faire un retour du produit déjà vendu ?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirmer",
                cancelButtonText: "Annuler",
            }).then((result) => {
                if (result.isConfirmed) {
                    self.returnProcess();
                }
            });
        },

        returnProcess() {
            const formData = {
                product_id: this.selectedSaleItem.product_id,
                sale_id: this.selectedSaleItem.sale_id,
                quantity: this.form.quantity,
            };
            this.isLoading = true;
            postJson("/sale.return", formData)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors;
                    }
                    if (data.result !== undefined) {
                        $("#modal-returns").modal("hide");
                        new Swal({
                            title: data.result,
                            icon: "success",
                            showConfirmButton: !1,
                            timer: 3000,
                        });
                        this.form.quantity = "";
                        this.getSales();
                        this.getSalesReturns();
                    }
                })
                .catch((err) => {
                    console.log(err);
                    this.isLoading = false;
                    this.error = err;
                });
        },
        deleteSaleProcess(id) {
            const self = this;
            new Swal({
                title: "Confirmez la suppression de la vente ",
                text: "Vous êtes sur le point de supprimer une vente ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirmer",
                cancelButtonText: "Annuler",
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = {
                        sale_id: id,
                    };
                    self.load_id = id;
                    postJson("/sale.delete", formData)
                        .then(({ data, status }) => {
                            self.isLoading = false;
                            if (data.errors !== undefined) {
                                self.error = data.errors;
                            }
                            if (data.result !== undefined) {
                                new Swal({
                                    title: data.result,
                                    icon: "success",
                                    showConfirmButton: !1,
                                    timer: 3000,
                                });
                                self.getSales();
                                self.getSalesReturns();
                            }
                        })
                        .catch((err) => {
                            console.log(err);
                            self.load_id = "";
                            self.error = err;
                        });
                }
            });
        },
    },
    mounted() {
        this.$nextTick(() => {
            this.getSales();
            this.getSalesReturns();
        });
    },

    computed: {
        filteredSales() {
            return this.reports;
        },

        filteredReturns() {
            return this.returns;
        },
    },
});

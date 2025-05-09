import { get, postJson, post } from "../main/http.js";
import Select2 from "../components/select2Component.js";

new Vue({
    el: "#AppProduct",
    components: {
        Select2,
    },
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            isDataLoading: false,
            products: [],
            categories: [],
            selectedProduct: null,
            selectedAppro: null,
            approvisionnements: [],
            appro: {
                purchase_id: "",
                pa: "",
                qty: "",
                date: "",
                supplier: "",
                pu: "",
            },
            form: {
                product_id: "",
                name: "",
                category_id: "",
                unit_price: "",
                purchase: {
                    quantity: "",
                    unit_price: "",
                    supplier_name: "",
                    date: "",
                },
            },
            search: "",
            byDate: "",
            load_id: "",
        };
    },
    mounted() {
        const self = this;
        if ($("#product-modal").length) {
            let myModal = document.getElementById("product-modal");
            // Add an event listener for the 'hidden.bs.modal' event
            myModal.addEventListener("hidden.bs.modal", function (event) {
                self.clearFields();
            });
        }

        if ($("#stock-modal").length) {
            let myModal = document.getElementById("stock-modal");
            // Add an event listener for the 'hidden.bs.modal' event
            myModal.addEventListener("hidden.bs.modal", function (event) {
                self.appro = {
                    purchase_id: "",
                    pa: "",
                    qty: "",
                    date: "",
                    supplier: "",
                    pu: "",
                };
            });
        }

        this.$nextTick(() => {
            this.viewAllProducts();
            this.allCategories();
            this.getAllApprovisionnements();
        });
    },
    methods: {
        getAllApprovisionnements() {
            this.isDataLoading = true;
            get(`/appro.all?date=${this.byDate}`)
                .then((res) => {
                    this.isDataLoading = false;
                    this.approvisionnements = res.data.purchases;
                })
                .catch((err) => {
                    console.log("error", err);
                    this.isDataLoading = false;
                });
        },
        viewAllProducts() {
            this.isDataLoading = true;
            get("/products")
                .then((res) => {
                    this.isDataLoading = false;
                    this.products = res.data.products;
                })
                .catch((err) => {
                    console.log("error", err);
                    this.isDataLoading = false;
                });
        },
        allCategories() {
            get("/categories")
                .then((res) => {
                    this.categories = res.data.categories;
                })
                .catch((err) => {
                    console.log("error", err);
                });
        },
        submitForm(event) {
            const formData = new FormData();
            formData.append("product_id", this.form.product_id);
            formData.append("name", this.form.name);
            formData.append("category_id", this.form.category_id);
            formData.append("unit_price", this.form.unit_price);
            formData.append(
                "stock_supplier_name",
                this.form.purchase.supplier_name
            );
            formData.append("stock_quantity", this.form.purchase.quantity);
            formData.append("stock_unit_price", this.form.purchase.unit_price);
            formData.append("stock_date", this.form.purchase.date);
            this.isLoading = true;

            post("/product.create", formData)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors;
                    }
                    if (data.result !== undefined) {
                        this.error = null;
                        this.viewAllProducts();
                        this.clearFields();

                        new Swal({
                            title: data.result,
                            icon: "success",
                            showConfirmButton: !1,
                            timer: 3000,
                        });
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                    console.error(err);
                });
        },

        triggerEdit(data) {
            this.form.product_id = data.id;
            this.form.name = data.name;
            this.form.unit_price = data.unit_price;
            this.form.category_id = data.category_id;
        },

        editAppro(data) {
            this.selectedAppro = data;
            this.appro.purchase_id = data.id;
            this.appro.pa = data.unit_price;
            this.appro.qty = data.quantity;
            this.appro.supplier = data.supplier_name;
        },

        clearFields() {
            this.form = {
                product_id: "",
                name: "",
                category_id: "",
                unit_price: "",
                purchase: {
                    quantity: "",
                    unit_price: "",
                    supplier_name: "",
                    date: "",
                },
            };
        },

        addStock(event) {
            const formData = new FormData();
            formData.append("purchase_id", this.appro.purchase_id);
            formData.append("supplier_name", this.appro.supplier);
            formData.append("date", this.appro.date);
            formData.append("quantity", this.appro.qty);
            formData.append("unit_price", this.appro.pa);
            formData.append("product_id", this.selectedProduct.id);
            formData.append("pu", this.appro.pu);
            this.isLoading = true;
            post("/purchases", formData)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors;
                    }
                    if (data.result !== undefined) {
                        this.error = null;
                        this.result = data.result;
                        this.viewAllProducts();
                        new Swal({
                            title: "Produit approvisionné avec succès",
                            icon: "success",
                            showConfirmButton: !1,
                            timer: 2000,
                        });
                        setTimeout(() => {
                            $("#stock-modal").modal("hide");
                        });
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                });
        },
        updateStock(event) {
            const formData = new FormData();
            formData.append("purchase_id", this.appro.purchase_id);
            formData.append("supplier_name", this.appro.supplier);
            formData.append("date", this.appro.date);
            formData.append("quantity", this.appro.qty);
            formData.append("unit_price", this.appro.pa);
            formData.append("product_id", this.selectedAppro.product.id);
            formData.append("pu", this.selectedAppro.product.unit_price);

            this.isLoading = true;
            post("/purchases", formData)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors;
                    }
                    if (data.result !== undefined) {
                        this.error = null;
                        this.result = data.result;
                        this.getAllApprovisionnements();
                        new Swal({
                            title: "Modification enregistrée avec succès",
                            icon: "success",
                            showConfirmButton: !1,
                            timer: 2000,
                        });
                        setTimeout(() => {
                            $("#stock-modal").modal("hide");
                        }, 1000);
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                });
        },

        deleteProduct(id) {
            let self = this;
            new Swal({
                title: "Attention! Action irréversible.",
                text: "La suppression du produit entraine la suppression de tous les mouvements liés à ce produit ?",
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
                        table: "products",
                        id: id,
                        id_field: "id",
                    })
                        .then((res) => {
                            self.load_id = "";
                            self.viewAllProducts();
                        })
                        .catch((err) => {
                            self.load_id = "";
                        });
                }
            });
        },

        deleteApprov(id, qte, itemId) {
            let self = this;
            new Swal({
                title: "Attention! Action irréversible.",
                text: "Voulez-vous vraiment supprimer cette approvisionnement ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirmer",
                cancelButtonText: "Annuler",
            }).then((result) => {
                if (result.isConfirmed) {
                    self.load_id = id;
                    postJson("/approv.delete", {
                        id: id,
                        item_id: itemId,
                        qte: qte,
                    })
                        .then((res) => {
                            self.load_id = "";
                            location.reload();
                        })
                        .catch((err) => {
                            self.load_id = "";
                        });
                }
            });
        },

        downloadExportPdf() {
            location.href = `/purchases.reports.export?date=${this.byDate}`;
        },
    },

    computed: {
        filteredProducts() {
            if (this.search && this.search.trim()) {
                return this.products.filter((el) =>
                    el.name.toLowerCase().includes(this.search.toLowerCase())
                );
            } else {
                return this.products;
            }
        },
        filteredPurchases() {
            if (this.search && this.search.trim()) {
                return this.approvisionnements.filter((el) =>
                    el.product.name
                        .toLowerCase()
                        .includes(this.search.toLowerCase())
                );
            } else {
                return this.approvisionnements;
            }
        },
    },
});

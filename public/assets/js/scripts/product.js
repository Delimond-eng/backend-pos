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
            appro: {
                pa: "",
                qty: "",
                date: "",
                supplier: "",
                pu: "",
            },
            search: "",
            load_id: "",
        };
    },

    methods: {
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
            formData.append("name", event.target.name.value);
            formData.append("category_id", event.target.category_id.value);
            formData.append("unit_price", event.target.unit_price.value);
            formData.append(
                "stock_supplier_name",
                event.target.supplier_name.value
            );
            formData.append("stock_quantity", event.target.quantity.value);
            formData.append("stock_unit_price", event.target.unit_price2.value);
            formData.append("stock_date", event.target.date.value);
            this.isLoading = true;

            post("/product.create", formData)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors;
                    }
                    if (data.result !== undefined) {
                        this.error = null;
                        this.result = data.result;
                        this.viewAllProducts();
                        console.log(JSON.stringify(data.result));
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                    console.error(err);
                });
        },
        addStock(event) {
            const formData = new FormData();
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
        deleteProduct(id) {
            let self = this;
            new Swal({
                title: "Attention! Action irréversible.",
                text: "La suppression entraine tous les mouvements liés à ce produit ?",
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
    },
    mounted() {
        this.$nextTick(() => {
            this.viewAllProducts();
            this.allCategories();
        });
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
    },
});

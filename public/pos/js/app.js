import { get, post, postJson } from "./main/http.js";

new Vue({
    el: "#app",
    data: {
        dropdownOpen: false,
        searchTerm: "",
        currentCategory: "",
        showNumpad: false,
        daySale: 0.0,
        currentProduct: null,
        isDataLoading: false,
        isLoading: false,
        numpadQty: "",
        cart: [],
        categories: [],
        products: [],
    },
    mounted() {
        this.$nextTick(() => {
            this.loadCatDatas();
            this.loadProducts();
            this.loadDaySale();
        });
    },
    computed: {
        filteredProducts() {
            return this.products.filter(
                (p) =>
                    (!this.currentCategory ||
                        p.category.id === this.currentCategory) &&
                    (!this.searchTerm ||
                        p.name
                            .toLowerCase()
                            .includes(this.searchTerm.toLowerCase()))
            );
        },
        total() {
            return this.cart
                .reduce((sum, item) => sum + item.unit_price * item.quantity, 0)
                .toFixed(2);
        },

        filterNumericInput() {
            this.numpadQty = this.numpadQty.replace(/[^0-9]/g, "");
        },
    },
    methods: {
        loadCatDatas() {
            get("/categories")
                .then((res) => {
                    this.isDataLoading = false;
                    this.categories = res.data.categories;
                })
                .catch((err) => {
                    console.log("error", err);
                    this.isDataLoading = false;
                });
        },

        loadProducts() {
            this.isDataLoading = true;
            get("/products")
                .then((res) => {
                    this.isDataLoading = false;
                    this.products = res.data.products;
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error", err);
                });
        },
        loadDaySale() {
            get("/day.sale.count")
                .then((res) => {
                    this.daySale = res.data.day_sum;
                })
                .catch((err) => {
                    console.log("error", err);
                });
        },

        filterCategory(cat) {
            this.currentCategory = cat === this.currentCategory ? "" : cat;
        },
        addToCart(product) {
            const found = this.cart.find((p) => p.id === product.id);
            if (found) {
                if (found.quantity > product.stock) {
                    new Swal({
                        title: "Stock insuffisant !",
                        text:
                            "Impossible d'ajouter un produit au panier, stock insuffisant ! Stock actuel : " +
                            product.stock,
                        icon: "warning",
                        timer: 3000,
                        showConfirmButton: !1,
                    });
                    if (product.stock == 0) {
                        this.cart = this.cart.filter(
                            (p) => p.id !== product.id
                        );
                    } else {
                        found.quantity = product.stock;
                    }
                    return;
                }
                found.quantity += 1;
            } else {
                if (product.stock == 0) {
                    new Swal({
                        title: "Stock insuffisant !",
                        text:
                            "Impossible d'ajouter un produit au panier, stock insuffisant. stock actuel : " +
                            product.stock,
                        icon: "warning",
                        timer: 3000,
                        showConfirmButton: !1,
                    });
                    return;
                }
                this.cart.push({ ...product, quantity: 1 });
            }
        },
        removeFromCart(product) {
            this.cart = this.cart.filter((p) => p.id !== product.id);
        },
        editQuantity(product) {
            this.currentProduct = product;
            this.numpadQty = product.quantity.toString();
            this.showNumpad = true;
        },
        confirmQty() {
            if (this.currentProduct && this.numpadQty) {
                const quantity = parseInt(this.numpadQty);
                if (quantity > this.currentProduct.stock) {
                    new Swal({
                        title: "Stock insuffisant !",
                        text:
                            "Impossible d'ajouter un produit au panier, stock insuffisant ! Stock actuel : " +
                            this.currentProduct.stock,
                        icon: "warning",
                        timer: 3000,
                        showConfirmButton: !1,
                    });
                    if (this.currentProduct.stock == 0) {
                        this.cart = this.cart.filter(
                            (p) => p.id !== product.id
                        );
                    }
                    return;
                }
                if (quantity > 0) {
                    const item = this.cart.find(
                        (p) => p.id === this.currentProduct.id
                    );
                    if (item) {
                        item.quantity = quantity;
                    }
                } else {
                    this.removeFromCart(this.currentProduct);
                }
                this.showNumpad = false;
                this.currentProduct = null;
                this.numpadQty = "";
            }
        },

        handleEnter() {
            if (this.numpadQty && parseInt(this.numpadQty) > 0) {
                this.confirmQty();
            }
        },
        pay() {
            const self = this;
            new Swal({
                title: "Confirmez la vente ",
                text: "Veuillez confirmer la vente en cours !",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirmer",
                cancelButtonText: "Annuler",
            }).then((result) => {
                if (result.isConfirmed) {
                    self.payProcessing();
                }
            });
        },

        payProcessing() {
            if (this.cart.length > 0) {
                let items = [];
                this.cart.forEach((el) => {
                    items.push({
                        product_id: el.id,
                        quantity: el.quantity,
                        unit_price: el.unit_price,
                    });
                });
                const formData = {
                    items: items,
                };
                this.isLoading = true;
                postJson("/sales.store", formData)
                    .then(({ data, status }) => {
                        this.isLoading = false;
                        if (data.errors !== undefined) {
                            new Swal({
                                text: data.errors.toString(),
                                icon: "error",
                                toast: true,
                            });
                        }
                        if (data.result !== undefined) {
                            console.log(JSON.stringify(data.ticket));

                            new Swal({
                                title: data.result,
                                icon: "success",
                                showConfirmButton: !1,
                                timer: 3000,
                            });

                            this.loadProducts();
                            this.loadDaySale();
                            this.cart = [];
                            localStorage.setItem(
                                "ticket",
                                JSON.stringify(data.ticket)
                            );
                            const printWindow = window.open(
                                "printing.html",
                                "_blank",
                                "width=800,height=600"
                            );
                        }
                    })
                    .catch((err) => {
                        console.log(err);
                        this.isLoading = false;
                        new Swal({
                            text: err,
                            icon: "error",
                            toast: true,
                        });
                    });
            }
        },
    },
});

import { get, postJson, post } from "../main/http.js";
import Select2 from "../components/select2Component.js";
new Vue({
    el: "#AppInventory",
    components: {
        Select2,
    },
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            isDataLoading: false,
            search: "",
            load_id: "",
            currentInventory: null,
            inventories: [],
            products: [],
            selectedProductIds: [],
            inventoryLines: [],
        };
    },

    methods: {
        // Chargement de l'inventaire en cours depuis l'API
        loadCurrentInventory() {
            this.isDataLoading = true;
            get("/inventories.current")
                .then((res) => {
                    this.isDataLoading = false;
                    if (res.data.status === "success" && res.data.inventory) {
                        this.currentInventory = res.data.inventory;
                        this.loadInventoryFromCache(); // Restaurer depuis le cache
                    }
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.error(
                        "Erreur lors du chargement de l'inventaire en cours :",
                        err
                    );
                });
        },

        // Charger tous les inventaires (passés)
        getInventories() {
            this.isDataLoading = true;
            get("/inventories.all")
                .then((res) => {
                    this.isDataLoading = false;
                    this.inventories = res.data.inventories;
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error", err);
                });
        },

        // Voir tous les produits
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

        // Ajouter ou retirer un produit de l'inventaire
        toggleProductSelection(product) {
            const exists = this.selectedProductIds.includes(product.id);
            if (exists) {
                this.selectedProductIds = this.selectedProductIds.filter(
                    (id) => id !== product.id
                );
                this.inventoryLines = this.inventoryLines.filter(
                    (p) => p.id !== product.id
                );
            } else {
                this.selectedProductIds.push(product.id);
                this.inventoryLines.push({
                    ...product,
                    real_quantity: null,
                });
            }

            this.saveInventoryToCache(); // Sauvegarder après chaque changement
        },

        getInventoryGap(line) {
            if (line.real_quantity === null || line.real_quantity === "")
                return "";
            const theoretical = line.stock_global || 0;
            return line.real_quantity - theoretical;
        },

        getInventoryValue(line) {
            const gap = this.getInventoryGap(line);
            if (gap === "") return "";
            const price = line.unit_price || 0;
            return gap * price;
        },

        getTotalGap() {
            return this.inventoryLines.reduce((sum, line) => {
                const gap = this.getInventoryGap(line);
                return sum + (gap !== "" ? gap : 0);
            }, 0);
        },

        getTotalValue() {
            return this.inventoryLines.reduce((sum, line) => {
                const val = this.getInventoryValue(line);
                return sum + (val !== "" ? val : 0);
            }, 0);
        },

        // Démarrer un nouvel inventaire
        startInventory() {
            this.isLoading = true;
            postJson("/inventories.start", {})
                .then(({ data }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors;
                    }
                    if (data.result !== undefined) {
                        this.loadCurrentInventory(); // Charge et restaure depuis le cache
                    }
                })
                .catch((err) => {
                    console.log(err);
                    this.isLoading = false;
                    this.error = err;
                });
        },

        //valider un inventaire en cours
        validateInventory() {
            let items = [];
            this.inventoryLines.forEach((el) => {
                items.push({
                    theoretical_quantity: parseInt(el.stock_global),
                    real_quantity: el.real_quantity,
                    product_id: el.id,
                });
            });
            let formData = {
                inventory_id: this.currentInventory.id,
                items: items,
            };
            this.isLoading = true;
            postJson("/inventories.validate", formData)
                .then(({ data }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors;
                    }
                    if (data.result !== undefined) {
                        this.loadCurrentInventory();
                        this.clearAll();
                        new Swal({
                            title: data.result,
                            icon: "success",
                            timer: 3000,
                            showConfirmButton: !1,
                        });
                    }
                })
                .catch((err) => {
                    console.log(err);
                    this.isLoading = false;
                    this.error = err;
                });
        },

        // Sauvegarder l’état actuel dans le cache local
        saveInventoryToCache() {
            if (this.currentInventory) {
                const cache = {
                    inventory_id: this.currentInventory.id,
                    selectedProductIds: this.selectedProductIds,
                    inventoryLines: this.inventoryLines,
                };
                localStorage.setItem("inventory_cache", JSON.stringify(cache));
            }
        },

        // Restaurer les données depuis le cache si l'ID correspond
        loadInventoryFromCache() {
            const cached = localStorage.getItem("inventory_cache");
            if (cached) {
                try {
                    const data = JSON.parse(cached);
                    if (
                        this.currentInventory &&
                        data.inventory_id === this.currentInventory.id
                    ) {
                        this.selectedProductIds = data.selectedProductIds || [];
                        this.inventoryLines = data.inventoryLines || [];
                    }
                } catch (e) {
                    console.error("Erreur de lecture du cache inventaire :", e);
                }
            }
        },

        // Nettoyer le cache
        clearInventoryCache() {
            localStorage.removeItem("inventory_cache");
        },

        clearAll() {
            this.currentInventory = null;
            this.selectedProductIds = [];
            this.inventoryLines = [];
        },
    },

    mounted() {
        this.$nextTick(() => {
            this.getInventories();
            this.viewAllProducts();
            this.loadCurrentInventory();
        });
    },

    watch: {
        // Met à jour le cache dès qu’une quantité est modifiée
        inventoryLines: {
            handler() {
                this.saveInventoryToCache();
            },
            deep: true,
        },
    },

    computed: {
        filteredInventories() {
            return this.inventories;
        },
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

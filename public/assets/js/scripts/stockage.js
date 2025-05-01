import {get, postJson } from "../main/http.js";
import Select2 from "../components/select2Component.js";

new Vue({
    el: "#AppStockage",
    components: {
        Select2
    },
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            isDataLoading: false,
            search: "",
            load_id: "",
            stocks: [],
            produits: [],
            formProduit: {
                produit_libelle: "",
                entree: {
                    entree_qte: "",
                    entree_prix_achat: "",
                    entree_prix_devise: "CDF",
                },
            },
            formAppend: {
                entree_qte: "",
                entree_prix_achat: "",
                entree_prix_devise: "CDF",
                produit_id: null
            },
            formReduce: {
                sortie_motif: "",
                sortie_qte: "",
                produit_id: null,
            },
            detail: {}
        };
    },
    mounted() {
        this.$nextTick(() => {
            this.viewAllStocks();
            this.viewConfigs();
        });
        this.onModalClosed('append-modal', () => {
            this.cleanFields();
        });
        this.onModalClosed('product-modal', () => {
            this.cleanFields();
        });
        this.onModalClosed('reduce-modal', () => {
            this.cleanFields();
        });
    },

    methods: {
        viewConfigs() {
            get("/configs.all")
                .then((res) => {
                    this.produits = res.data.produits;
                })
                .catch((err) => console.log("error", err));
        },
        viewAllStocks() {
            this.isDataLoading = true;
            get("/stocks.view")
                .then((res) => {
                    this.isDataLoading = false;
                    this.stocks = res.data.results;
                })
                .catch((err) => { console.log("error", err);
                    this.isDataLoading = false; });
        },
        calculateTotalEntree(entrees) {
            if (entrees.length === 0) {
                return 0;
            }
            return entrees.reduce(
                (sum, entree) => parseInt(sum) + parseInt(entree.entree_qte),
                0
            );
        },
        calculateTotalSortie(sorties) {
            if (sorties.length === 0) {
                return 0;
            }
            return sorties.reduce(
                (sum, sortie) => parseInt(sum) + parseInt(sortie.sortie_qte),
                0
            );
        },

        cleanFields() {
            this.formProduit = {
                produit_libelle: "",
                entree: {
                    entree_qte: "",
                    entree_prix_achat: "",
                    entree_prix_devise: "CDF",
                },
            };
            this.formAppend = {
                entree_qte: "",
                entree_prix_achat: "",
                entree_prix_devise: "CDF",
                produit_id: null
            }
            this.formReduce = {
                sortie_motif: "",
                sortie_qte: "",
                produit_id: null,
            };
        },

        showProduct(data) {
            this.formAppend.produit = data;
            this.formAppend.produit_id = data.id;

            setTimeout(() => {
                $("#append-modal").modal("show");
            }, 100);
        },
        showReduce(data) {
            this.formReduce.produit = data;
            this.formReduce.produit_id = data.id;

            setTimeout(() => {
                $("#reduce-modal").modal("show");
            }, 100);
        },
        showDetail(data) {
            this.detail = data;
            setTimeout(() => {
                $("#detail-modal").modal("show");
            }, 100);
        },
        createProduct(event) {
            this.isLoading = true;
            postJson("/product.create", this.formProduit)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors;
                    }
                    if (data.result !== undefined) {
                        this.error = null;
                        $("#product-modal").modal("hide");
                        new Swal({
                            icon: "success",
                            title: "Opération effectuée !",
                            text: "La création du produit effectué avec succès!",
                            timer: 2000,
                            showConfirmButton: false,
                        });
                        this.viewAllStocks();
                        this.viewConfigs();
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                });
        },
        reduceProduct(event) {
            this.isLoading = true;
            postJson("/stock.reduce", this.formReduce)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors;
                    }
                    if (data.result !== undefined) {
                        this.error = null;
                        $("#reduce-modal").modal("hide");
                        new Swal({
                            icon: "success",
                            title: "Opération effectuée !",
                            text: "Le déstockage du produit effectué avec succès!",
                            timer: 2000,
                            showConfirmButton: false,
                        });
                        this.viewAllStocks();
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                });
        },
        appendProduct(event) {
            this.isLoading = true;
            postJson("/stock.append", this.formAppend)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors;
                    }
                    if (data.result !== undefined) {
                        this.error = null;
                        $("#append-modal").modal("hide");
                        new Swal({
                            icon: "success",
                            title: "Opération effectuée !",
                            text: "Nouveau stock ajouté avec succès !",
                            timer: 2000,
                            showConfirmButton: false,
                        });
                        this.viewAllStocks();
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                });
        },
        deleteStock(item) {
            let self = this;
            new Swal({
                title: "Attention! Action irréversible.",
                text: "Voulez-vous vraiment supprimer ce stock des produits ?",
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
                            table: "produits",
                            id: item.id,
                            id_field: "id",
                            state: "produit_state",
                        })
                        .then((res) => {
                            self.load_id = "";
                            self.viewAllStocks();
                        })
                        .catch((err) => {
                            self.load_id = "";
                        });
                }
            });
        },

        onModalClosed(modalId, callback) {
            let myModal = document.getElementById(modalId);
            myModal.addEventListener("hidden.bs.modal", function(event) {
                callback();
            });
        }
    },


    computed: {
        allStocks() {
            // Filtrage en fonction de la recherche
            const filteredStocks =
                this.search && this.search.trim() ?
                this.stocks.filter((el) =>
                    el.produit_libelle
                    .toLowerCase()
                    .includes(this.search.toLowerCase())
                ) :
                this.stocks;
            // Appliquer les calculs sur les résultats filtrés
            return filteredStocks.map((data) => {
                const totalEntree = this.calculateTotalEntree(data.entrees);
                const totalSortie = this.calculateTotalSortie(data.sorties);
                const solde = totalEntree - totalSortie;
                return {
                    ...data,
                    totalEntree,
                    totalSortie,
                    solde,
                    status: totalEntree === 0 ?
                        "Sans stock" : solde === 0 ?
                        "En rupture" : "En stock",
                    percent: totalEntree > 0 ? (solde / totalEntree) * 100 : 0,
                };
            });
        },

        allProducts() {
            return this.produits.map((data) => {
                return {
                    id: data.id,
                    text: data.produit_libelle
                }
            });
        }
    },
});

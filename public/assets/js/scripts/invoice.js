import {get, postJson, post } from "../main/http.js";
import { dollarsToCdf, cdfToDollars } from "../main/util.js";
import Select2 from "../components/select2Component.js";

new Vue({
    el: "#AppInvoice",
    components: {
        Select2
    },
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            isLoading2: false,
            produits: [],
            clients: [],
            comptes: [],
            currencie: '',
            form: {
                facture_montant: '',
                facture_devise: 'USD',
                client_id: null,
                facture_create_At: null,
                facture_details: [{
                    facture_detail_libelle: '',
                    facture_detail_qte: 0,
                    facture_detail_pu: 0.00,
                    facture_detail_devise: 'CDF',
                    facture_detail_nature: '',
                    nature: null,
                    natures: []
                }]
            },
            formPay: {
                compte_id: "",
                facture_id: "",
                operation_montant: "",
                operation_devise: "USD",
                operation_mode: "Cash"
            },
            factures: [],
            selectedFacture: null,
            dayCount: '',
            search: '',
            load_id: ''
        };
    },

    methods: {
        viewAllFactures(key) {
            this.isLoading = true;
            get("/factures.view/" + key)
                .then((res) => {
                    this.isLoading = false;
                    this.factures = res.data.results
                })
                .catch((err) => {
                    console.log("error", err);
                    this.isLoading = false;
                });
        },
        loadDashDatas() {
            get("/dashboard.all")
                .then((res) => {
                    this.dayCount = res.data.day_count;
                })
                .catch((err) => console.log("error", err));
        },
        viewConfigs() {
            get("/configs.all")
                .then((res) => {
                    this.produits = res.data.items;
                    this.currencie = res.data.currencie.currencie_value;
                    this.comptes = res.data.activated_comptes;
                })
                .catch((err) => console.log("error", err));
        },
        viewClients() {
            get("/clients.all")
                .then((res) => {
                    this.clients = res.data.clients;
                })
                .catch((err) => console.log("error", err));
        },
        deleteField(index) {
            if (this.form.facture_details.length === 1) {
                return;
            }
            this.form.facture_details.splice(index, 1);
        },
        addField() {
            this.form.facture_details.push({
                facture_detail_libelle: '',
                facture_detail_qte: 0,
                facture_detail_pu: 0.00,
                facture_detail_devise: 'CDF',
                facture_detail_nature: '',
                nature: null,
                natures: []
            });
        },
        cleanFields() {
            this.form = {
                facture_montant: '',
                facture_devise: 'USD',
                client_id: null,
                facture_create_At: null,
                facture_details: [{
                    facture_detail_libelle: '',
                    facture_detail_qte: 0,
                    facture_detail_pu: 0.00,
                    facture_detail_devise: 'CDF',
                    facture_detail_nature: "",
                    nature: null,
                    natures: []
                }]
            };
        },
        onSelectNature(item) {
            const data = item.nature;
            item.facture_detail_nature = data.item_nature_libelle;
            item.facture_detail_pu = data.item_nature_prix;
            item.facture_detail_devise = data.item_nature_prix_devise;
        },

        onSelectItem(item, event) {
            const data = JSON.parse(JSON.stringify(event));
            item.facture_detail_libelle = data.text;
            item.natures = data.natures;
        },
        createFacture(event) {
            this.form.facture_montant = '' + this.invoiceTotal + '';
            let form = this.form;
            this.isLoading = true;
            postJson("/facture.create", form)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors.toString();
                    }
                    if (data.result !== undefined) {
                        this.error = null;
                        new Swal({
                            icon: "success",
                            title: "Opération effectuée !",
                            text: "La création de la facture effectuée avec succès!",
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
        deleteFacture(id) {
            let self = this;
            new Swal({
                title: "Attention! Action irréversible.",
                text: "Voulez-vous vraiment supprimer cette facture ?",
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
                            table: "factures",
                            id: id,
                            id_field: "id",
                            state: "facture_state",
                        })
                        .then((res) => {
                            self.load_id = "";
                            self.viewAllFactures("all");
                        })
                        .catch((err) => {
                            self.load_id = "";
                        });
                }
            });
        },

        makePayment(event) {
            this.formPay.facture_id = this.selectedFacture.id;
            this.isLoading2 = true;
            postJson("/facture.pay", this.formPay)
                .then(({ data, status }) => {
                    this.isLoading2 = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors.toString();
                    }
                    if (data.result !== undefined) {
                        this.error = null;
                        new Swal({
                            icon: "success",
                            title: "Opération effectuée !",
                            text: "Le paiement effectué avec succès.",
                            timer: 2000,
                            showConfirmButton: false,
                        });
                        this.loadDashDatas();
                        this.viewAllFactures("all");
                        this.formPay = {
                            compte_id: "",
                            facture_id: "",
                            operation_montant: "",
                            operation_devise: "USD",
                            operation_mode: ""
                        };
                    }
                })
                .catch((err) => {
                    this.isLoading2 = false;
                    this.error = err;
                });
        }

    },
    mounted() {
        this.$nextTick(() => {
            this.viewAllFactures("all");
            this.loadDashDatas();
            this.viewConfigs();
            this.viewClients();
        })
    },


    computed: {
        allProducts() {
            return this.produits.map((data) => {
                return {
                    id: data.id,
                    text: data.item_libelle,
                    natures: data.natures
                }
            });
        },

        allComptes() {
            return this.comptes;
        },
        allClients() {
            return this.clients.map((data) => {
                return {
                    id: data.id,
                    text: data.client_nom + ' ' + data.client_tel
                }
            });
        },
        invoiceTotal() {
            let total = 0;
            let currentTot = 0;
            for (var e of this.form.facture_details) {
                let subTotal = parseInt(e.facture_detail_qte) * parseFloat(e.facture_detail_pu);
                if (e.facture_detail_devise ==
                    "CDF") {
                    currentTot =
                        cdfToDollars(this.currencie, subTotal);
                } else {
                    currentTot = subTotal;
                }
                total += currentTot;
            }
            if (isNaN(total)) {
                return 0;
            } else {
                return Math.round(total * 100) / 100;
            }
        },
        cdfAmount() {
            return Math.round(dollarsToCdf(this.currencie, this.invoiceTotal) * 100) / 100;
        },

        totPayment() {
            return (arr) => {
                if (arr.length === 0) {
                    return 0;
                }
                return arr.reduce((total, el) => {
                    return total + parseFloat(el.operation_montant);
                }, 0);
            };
        },

        parseNatureArray() {
            return (arr) => arr.map((el) => {
                return {
                    text: el.item_nature_libelle,
                    id: el.id,
                    item_nature_prix: el.item_nature_prix,
                    item_nature_prix_devise: el.item_nature_prix_devise,
                }
            });
        },
        allFactures() {
            if (this.search && this.search.trim()) {
                return this.factures.filter((el) =>
                    el.client.client_nom
                    .toLowerCase()
                    .includes(this.search.toLowerCase())
                );
            } else {
                return this.factures;
            }
        },

        daySum() {
            return this.dayCount;
        }
    },
});

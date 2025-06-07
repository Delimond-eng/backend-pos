import { get, postJson } from "../main/http.js";
import Select2 from "../components/select2Component.js";

new Vue({
    el: "#AppStock",
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
            type: "",
            date: {
                start: "",
                end: "",
            },
            load_id: "",
            reports: [],
            adjustments: [],
            globals: [],
        };
    },
    mounted() {
        this.$nextTick(() => {
            this.getReports();
            this.getAdjustmentReports();
            this.getStockGlobal();
        });
    },

    methods: {
        getReports() {
            this.isDataLoading = true;
            get(
                `/reports.stock-movements?start=${this.date.start}&end=${this.date.end}`
            )
                .then((res) => {
                    this.isDataLoading = false;
                    this.reports = res.data.stock_movements;
                })
                .catch((err) => {
                    console.log("error", err);
                    this.isDataLoading = false;
                });
        },
        getAdjustmentReports() {
            this.isDataLoading = true;
            get(
                `/reports.adjustments?start=${this.date.start}&end=${this.date.end}`
            )
                .then((res) => {
                    this.isDataLoading = false;
                    this.adjustments = res.data.adjustments;
                })
                .catch((err) => {
                    console.log("error", err);
                    this.isDataLoading = false;
                });
        },
        getStockGlobal() {
            this.isDataLoading = true;
            get("/stock.global")
                .then((res) => {
                    this.isDataLoading = false;
                    this.globals = res.data.reports;
                })
                .catch((err) => {
                    console.log("error", err);
                    this.isDataLoading = false;
                });
        },
    },

    computed: {
        allReports() {
            const search = this.search?.trim().toLowerCase() || "";
            const type = this.type?.trim().toLowerCase() || "";

            // Si aucun filtre n'est renseigné, retourner tous les rapports
            if (!search && !type) {
                return this.reports;
            }

            // Sinon, appliquer les filtres
            return this.reports.filter((el) => {
                const nameMatch = el.product.name
                    ?.toLowerCase()
                    .includes(search);
                const typeMatch = el.type?.toLowerCase().includes(type);

                return (!search || nameMatch) && (!type || typeMatch);
            });
        },

        allAdjustments() {
            return this.adjustments;
        },

        allGlobalStocks() {
            const filteredStocks =
                this.search && this.search.trim()
                    ? this.globals.filter((el) =>
                          el.name
                              .toLowerCase()
                              .includes(this.search.toLowerCase())
                      )
                    : this.globals;
            return filteredStocks;
        },
    },
});

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
            load_id: "",
            reports: [],
        };
    },
    mounted() {
        this.$nextTick(() => {
            this.getReports();
        });
    },

    methods: {
        getReports() {
            this.isDataLoading = true;
            get("/reports.stock-movements")
                .then((res) => {
                    this.isDataLoading = false;
                    this.reports = res.data.stock_movements;
                })
                .catch((err) => {
                    console.log("error", err);
                    this.isDataLoading = false;
                });
        },
    },

    computed: {
        allReports() {
            // Filtrage en fonction de la recherche
            const filteredStocks =
                this.search && this.search.trim()
                    ? this.reports.filter((el) =>
                          el.product.name
                              .toLowerCase()
                              .includes(this.search.toLowerCase())
                      )
                    : this.reports;
            // Appliquer les calculs sur les résultats filtrés
            return filteredStocks;
        },
    },
});

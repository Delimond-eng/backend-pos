import { get, post } from "../main/http.js";
new Vue({
    el: "#AppDashboard",
    data() {
        return {
            error: null,
            result: null,
            isDataLoading: false,
            products: [],
            saleReports: [],
            search: "",
            byDate: "",
            counts: null,
        };
    },

    mounted() {
        this.$nextTick(() => {
            this.loadDashDatas();
            this.viewAllProducts();
            this.getSaleReports();
        });
    },

    methods: {
        loadDashDatas() {
            this.isDataLoading = true;
            get("/reports.counts")
                .then((res) => {
                    this.counts = res.data.result;
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

        getSaleReports() {
            this.isDataLoading = true;
            get(`reports.sales?date=${this.byDate}`)
                .then((res) => {
                    this.isDataLoading = false;
                    this.saleReports = res.data.sales_report;
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error", err);
                });
        },

        downloadSalePdf() {
            location.href = `/sales.reports.export?date=${this.byDate}`;
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
        dashCounts() {
            return this.counts;
        },

        filteredSaleReports() {
            return this.saleReports;
        },
    },
});

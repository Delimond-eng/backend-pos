import {get, post } from "../main/http.js";
new Vue({
    el: "#AppInventory",
    data() {
        return {
            error: null,
            result: null,
            isDataLoading: false,
            load_id: "",
            filterWord: "all",
            selectMonth: '',
            selectYear: '',
            date_start: '',
            date_end: '',
            inventories: [],
            details: []
        };
    },

    mounted() {
        this.$nextTick(() => {
            this.loadInventories();
        })
    },

    methods: {
        loadInventories(event) {
            this.isDataLoading = true;
            let url = "";
            let value = "";
            let key = this.filterWord;
            switch (key) {
                case "all":
                    url = "/inventories.load/all"
                    break;
                case "date":
                    value = [this.date_start, this.date_end].toString();
                    console.log(value);
                    url = "/inventories.load/date/" + value
                    break;
                case "mois":
                    value = this.selectMonth + "-" + this.selectYear
                    url = "inventories.load/mois/" + value
                    break;
            }
            get(url)
                .then((res) => {
                    this.isDataLoading = false;
                    this.inventories = res.data.results;
                })
                .catch((err) => {
                    console.log("error", err);
                    this.isDataLoading = false;
                });
        },

        viewDetails(date) {
            this.load_id = date;
            get("/inventory.details/" + date.replaceAll('/', '-'))
                .then((res) => {
                    this.load_id = '';
                    this.details = res.data.results;
                    $('#detail-modal').modal("show");
                })
                .catch((err) => {
                    console.log("error", err);
                    this.load_id = '';
                });

        }

    },
    computed: {
        allInventories() {
            return this.inventories;
        },
        allDetails() {
            return this.details;
        },
        mois() {
            return [
                { label: "Janvier", value: "01" },
                { label: "Février", value: "02" },
                { label: "Mars", value: "03" },
                { label: "Avril", value: "04" },
                { label: "Mai", value: "05" },
                { label: "Juin", value: "06" },
                { label: "Juillet", value: "07" },
                { label: "Août", value: "08" },
                { label: "Septembre", value: "09" },
                { label: "Octobre", value: "10" },
                { label: "Novembre", value: "11" },
                { label: "Décembre", value: "12" }
            ];
        },
        years() {
            const years = [];
            const currentYear = new Date().getFullYear();
            for (let year = 2022; year <= currentYear; year++) {
                years.push(year.toString());
            }
            return years;
        },
        totalGen() {
            return this.inventories.reduce((total, el) => {
                return total + parseFloat(el.total_amount);
            }, 0);
        }
    },
});
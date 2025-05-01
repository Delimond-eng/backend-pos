import {get, post } from "../main/http.js";
new Vue({
    el: "#AppDashboard",
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            isDataLoading: false,
            dashCounts: [],
            dayPayments: [],
            dayCount: "",
            currencie: "",
        };
    },

    mounted() {
        this.$nextTick(() => {
            this.loadDashDatas();
            let myModal = document.getElementById("currencie-modal");
            let self = this;
            // Add an event listener for the 'hidden.bs.modal' event
            myModal.addEventListener("hidden.bs.modal", function(event) {
                self.loadDashDatas();
            });
        });
    },

    methods: {
        loadDashDatas() {
            this.isDataLoading = true;
            get("/dashboard.all")
                .then((res) => {
                    this.isDataLoading = false;
                    this.dashCounts = res.data.dash_counts;
                    this.dayPayments = res.data.day_payments;
                    this.dayCount = res.data.day_count;
                    this.currencie = res.data.currencie;
                })
                .catch((err) => { console.log("error", err);
                    this.isDataLoading = false; });
        },

        updateCurrencie(event) {
            const formData = new FormData();
            formData.append("currencie_value", this.currencie);
            this.isLoading = true;
            post("/currencie.create", formData)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors;
                    }
                    if (data.result !== undefined) {
                        this.error = null;
                        this.result = data.result;
                        event.target.reset();
                        $("#currencie-modal").modal("hide");
                        this.loadDashDatas();
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                });
        },
    },

    computed: {},
});

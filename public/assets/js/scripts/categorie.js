import { get, post } from "../main/http.js";
new Vue({
    el: "#AppCategories",
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            isDataLoading: false,
            categories: [],
            load_id: "",
            form: {
                name: "",
            },
        };
    },

    mounted() {
        let myModal = document.getElementById("categorie-modal");
        let self = this;
        // Add an event listener for the 'hidden.bs.modal' event
        myModal.addEventListener("hidden.bs.modal", function (event) {
            self.loadCatDatas();
        });
        this.$nextTick(() => {
            this.loadCatDatas();
        });
    },

    methods: {
        loadCatDatas() {
            this.isDataLoading = true;
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

        submitForm(event) {
            const formData = new FormData();
            formData.append("name", this.form.name);
            this.isLoading = true;
            post("/category.create", formData)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors;
                    }
                    if (data.result !== undefined) {
                        this.error = null;
                        this.result = data.result;
                        this.form.name = "";
                        this.loadCatDatas();
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                });
        },

        deleteCat(id) {
            this.load_id = id;
            const formData = new FormData();
            formData.append("table", "product_categories");
            formData.append("id", id);
            formData.append("id_field", "id");
            formData.append("state", "state");
            post("/data.delete", formData)
                .then((res) => {
                    this.load_id = "";
                    this.loadCatDatas();
                })
                .catch((err) => {
                    this.load_id = "";
                });
        },
    },

    computed: {},
});

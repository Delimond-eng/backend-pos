import {get, post } from "../main/http.js";
new Vue({
    el: "#AppUser",
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            isDataLoading: false,
            users: [],
            load_id: "",
            form: {
                name: "",
                password: "",
                role: "",
            },
        };
    },

    mounted() {
        let myModal = document.getElementById("user-modal");
        let self = this;
        // Add an event listener for the 'hidden.bs.modal' event
        myModal.addEventListener("hidden.bs.modal", function(event) {
            self.loadUsersDatas();
        });
        this.$nextTick(() => {
            this.loadUsersDatas();
        });

    },

    methods: {
        loadUsersDatas() {
            this.isDataLoading = true;
            get("/users.all")
                .then((res) => {
                    this.isDataLoading = false;
                    this.users = res.data.users;
                })
                .catch((err) => {
                    console.log("error", err);
                    this.isDataLoading = false;
                });
        },

        submitForm(event) {
            const formData = new FormData();
            formData.append("name", this.form.name);
            formData.append("password", this.form.password);
            formData.append("role", this.form.role);
            this.isLoading = true;
            post("/user.create", formData)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors;
                    }
                    if (data.result !== undefined) {
                        this.error = null;
                        this.result = data.result;
                        this.form.name = "";
                        this.form.phone = "";
                        this.form.adresse = "";
                        $("#user-modal").modal("hide");
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                });
        },

        deleteUser(id) {
            this.load_id = id;
            const formData = new FormData();
            formData.append("table", "users");
            formData.append("id", id);
            formData.append("id_field", "id");
            formData.append("state", "state");
            post("/data.delete", formData)
                .then((res) => {
                    this.load_id = "";
                    this.loadUsersDatas();
                })
                .catch((err) => {
                    this.load_id = "";
                });
        },
    },

    computed: {},
});

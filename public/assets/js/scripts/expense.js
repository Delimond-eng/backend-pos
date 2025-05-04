import { get, post } from "../main/http.js";
new Vue({
    el: "#AppExpenses",
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            isDataLoading: false,
            expenseTypes: [],
            expenses: [],
            load_id: "",
            filter: "",
            form: {
                name: "",
                amount: "",
                expense_type_id: "",
                description: "",
                date: "",
                expense_id: "",
            },
        };
    },

    mounted() {
        let self = this;
        if ($("#expense-modal").length) {
            let myModal = document.getElementById("expense-modal");
            // Add an event listener for the 'hidden.bs.modal' event
            myModal.addEventListener("hidden.bs.modal", function (event) {
                self.loadExpenseTypesDatas();
            });
        }
        if ($("#expense-create-modal").length) {
            let myModalCreate = document.getElementById("expense-create-modal");
            myModalCreate.addEventListener("hidden.bs.modal", function (event) {
                self.loadExpenseTypesDatas();
                self.form = {
                    amount: "",
                    expense_type_id: "",
                    description: "",
                    date: "",
                    expense_id: "",
                };
            });
        }
        this.$nextTick(() => {
            this.loadExpenseTypesDatas();
            this.loadExpenses();
        });
    },

    methods: {
        loadExpenseTypesDatas() {
            this.isDataLoading = true;
            get("/expense_types")
                .then((res) => {
                    this.isDataLoading = false;
                    this.expenseTypes = res.data.expenseTypes;
                })
                .catch((err) => {
                    console.log("error", err);
                    this.isDataLoading = false;
                });
        },
        loadExpenses() {
            this.isDataLoading = true;
            get("/expenses")
                .then((res) => {
                    this.isDataLoading = false;
                    this.expenses = res.data.expenses;
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
            post("/expense_type.create", formData)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors;
                    }
                    if (data.result !== undefined) {
                        this.error = null;
                        this.result = data.result;
                        this.form.name = "";
                        this.loadExpenseTypesDatas();
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                });
        },
        addExpense(event) {
            const formData = new FormData();
            formData.append("amount", this.form.amount);
            formData.append("expense_type_id", this.form.expense_type_id);
            formData.append("description", this.form.description);
            formData.append("date", this.form.date);
            formData.append("expense_id", this.form.expense_id);
            this.isLoading = true;
            post("/expense.create", formData)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    if (data.errors !== undefined) {
                        this.error = data.errors;
                    }
                    if (data.result !== undefined) {
                        this.error = null;
                        this.result = data.result;
                        this.form.name = "";
                        this.loadExpenses();
                        $("#expense-create-modal").modal("hide");
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                });
        },
        editExpense(data) {
            this.form.expense_id = data.id;
            this.form.expense_type_id = data.expense_type_id;
            this.form.date = data.date;
            this.form.amount = data.amount;
            this.form.description = data.description;
            $("#expense-create-modal").modal("show");
        },
        deleteExpenseType(id) {
            let self = this;
            new Swal({
                title: "Attention! Action irréversible.",
                text: "Voulez-vous vraiment supprimer ce type dépense?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirmer",
                cancelButtonText: "Annuler",
            }).then((result) => {
                if (result.isConfirmed) {
                    self.load_id = id;
                    const formData = new FormData();
                    formData.append("table", "expense_types");
                    formData.append("id", id);
                    post("/data.delete", formData)
                        .then((res) => {
                            self.load_id = "";
                            self.loadExpensesDatas();
                        })
                        .catch((err) => {
                            self.load_id = "";
                        });
                }
            });
        },
        deleteExpense(id) {
            let self = this;
            new Swal({
                title: "Attention! Action irréversible.",
                text: "Voulez-vous vraiment supprimer cette dépense?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirmer",
                cancelButtonText: "Annuler",
            }).then((result) => {
                if (result.isConfirmed) {
                    self.load_id = id;
                    const formData = new FormData();
                    formData.append("table", "expenses");
                    formData.append("id", id);
                    post("/data.delete", formData)
                        .then((res) => {
                            self.load_id = "";
                            self.loadExpenses();
                        })
                        .catch((err) => {
                            self.load_id = "";
                        });
                }
            });
        },
    },

    computed: {
        allExpenses() {
            if (this.filter) {
                return this.expenses.filter((el) => el.type.id === this.filter);
            } else {
                return this.expenses;
            }
        },
    },
});

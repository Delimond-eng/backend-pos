new Vue({
    el: "#app",
    data: {
        dropdownOpen: false,
        searchTerm: "",
        currentCategory: "",
        showNumpad: false,
        currentProduct: null,
        numpadQty: "",
        cart: [],
        categories: ["Boissons", "Snacks", "Repas"],
        products: [
            {
                id: 1,
                name: "Coca-Cola",
                price: 1250.0,
                image: "https://images.pexels.com/photos/3819969/pexels-photo-3819969.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
                category: "Boissons",
            },
            {
                id: 2,
                name: "Evian (Water)",
                price: 750.0,
                image: "https://images.pexels.com/photos/327090/pexels-photo-327090.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
                category: "Boissons",
            },
            {
                id: 3,
                name: "Orange Juice",
                price: 600.0,
                image: "https://images.pexels.com/photos/158053/fresh-orange-juice-squeezed-refreshing-citrus-158053.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
                category: "Boissons",
            },
            {
                id: 4,
                name: "Lays Chips",
                price: 800.0,
                image: "img/product-placeholder.jpg",
                category: "Snacks",
            },
            {
                id: 5,
                name: "Chocolate Bar",
                price: 350.0,
                image: "https://images.pexels.com/photos/4467737/pexels-photo-4467737.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
                category: "Snacks",
            },
            {
                id: 6,
                name: "Sandwich",
                price: 300.0,
                image: "https://images.pexels.com/photos/1647163/pexels-photo-1647163.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
                category: "Repas",
            },
            {
                id: 7,
                name: "Pizza Slice",
                price: 500.0,
                image: "https://images.pexels.com/photos/2619970/pexels-photo-2619970.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
                category: "Repas",
            },
            {
                id: 8,
                name: "Croissant",
                price: 1500.0,
                image: "https://images.pexels.com/photos/3892469/pexels-photo-3892469.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
                category: "Repas",
            },
            {
                id: 9,
                name: "Coffee",
                price: 2000.0,
                image: "https://images.pexels.com/photos/312418/pexels-photo-312418.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
                category: "Boissons",
            },
        ],
    },
    computed: {
        filteredProducts() {
            return this.products.filter(
                (p) =>
                    (!this.currentCategory ||
                        p.category === this.currentCategory) &&
                    (!this.searchTerm ||
                        p.name
                            .toLowerCase()
                            .includes(this.searchTerm.toLowerCase()))
            );
        },
        total() {
            return this.cart
                .reduce((sum, item) => sum + item.price * item.quantity, 0)
                .toFixed(2);
        },

        filterNumericInput() {
            this.numpadQty = this.numpadQty.replace(/[^0-9]/g, "");
        },
    },
    methods: {
        filterCategory(cat) {
            this.currentCategory = cat === this.currentCategory ? "" : cat;
        },
        addToCart(product) {
            const found = this.cart.find((p) => p.id === product.id);
            if (found) {
                found.quantity += 1;
            } else {
                this.cart.push({ ...product, quantity: 1 });
            }
        },
        removeFromCart(product) {
            this.cart = this.cart.filter((p) => p.id !== product.id);
        },
        editQuantity(product) {
            this.currentProduct = product;
            this.numpadQty = product.quantity.toString();
            this.showNumpad = true;
        },
        confirmQty() {
            if (this.currentProduct && this.numpadQty) {
                const quantity = parseInt(this.numpadQty);
                if (quantity > 0) {
                    const item = this.cart.find(
                        (p) => p.id === this.currentProduct.id
                    );
                    if (item) {
                        item.quantity = quantity;
                    }
                } else {
                    this.removeFromCart(this.currentProduct);
                }
                this.showNumpad = false;
                this.currentProduct = null;
                this.numpadQty = "";
            }
        },

        handleEnter() {
            if (this.numpadQty && parseInt(this.numpadQty) > 0) {
                this.confirmQty();
            }
        },
        pay() {
            alert("Paiement simulé. Merci !");
            this.cart = [];
        },
    },
});

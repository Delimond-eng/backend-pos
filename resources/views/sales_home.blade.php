@extends("layouts.pos")

@section("content")
<div class="main-content">
    <aside class="sidebar">
        <div class="cart-header">
            <i class="ri-shopping-cart-2-line"></i>
            <h2>Panier</h2>
        </div>

        <div class="cart-items">
            <template v-if="cart.length === 0">
                <div class="empty-cart">
                    <i class="ri-shopping-cart-line"></i>
                    <p>Le Panier est vide !</p>
                </div>
            </template>
            <template v-else>
                <div v-for="item in cart" :key="item.id" class="cart-item">
                    <img src="{{ asset("pos/img/product-placeholder.jpg") }}" :alt="item.name" class="cart-item-image">
                    <div class="cart-item-details">
                        <div class="cart-item-name">
                            @{{ item.name }}
                        </div>
                        <div class="cart-item-info">@{{ item.quantity }} x @{{ item.unit_price }}F</div>
                    </div>
                    <div class="cart-item-actions">
                        <button class="cart-item-btn edit" @click="editQuantity(item)">
                            <i class="ri-edit-line"></i>
                        </button>
                        <button class="cart-item-btn delete" @click="removeFromCart(item)">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <div class="cart-footer">
            <div class="cart-total">
                <span>Total</span>
                <span class="total-amount">@{{ total }}F</span>
            </div>
            <button class="pay-button" :disabled="cart.length === 0 || isLoading" @click="pay">
                <i class="ri-money-dollar-circle-line"></i>
                <span v-if="isLoading">Processing....</span>
                <span v-else>Payer</span>
            </button>
        </div>
    </aside>

    <main class="content">
        <div class="search-container">
            <div class="search-bar">
                <i class="ri-search-2-line"></i>
                <input type="text" placeholder="Recherche produit..." v-model="searchTerm">
            </div>
        </div>

        <div class="categories">
            <button class="category-btn" :class="{ active: currentCategory === '' }"
                @click="currentCategory = ''">
                Tout
            </button>
            <button v-for="category in categories" :key="category" class="category-btn"
                :class="{ active: currentCategory === category.id }" @click="filterCategory(category.id)">
                @{{ category.name }}
            </button>
        </div>

        <div class="products-grid">
            <div v-for="product in filteredProducts" :key="product.id" class="product-card"
                @click="addToCart(product)">
                <img src="{{ asset("pos/img/product-placeholder.jpg") }}" :alt="product.name" class="product-image">
                <div class="product-info">
                    <div class="product-name">
                        @{{ product.name }} <br>
                        <small style="font-weight: 400;">@{{product.category.name}}</small>
                    </div>
                    <div class="product-price">@{{ product.unit_price }}F</div>
                </div>
            </div>
        </div>

        <template v-if="isDataLoading">
            <div class="empty-cart">
                <i class="ri-refresh-line"></i>
                <p>Chargement...</p>
            </div>
        </template>
        <template v-if="filteredProducts.length === 0 && !isDataLoading">
            <div class="empty-cart">
                <i class="ri-search-eye-line"></i>
                <p>Aucun résultat trouvé !</p>
            </div>
        </template>
    </main>
</div>

<div class="numpad-modal" :class="{ active: showNumpad }" @click.self="showNumpad = false">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Modifier la quantité</h3>
            <button class="close-button" @click="showNumpad = false">
                <i class="ri-close-line"></i>
            </button>
        </div>
        <div v-if="currentProduct" class="product-info">
            <div class="product-image">
                <img src="{{ asset("pos/img/product-placeholder.jpg") }}" :alt="currentProduct.name">
            </div>
            <div class="product-details">
                <h4 class="product-name">@{{ currentProduct.name }}</h4>
                <p class="product-price">@{{ currentProduct.unit_price }}F</p>
            </div>
        </div>
        <div class="quantity-display">
            <input type="text" inputmode="none" @input="filterNumericInput" @keyup.enter="handleEnter"
                v-model="numpadQty" id="quantity-input">
        </div>
        <div class="numpad">
            <button v-for="n in 9" :key="n" class="numpad-key" @click="numpadQty += n.toString()">@{{ n
                        }}</button>
            <button class="numpad-key clear-key" @click="numpadQty = ''"><i class="ri-delete-back-2-fill"></i>
            </button>
            <button class="numpad-key" @click="numpadQty += '0'">0</button>
            <button class="numpad-key confirm-key" @click="confirmQty">OK</button>
        </div>
    </div>
</div>
@endsection
@extends("layouts.pos")

@section("content")
<div class="main-content">
    <aside class="sidebar">
        <div class="cart-header">
            <i class="fas fa-shopping-cart"></i>
            <h2>Panier</h2>
        </div>

        <div class="cart-items">
            <template v-if="cart.length === 0">
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Le Panier est vide !</p>
                </div>
            </template>
            <template v-else>
                <div v-for="item in cart" :key="item.id" class="cart-item">
                    <img :src="item.image" :alt="item.name" class="cart-item-image">
                    <div class="cart-item-details">
                        <div class="cart-item-name">
                            @{{ item.name }}
                        </div>
                        <div class="cart-item-info">@{{ item.quantity }} x @{{ item.price.toFixed(2) }}F</div>
                    </div>
                    <div class="cart-item-actions">
                        <button class="cart-item-btn edit" @click="editQuantity(item)">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="cart-item-btn delete" @click="removeFromCart(item)">
                            <i class="fas fa-trash"></i>
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
            <button class="pay-button" :disabled="cart.length === 0" @click="pay">
                <i class="fas fa-credit-card"></i>
                Payer
            </button>
        </div>
    </aside>

    <main class="content">
        <div class="search-container">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Recherche produit..." v-model="searchTerm">
            </div>
        </div>

        <div class="categories">
            <button class="category-btn" :class="{ active: currentCategory === '' }"
                @click="currentCategory = ''">
                Tout
            </button>
            <button v-for="category in categories" :key="category" class="category-btn"
                :class="{ active: currentCategory === category.name }" @click="filterCategory(category.name)">
                @{{ category.name }}
            </button>
        </div>

        <div class="products-grid">
            <div v-for="product in filteredProducts" :key="product.id" class="product-card"
                @click="addToCart(product)">
                <img :src="product.image" :alt="product.name" class="product-image">
                <div class="product-info">
                    <div class="product-name">
                        @{{ product.name }} <br>
                        <small style="font-weight: 400;">@{{product.category}}</small>
                    </div>
                    <div class="product-price">@{{ product.price.toFixed(2) }}F</div>
                </div>
            </div>
        </div>

        <template v-if="filteredProducts.length === 0">
            <div class="empty-cart">
                <i class="fas fa-search"></i>
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
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div v-if="currentProduct" class="product-info">
            <div class="product-image">
                <img :src="currentProduct.image" :alt="currentProduct.name">
            </div>
            <div class="product-details">
                <h4 class="product-name">@{{ currentProduct.name }}</h4>
                <p class="product-price">@{{ currentProduct.price.toFixed(2) }}F</p>
            </div>
        </div>
        <div class="quantity-display">
            <input type="text" inputmode="none" @input="filterNumericInput" @keyup.enter="handleEnter"
                v-model="numpadQty" id="quantity-input">
        </div>
        <div class="numpad">
            <button v-for="n in 9" :key="n" class="numpad-key" @click="numpadQty += n.toString()">@{{ n
                        }}</button>
            <button class="numpad-key clear-key" @click="numpadQty = ''"><i class="fa fa-delete-left"></i>
            </button>
            <button class="numpad-key" @click="numpadQty += '0'">0</button>
            <button class="numpad-key confirm-key" @click="confirmQty">OK</button>
        </div>
    </div>
</div>
@endsection
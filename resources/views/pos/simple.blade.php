<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal POS - Sistema Paraguay</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        
        .header {
            background: white;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #2563eb;
        }
        
        .user-info {
            font-size: 14px;
            color: #666;
        }
        
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }
        
        .search-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .search-input {
            width: 100%;
            padding: 12px 15px 12px 40px;
            font-size: 16px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.3s;
        }
        
        .search-input:focus {
            border-color: #2563eb;
        }
        
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
        
        .search-container {
            position: relative;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .product-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #f0f0f0;
        }
        
        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .product-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }
        
        .product-code {
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
        }
        
        .product-price {
            font-size: 18px;
            font-weight: 700;
            color: #059669;
        }
        
        .product-stock {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }
        
        .sidebar {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            height: fit-content;
        }
        
        .cart-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .cart-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
        
        .sale-type {
            margin: 20px 0;
        }
        
        .sale-type select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        
        .totals {
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: 20px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            font-size: 14px;
        }
        
        .total-final {
            font-size: 18px;
            font-weight: 700;
            color: #333;
            border-top: 1px solid #eee;
            padding-top: 8px;
            margin-top: 8px;
        }
        
        .btn-process {
            width: 100%;
            padding: 15px;
            background: #059669;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: background 0.3s;
        }
        
        .btn-process:hover {
            background: #047857;
        }
        
        .empty-cart {
            text-align: center;
            color: #666;
            font-style: italic;
            margin: 40px 0;
        }
        
        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
                margin: 10px;
                padding: 0 10px;
            }
            
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏪 Terminal POS Paraguay</h1>
        <div class="user-info">
            Usuario: {{ Auth::user()->name ?? 'Demo' }} | Caja: #001
        </div>
    </div>

    <div class="container">
        <div class="left-panel">
            <div class="search-section">
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input 
                        type="text" 
                        class="search-input" 
                        placeholder="Buscar productos por código o nombre..."
                        id="searchInput"
                    >
                </div>
                
                <div class="products-grid" id="productsGrid">
                    <!-- Los productos se cargarán aquí dinámicamente -->
                    <div class="product-card" onclick="addToCart('ALM001', 'Pan de Molde', 12000)">
                        <div class="product-name">Pan de Molde</div>
                        <div class="product-code">ALM001</div>
                        <div class="product-price">₲ 12.000</div>
                        <div class="product-stock">Stock: 50</div>
                    </div>
                    
                    <div class="product-card" onclick="addToCart('ALM002', 'Arroz Tipo 1', 6500)">
                        <div class="product-name">Arroz Tipo 1</div>
                        <div class="product-code">ALM002</div>
                        <div class="product-price">₲ 6.500</div>
                        <div class="product-stock">Stock: 100</div>
                    </div>
                    
                    <div class="product-card" onclick="addToCart('BEB001', 'Coca Cola 2L', 12000)">
                        <div class="product-name">Coca Cola 2L</div>
                        <div class="product-code">BEB001</div>
                        <div class="product-price">₲ 12.000</div>
                        <div class="product-stock">Stock: 24</div>
                    </div>
                    
                    <div class="product-card" onclick="addToCart('BEB002', 'Agua Mineral 500ml', 2500)">
                        <div class="product-name">Agua Mineral 500ml</div>
                        <div class="product-code">BEB002</div>
                        <div class="product-price">₲ 2.500</div>
                        <div class="product-stock">Stock: 120</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebar">
            <div class="cart-header">
                <div class="cart-title">🛒 Carrito de Compras</div>
            </div>
            
            <div class="sale-type">
                <label>Tipo de Venta:</label>
                <select id="saleType">
                    <option value="TICKET">Ticket</option>
                    <option value="FACTURA">Factura</option>
                </select>
            </div>
            
            <div id="cartItems">
                <div class="empty-cart">El carrito está vacío</div>
            </div>
            
            <div class="totals">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span id="subtotal">₲ 0</span>
                </div>
                <div class="total-row">
                    <span>IVA (10%):</span>
                    <span id="iva">₲ 0</span>
                </div>
                <div class="total-row total-final">
                    <span>TOTAL:</span>
                    <span id="total">₲ 0</span>
                </div>
            </div>
            
            <button class="btn-process" onclick="processSale()">
                💳 Procesar Venta
            </button>
        </div>
    </div>

    <script>
        let cart = [];
        
        function addToCart(code, name, price) {
            const existingItem = cart.find(item => item.code === code);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    code: code,
                    name: name,
                    price: price,
                    quantity: 1
                });
            }
            
            updateCart();
        }
        
        function removeFromCart(code) {
            cart = cart.filter(item => item.code !== code);
            updateCart();
        }
        
        function updateQuantity(code, quantity) {
            const item = cart.find(item => item.code === code);
            if (item) {
                item.quantity = parseInt(quantity);
                if (item.quantity <= 0) {
                    removeFromCart(code);
                } else {
                    updateCart();
                }
            }
        }
        
        function updateCart() {
            const cartItems = document.getElementById('cartItems');
            
            if (cart.length === 0) {
                cartItems.innerHTML = '<div class="empty-cart">El carrito está vacío</div>';
                document.getElementById('subtotal').textContent = '₲ 0';
                document.getElementById('iva').textContent = '₲ 0';
                document.getElementById('total').textContent = '₲ 0';
                return;
            }
            
            let html = '';
            let subtotal = 0;
            
            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;
                
                html += `
                    <div style="border-bottom: 1px solid #eee; padding: 10px 0; margin: 10px 0;">
                        <div style="font-weight: 600; margin-bottom: 5px;">${item.name}</div>
                        <div style="font-size: 12px; color: #666; margin-bottom: 5px;">${item.code}</div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <input type="number" value="${item.quantity}" min="1" 
                                       onchange="updateQuantity('${item.code}', this.value)"
                                       style="width: 60px; padding: 5px; border: 1px solid #ddd; border-radius: 4px;">
                                <span style="font-size: 12px; color: #666;">x ₲ ${item.price.toLocaleString()}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="font-weight: 600;">₲ ${itemTotal.toLocaleString()}</span>
                                <button onclick="removeFromCart('${item.code}')" 
                                        style="color: #dc2626; background: none; border: none; cursor: pointer; font-size: 16px;">🗑️</button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            cartItems.innerHTML = html;
            
            const iva = subtotal * 0.1;
            const total = subtotal + iva;
            
            document.getElementById('subtotal').textContent = '₲ ' + subtotal.toLocaleString();
            document.getElementById('iva').textContent = '₲ ' + Math.round(iva).toLocaleString();
            document.getElementById('total').textContent = '₲ ' + Math.round(total).toLocaleString();
        }
        
        function processSale() {
            if (cart.length === 0) {
                alert('El carrito está vacío');
                return;
            }
            
            const saleType = document.getElementById('saleType').value;
            const total = document.getElementById('total').textContent;
            
            if (confirm(`¿Procesar venta de ${total}?`)) {
                alert(`✅ Venta procesada exitosamente!\nTipo: ${saleType}\nTotal: ${total}`);
                cart = [];
                updateCart();
            }
        }
        
        // Búsqueda simple
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const productCards = document.querySelectorAll('.product-card');
            
            productCards.forEach(card => {
                const name = card.querySelector('.product-name').textContent.toLowerCase();
                const code = card.querySelector('.product-code').textContent.toLowerCase();
                
                if (name.includes(searchTerm) || code.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
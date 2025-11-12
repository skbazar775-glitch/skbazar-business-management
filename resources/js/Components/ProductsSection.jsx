<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SK Bazar - Product Display</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: linear-gradient(135deg, #2c3e50, #4a6491);
            color: white;
            padding: 20px 0;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }
        
        .product-image {
            height: 200px;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-weight: bold;
        }
        
        .product-content {
            padding: 20px;
        }
        
        .product-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .product-type {
            color: #3498db;
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .product-details {
            margin-bottom: 15px;
            color: #555;
        }
        
        .stock-info {
            display: inline-block;
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 15px;
        }
        
        .add-to-cart {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .add-to-cart:hover {
            background-color: #2980b9;
        }
        
        .view-all {
            display: block;
            text-align: center;
            margin: 30px 0;
            color: #3498db;
            font-weight: 600;
            text-decoration: none;
            font-size: 1.1rem;
        }
        
        .view-all:hover {
            text-decoration: underline;
        }
        
        hr {
            border: none;
            height: 1px;
            background-color: #ddd;
            margin: 30px 0;
        }
        
        footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
            
            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>SK Bazar</h1>
            <p>Your trusted source for quality solar products</p>
        </header>
        
        <div class="products-grid">
            <div class="product-card">
                <div class="product-image">
                    [Product Image: Eastman EMP125W]
                </div>
                <div class="product-content">
                    <h2 class="product-title">Eastman EMP125W - 125W</h2>
                    <div class="product-type">Mono Perc Non-DCR Solar Panel</div>
                    <div class="product-details">
                        <p>Pack of 1 | Suitable for Home, Office & Shops</p>
                        <p>5 Years Product Warranty | 25 Years Performance Warranty</p>
                        <p>Superior Low Light Performance</p>
                    </div>
                    <span class="stock-info">250 In Stock</span>
                    <button class="add-to-cart">Add to Cart</button>
                </div>
            </div>
            
            <div class="product-card">
                <div class="product-image">
                    [Product Image: Electronic Spices Solar Panel]
                </div>
                <div class="product-content">
                    <h2 class="product-title">Electronic Spices Solar for DIY</h2>
                    <div class="product-type">Solar Panel 6v-100 mah</div>
                    <div class="product-details">
                        <p>70mm × 70mm × 03mm | Square Shape</p>
                        <p>4 LEDs | 2 ON/Off Switch | 2 Meter Wire</p>
                        <p>Pack of 2</p>
                    </div>
                    <span class="stock-info">100 In Stock</span>
                    <button class="add-to-cart">Add to Cart</button>
                </div>
            </div>
        </div>
        
        <a href="#" class="view-all">View All Products →</a>
        
        <hr>
        
        <footer>
            <p>SK Bazar &copy; 2023 | All Rights Reserved</p>
        </footer>
    </div>

    <script>
        // JavaScript to handle Add to Cart functionality
        document.addEventListener('DOMContentLoaded', function() {
            const addToCartButtons = document.querySelectorAll('.add-to-cart');
            
            addToCartButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productCard = this.closest('.product-card');
                    const productTitle = productCard.querySelector('.product-title').textContent;
                    
                    // Show confirmation message
                    alert(`"${productTitle}" has been added to your cart!`);
                    
                    // In a real application, you would add the product to a cart array or send to server
                });
            });
        });
    </script>
</body>
</html>
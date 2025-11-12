import React, { useEffect } from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { fetchProducts, fetchCategories, clearProducts } from '../../Redux/productSlice';
import { addToCart, updateQuantity } from '../../Redux/cartSlice';
import { openCartModal } from '../../Redux/modalSlice';
import { router } from '@inertiajs/react';
import Header from '@/Components/Ecoms/Header';
import Category from '@/Components/Ecoms/Category';

const Products = () => {
  const dispatch = useDispatch();
  const { products, categories, selectedCategory, loading, error } = useSelector((state) => state.product);
  const cartItems = useSelector((state) => state.cart.items);

  // Debug logging
  console.log('Products state:', products);
  console.log('Categories state:', categories);
  console.log('Loading:', loading);
  console.log('Error:', error);

  useEffect(() => {
    console.log('Dispatching fetch actions...');
    dispatch(fetchProducts());
    dispatch(fetchCategories());
    
    return () => {
      dispatch(clearProducts());
    };
  }, [dispatch]);

  const handleQuantityChange = (productId, newQuantity) => {
    if (newQuantity >= 0) {
      dispatch(updateQuantity({ id: productId, quantity: newQuantity }));
    }
  };

  // Enhanced loading state
  if (loading) {
    return (
      <div className="flex flex-col min-h-screen bg-gray-900">
        <Header />
        <main className="flex-grow py-16 px-4 lg:p-8 bg-gray-50 text-center">
          <div className="flex flex-col items-center justify-center">
            <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
            <h1 className="text-2xl font-bold text-gray-900 mb-2">Loading Products...</h1>
            <p className="text-gray-600">Please wait while we load our products</p>
          </div>
        </main>
      </div>
    );
  }

  // Enhanced error state
  if (error) {
    return (
      <div className="flex flex-col min-h-screen bg-gray-900">
        <Header />
        <main className="flex-grow py-16 px-4 lg:p-8 bg-gray-50 text-center">
          <div className="max-w-md mx-auto">
            <div className="text-red-500 text-6xl mb-4">⚠️</div>
            <h1 className="text-2xl font-bold text-gray-900 mb-4">Error Loading Products</h1>
            <p className="text-gray-600 mb-6">{error}</p>
            <div className="space-x-4">
              <button
                onClick={() => {
                  dispatch(fetchProducts());
                  dispatch(fetchCategories());
                }}
                className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
              >
                Try Again
              </button>
              <button
                onClick={() => router.visit('/')}
                className="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
              >
                Back to Home
              </button>
            </div>
          </div>
        </main>
      </div>
    );
  }

  // Check if we have any data
  const hasData = products.length > 0 || categories.length > 0;
  
  if (!hasData && !loading) {
    return (
      <div className="flex flex-col min-h-screen bg-gray-900">
        <Header />
        <main className="flex-grow py-16 px-4 lg:p-8 bg-gray-50 text-center">
          <div className="max-w-md mx-auto">
            <div className="text-gray-400 text-6xl mb-4">📦</div>
            <h1 className="text-2xl font-bold text-gray-900 mb-4">No Products Available</h1>
            <p className="text-gray-600 mb-6">We couldn't find any products to display.</p>
            <button
              onClick={() => {
                dispatch(fetchProducts());
                dispatch(fetchCategories());
              }}
              className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
              Reload Products
            </button>
          </div>
        </main>
      </div>
    );
  }

  const filteredProducts = selectedCategory
    ? products.filter((product) => product.category_id === selectedCategory)
    : products;

  return (
    <div className="flex flex-col min-h-screen bg-gray-900">
      <Header />
      <main className="flex-grow bg-gray-50">
        <div className="max-w-7xl mx-auto h-full flex flex-col lg:flex-row">
          {/* Category Sidebar */}
          <div className="lg:w-64 lg:border-r lg:border-gray-200 lg:h-full">
            <div className="lg:fixed lg:w-64 lg:h-[calc(100vh-80px)] lg:overflow-y-auto lg:py-4 lg:px-2 mt-[40px]">
              <Category />
            </div>
          </div>
          
          {/* Products Grid */}
          <div className="flex-1 lg:h-[calc(100vh-80px)] lg:overflow-y-auto lg:py-4 mt-[40px]">
            <div className="px-4 lg:px-8">
              <h1 className="text-2xl lg:text-3xl font-bold text-gray-900 mb-4 lg:mb-6 sticky top-0 bg-gray-50 py-4 z-10">
                {selectedCategory
                  ? categories.find((cat) => cat.id === selectedCategory)?.title || 'Products'
                  : 'All Products'}
                <span className="text-sm text-gray-500 font-normal ml-2">
                  ({filteredProducts.length} products)
                </span>
              </h1>
              
              {filteredProducts.length === 0 ? (
                <div className="h-[calc(100vh-180px)] flex flex-col items-center justify-center">
                  <div className="text-gray-400 text-6xl mb-4">🔍</div>
                  <p className="text-gray-600 text-lg mb-2">No products found</p>
                  <p className="text-gray-500 text-sm">
                    {selectedCategory ? 'Try selecting a different category' : 'No products available'}
                  </p>
                </div>
              ) : (
                <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4 lg:gap-6 pb-8">
                  {filteredProducts.map((product) => {
                    const cartItem = cartItems.find((item) => item.id === product.id);
                    const quantity = cartItem ? cartItem.quantity : 0;
                    const isOutOfStock = !product.inStock;

                    const handleAddToCart = () => {
                      dispatch(
                        addToCart({
                          id: product.id,
                          title: product.title,
                          price: product.price,
                          image: product.image,
                          quantity: 1,
                        })
                      );
                    };

                    const handleBuyNow = () => {
                      if (!cartItems.find((item) => item.id === product.id)) {
                        dispatch(
                          addToCart({
                            id: product.id,
                            title: product.title,
                            price: product.price,
                            image: product.image,
                            quantity: 1,
                          })
                        );
                      }
                      dispatch(openCartModal({ modalType: 'normal' }));
                    };

                    return (
                      <div
                        key={product.id}
                        className={`bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 relative overflow-hidden ${
                          isOutOfStock ? 'opacity-60' : ''
                        }`}
                      >
                        {isOutOfStock && (
                          <div className="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded z-10">
                            Out of Stock
                          </div>
                        )}
                        
                        <div className="p-4">
                          {/* Product Status */}
                          <div className="mb-2">
                            <span className="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                              {product.status_text || 'Available'}
                            </span>
                          </div>
                          
                          {/* Product Image */}
                          <div className="w-full h-48 bg-gray-200 rounded-lg mb-3 flex items-center justify-center overflow-hidden">
                            {product.image ? (
                              <img
                                src={product.image}
                                alt={product.alt || product.title}
                                className="w-full h-full object-cover"
                                loading="lazy"
                                onError={(e) => {
                                  e.target.style.display = 'none';
                                  e.target.nextSibling.style.display = 'flex';
                                }}
                              />
                            ) : null}
                            <div className="hidden text-gray-400 text-sm flex-col items-center justify-center">
                              <span className="text-2xl mb-1">📦</span>
                              <span>No Image</span>
                            </div>
                          </div>
                          
                          {/* Product Info */}
                          <h2 className="text-base font-bold text-gray-900 mb-2 line-clamp-2 h-12">
                            {product.title}
                          </h2>
                          <p className="text-sm text-gray-600 mb-3 line-clamp-2 h-10">
                            {product.description}
                          </p>

                          {/* Price */}
                          <div className="flex justify-between items-center mb-3">
                            <span className="text-lg font-black text-green-600">
                              ₹{product.price ? product.price.toLocaleString('en-IN') : 'N/A'}
                            </span>
                            {product.oldPrice && product.oldPrice > product.price && (
                              <span className="text-xs text-gray-500 line-through ml-2 font-medium">
                                ₹{product.oldPrice.toLocaleString('en-IN')}
                              </span>
                            )}
                          </div>
                        </div>

                        {/* Action Buttons */}
                        <div className="px-4 pb-4 space-y-3">
                          {quantity > 0 ? (
                            <div className="flex items-center justify-between bg-gray-100 rounded-lg p-2">
                              <button
                                onClick={() => handleQuantityChange(product.id, quantity - 1)}
                                className="w-8 h-8 flex items-center justify-center bg-white rounded-lg hover:bg-gray-200 transition-colors duration-200 shadow-sm"
                                disabled={isOutOfStock}
                              >
                                -
                              </button>
                              <span className="text-gray-900 font-medium">{quantity}</span>
                              <button
                                onClick={() => handleQuantityChange(product.id, quantity + 1)}
                                className="w-8 h-8 flex items-center justify-center bg-white rounded-lg hover:bg-gray-200 transition-colors duration-200 shadow-sm"
                                disabled={isOutOfStock}
                              >
                                +
                              </button>
                            </div>
                          ) : (
                            <button
                              onClick={handleAddToCart}
                              className={`w-full py-3 text-white text-sm font-medium rounded-lg transition-all duration-300 ${
                                isOutOfStock 
                                  ? 'bg-gray-400 cursor-not-allowed' 
                                  : 'bg-gray-900 hover:bg-gray-800'
                              }`}
                              disabled={isOutOfStock}
                            >
                              {isOutOfStock ? 'Out of Stock' : 'Add to Cart'}
                            </button>
                          )}
                          
                          <button
                            onClick={handleBuyNow}
                            className={`w-full py-3 text-white text-sm font-medium rounded-lg transition-all duration-300 ${
                              isOutOfStock 
                                ? 'bg-gray-400 cursor-not-allowed' 
                                : 'bg-green-600 hover:bg-green-700'
                            }`}
                            disabled={isOutOfStock}
                          >
                            {isOutOfStock ? 'Unavailable' : 'Buy Now'}
                          </button>
                        </div>
                      </div>
                    );
                  })}
                </div>
              )}
            </div>
          </div>
        </div>
      </main>
    </div>
  );
};

export default Products;
import React, { useEffect, useMemo } from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { searchProducts } from '../Redux/productSlice';
import { addToCart, updateQuantity, removeFromCart } from '../Redux/cartSlice';
import { openCartModal } from '../Redux/modalSlice';
import SearchHeader from '@/Components/Ecoms/SearchHeader';
import Skeleton, { SkeletonTheme } from 'react-loading-skeleton';
import 'react-loading-skeleton/dist/skeleton.css';
import { FiShoppingCart } from 'react-icons/fi';

const Search = () => {
  const dispatch = useDispatch();
  const { searchResults, relatedProducts, loading, error } = useSelector((state) => state.product);
  const cartItems = useSelector((state) => state.cart.items);
  const query = new URLSearchParams(window.location.search).get('q') || '';

  useEffect(() => {
    if (query.trim()) {
      dispatch(searchProducts(query));
    }
  }, [dispatch, query]);

  const handleQuantityChange = (productId, newQuantity) => {
    if (newQuantity > 0) {
      dispatch(updateQuantity({ id: productId, quantity: newQuantity }));
    } else {
      dispatch(removeFromCart(productId));
    }
  };

  const renderProductCard = (product) => {
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
        className={`bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 relative ${
          isOutOfStock ? 'bg-gray-200 filter grayscale pointer-events-none' : ''
        }`}
      >
        {isOutOfStock && (
          <div className="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full">
            Out of Stock
          </div>
        )}
        <div className="mb-4">
          <span className="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
            {product.status_text || 'Unknown Status'}
          </span>
        </div>
        <img
          src={product.image}
          alt={product.alt || product.title || 'Product image'}
          className="w-full h-64 object-cover rounded-lg mb-4 transition-transform duration-300 hover:scale-105"
          loading="lazy"
        />
        <h2 className="text-xl font-bold text-gray-900 mb-2">{product.title}</h2>
        <p className="text-gray-600 mb-4 line-clamp-2">{product.description}</p>
        <div className="mb-4">
          <span className="text-lg font-bold text-gray-900">
            Min Sell: {product.sellinQUANTITY ? product.sellinQUANTITY.toLocaleString('en-IN') : 'N/A'}
          </span>
          {product.sellinQUANTITYunit && (
            <span className="text-base font-medium text-gray-600 ml-2">
              {product.sellinQUANTITYunit}
            </span>
          )}
        </div>
        <div className="flex justify-between items-center mb-4">
          <span className="text-2xl font-black text-green-500" style={{ fontFamily: 'Arial, sans-serif' }}>
            ₹{product.price ? product.price.toLocaleString('en-IN') : 'N/A'}
          </span>
          {product.oldPrice && (
            <span className="text-sm text-gray-500 line-through ml-2 font-medium">
              ₹{product.oldPrice.toLocaleString('en-IN')}
            </span>
          )}
        </div>
        <div className="space-y-4">
          {quantity > 0 ? (
            <div className="flex items-center justify-center space-x-4">
              <button
                onClick={() => handleQuantityChange(product.id, quantity - 1)}
                className="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200"
                aria-label={`Decrease quantity of ${product.title}`}
                disabled={isOutOfStock}
              >
                -
              </button>
              <span className="text-gray-900 font-medium w-8 text-center">{quantity}</span>
              <button
                onClick={() => handleQuantityChange(product.id, quantity + 1)}
                className="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200"
                aria-label={`Increase quantity of ${product.title}`}
                disabled={isOutOfStock}
              >
                +
              </button>
            </div>
          ) : (
            <button
              onClick={handleAddToCart}
              className="w-full flex items-center justify-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-lg transition-colors duration-300 disabled:opacity-50"
              disabled={isOutOfStock}
              aria-label={`Add ${product.title} to cart`}
            >
              <FiShoppingCart className="h-5 w-5 mr-2" aria-hidden="true" />
              Add to Cart
            </button>
          )}
          <button
            onClick={handleBuyNow}
            className="w-full flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-300 disabled:opacity-50"
            disabled={isOutOfStock}
            aria-label={`Buy ${product.title} now`}
          >
            Buy Now
          </button>
        </div>
      </div>
    );
  };

  // Memoize product cards to optimize rendering
  const searchResultCards = useMemo(() => searchResults.map(renderProductCard), [searchResults, cartItems]);
  const relatedProductCards = useMemo(() => relatedProducts.map(renderProductCard), [relatedProducts, cartItems]);

  if (loading) {
    return (
      <div className="flex flex-col min-h-screen">
        <SearchHeader />
        <main className="flex-grow py-16 px-4 lg:px-8 bg-gray-50 mt-20">
          <div className="max-w-7xl mx-auto">
            <SkeletonTheme baseColor="#e5e7eb" highlightColor="#f3f4f6">
              <Skeleton height={36} width={300} className="mb-8" />
              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                {[...Array(6)].map((_, index) => (
                  <div key={index} className="bg-white p-6 rounded-xl shadow-lg">
                    <Skeleton height={256} className="mb-4" />
                    <Skeleton height={24} width="80%" className="mb-2" />
                    <Skeleton height={16} count={2} className="mb-4" />
                    <Skeleton height={20} width="60%" className="mb-4" />
                    <Skeleton height={40} className="mb-2" />
                    <Skeleton height={40} />
                  </div>
                ))}
              </div>
            </SkeletonTheme>
          </div>
        </main>
      </div>
    );
  }

  if (error) {
    return (
      <div className="flex flex-col min-h-screen">
        <SearchHeader />
        <main className="flex-grow py-16 px-4 lg:px-8 bg-gray-50 text-center mt-20">
          <h1 className="text-3xl font-bold text-red-600 mb-4">
            {error === 'network' ? 'Network Error: Please try again later' : error}
          </h1>
        </main>
      </div>
    );
  }

  return (
    <div className="flex flex-col min-h-screen">
      <SearchHeader />
      <main className="flex-grow py-16 px-4 lg:px-8 bg-gray-50 mt-20">
        <div className="max-w-7xl mx-auto">
          <h1 className="text-3xl font-bold text-gray-900 mb-8">Search Results for "{query}"</h1>
          {searchResultCards.length === 0 ? (
            <p className="text-gray-600 mb-8">No products found for your search.</p>
          ) : (
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
              {searchResultCards}
            </div>
          )}
          <h1 className="text-3xl font-bold text-gray-900 mb-8">Related Products</h1>
          {relatedProductCards.length === 0 ? (
            <p className="text-gray-600">No related products found.</p>
          ) : (
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              {relatedProductCards}
            </div>
          )}
        </div>
      </main>
    </div>
  );
};

export default Search;
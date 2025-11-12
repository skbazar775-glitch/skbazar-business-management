import React from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { addToCart, updateQuantity } from '../../Redux/cartSlice';
import { openCartModal } from '../../Redux/modalSlice';
import { router, usePage } from '@inertiajs/react';
import Header from '@/Components/Ecoms/Header';

const ProductDetails = () => {
  const { url } = usePage();
  const productId = parseInt(url.split('/').pop()); // Extract ID from URL
  const dispatch = useDispatch();
  const cartItems = useSelector((state) => state.cart.items);

  const products = [
    {
      id: 1,
      image: 'https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
      alt: 'Solar Panel',
      title: 'Mono Panel 450W',
      price: 14499,
      oldPrice: 16999,
      inStock: true,
      description: 'High-efficiency 450W monocrystalline solar panel with advanced cell technology.',
      features: ['High durability', 'Weather-resistant', '25-year warranty'],
    },
    {
      id: 2,
      image: 'https://images.unsplash.com/photo-1605980776566-0486c3ac7617?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
      alt: 'Solar Inverter',
      title: 'Hybrid Solar Inverter 5KVA',
      price: 34999,
      oldPrice: 39999,
      inStock: true,
      description: '5KVA hybrid solar inverter for seamless power conversion and backup.',
      features: ['Pure sine wave output', 'Built-in MPPT', 'LCD display'],
    },
    {
      id: 3,
      image: 'https://images.unsplash.com/photo-1604357209793-fca5dca89f97?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
      alt: 'Solar Battery',
      title: 'Lithium Solar Battery 5KWh',
      price: 62500,
      oldPrice: 69999,
      inStock: true,
      description: '5KWh lithium-ion battery for reliable solar energy storage.',
      features: ['Long lifespan', 'Fast charging', 'Compact design'],
    },
    {
      id: 4,
      image: 'https://images.unsplash.com/photo-1624395213043-fa2e123b2656?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
      alt: 'EV Charger',
      title: 'Solar EV Charger 7.4kW',
      price: 28750,
      oldPrice: 32499,
      inStock: true,
      description: '7.4kW solar-powered EV charger for fast and eco-friendly charging.',
      features: ['Smart charging', 'IP65 rating', 'App control'],
    },
    {
      id: 5,
      image: 'https://images.unsplash.com/photo-1584273143981-725228f05d68?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
      alt: 'Solar Panel',
      title: 'Poly Panel 330W',
      price: 10999,
      oldPrice: 12999,
      inStock: true,
      description: '330W polycrystalline solar panel for cost-effective energy production.',
      features: ['High efficiency', 'Robust frame', '20-year warranty'],
    },
    {
      id: 6,
      image: 'https://images.unsplash.com/photo-1612287041149-2a86d1d47a7b?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
      alt: 'Solar Controller',
      title: 'MPPT Charge Controller 60A',
      price: 8499,
      oldPrice: 9999,
      inStock: true,
      description: '60A MPPT charge controller for optimized solar energy harvesting.',
      features: ['High tracking efficiency', 'Multiple protections', 'LED indicators'],
    },
    {
      id: 7,
      image: 'https://images.unsplash.com/photo-1612287041149-2a86d1d47a7b?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
      alt: 'Solar Controller',
      title: 'MPPT Charge Controller 60A (Model B)',
      price: 8499,
      oldPrice: 9999,
      inStock: true,
      description: 'Advanced 60A MPPT charge controller with enhanced cooling.',
      features: ['Improved heat dissipation', 'Multiple protections', 'LED indicators'],
    },
    {
      id: 8,
      image: 'https://images.unsplash.com/photo-1612287041149-2a86d1d47a7b?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
      alt: 'Solar Controller',
      title: 'MPPT Charge Controller 60A (Model C)',
      price: 8499,
      oldPrice: 9999,
      inStock: true,
      description: '60A MPPT charge controller with smart monitoring features.',
      features: ['Bluetooth connectivity', 'Multiple protections', 'LED indicators'],
    },
    {
      id: 9,
      image: 'https://images.unsplash.com/photo-1599007993615-4df1d3f1c05e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
      alt: 'Solar Mounting',
      title: 'Adjustable Solar Mounting Kit',
      price: 5799,
      oldPrice: 6499,
      inStock: true,
      description: 'Adjustable mounting kit for secure solar panel installation.',
      features: ['Rust-resistant', 'Easy installation', 'Adjustable angles'],
    },
    {
      id: 10,
      image: 'https://images.unsplash.com/photo-1596191811932-dfa27a1a2122?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
      alt: 'Solar Charger',
      title: 'Solar Charger',
      price: 200,
      oldPrice: 100,
      inStock: true,
      description: 'Portable solar charger for small devices.',
      features: ['Rust-resistant', 'Easy installation', 'High thermal efficiency'],
    },
  ];

  const product = products.find((p) => p.id === productId);

  if (!product) {
    return (
      <div className="flex flex-col min-h-screen bg-gray-900">
        <Header />
        <main className="flex-grow py-16 px-4 lg:p-8 bg-gray-50 text-center">
          <h1 className="text-3xl font-bold text-gray-900 mb-4">Product Not Found</h1>
          <button
            onClick={() => router.visit('/products')}
            className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            aria-label="Back to Products"
          >
            Back to Products
          </button>
        </main>
      </div>
    );
  }

  const cartItem = cartItems.find((item) => item.id === product.id);
  const quantity = cartItem ? cartItem.quantity : 0;

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

  const handleQuantityChange = (newQuantity) => {
    if (newQuantity >= 0) {
      dispatch(updateQuantity({ id: product.id, quantity: newQuantity }));
    }
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

  const handleBackToProducts = () => {
    router.visit('/products');
  };

  return (
    <div className="flex flex-col min-h-screen bg-gray-900">
      <Header />
      <main className="flex-grow py-16 px-4 lg:px-8 bg-gray-50">
        <div className="max-w-5xl mx-auto">
          <button
            onClick={handleBackToProducts}
            className="mb-6 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none"
            aria-label="Back to products"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              className="h-5 w-5 mr-2"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              aria-hidden="true"
            >
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
            </svg>
            Back to Products
          </button>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white p-8 rounded-xl shadow-md">
            {/* Product Image */}
            <div className="relative">
              <img
                src={product.image}
                alt={product.alt}
                className="w-full h-96 object-cover rounded-lg"
                loading="lazy"
              />
            </div>

            {/* Product Details */}
            <div className="flex flex-col justify-between">
              <div>
                <h1 className="text-3xl font-bold text-gray-900 mb-4">{product.title}</h1>
                <p className="text-gray-600 mb-4">{product.description}</p>

                <div className="mb-4">
                  <span className="text-2xl font-bold text-gray-900">₹{product.price.toLocaleString('en-IN')}</span>
                  {product.oldPrice && (
                    <span className="text-sm text-gray-500 line-through ml-2">
                      ₹{product.oldPrice.toLocaleString('en-IN')}
                    </span>
                  )}
                </div>

                <div className="mb-4">
                  <span className={`text-sm font-medium ${product.inStock ? 'text-green-600' : 'text-blue-600'}`}>
                    {product.inStock ? 'In Stock' : 'Out of Stock'}
                  </span>
                </div>

                <div className="mb-6">
                  <h3 className="text-lg font-semibold text-gray-900 mb-2">Features</h3>
                  <ul className="list-disc pl-5 text-gray-600">
                    {product.features.map((feature, index) => (
                      <li key={index}>{feature}</li>
                    ))}
                  </ul>
                </div>
              </div>

              {/* Actions */}
              <div className="space-y-4">
                {quantity > 0 ? (
                  <div className="flex items-center space-x-4">
                    <button
                      onClick={() => handleQuantityChange(quantity - 1)}
                      className="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300"
                      aria-label={`Decrease quantity of ${product.title}`}
                    >
                      -
                    </button>
                    <span className="text-gray-900 font-medium">{quantity}</span>
                    <button
                      onClick={() => handleQuantityChange(quantity + 1)}
                      className="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300"
                      aria-label={`Increase quantity of ${product.title}`}
                    >
                      +
                    </button>
                  </div>
                ) : (
                  <button
                    onClick={handleAddToCart}
                    className="w-full flex items-center justify-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-lg transition-colors duration-300 disabled:opacity-50"
                    disabled={!product.inStock}
                    aria-label={`Add ${product.title} to cart`}
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      className="h-5 w-5 mr-2"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      aria-hidden="true"
                    >
                      <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth={2}
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                      />
                    </svg>
                    Add to Cart
                  </button>
                )}
                <button
                  onClick={handleBuyNow}
                  className="w-full flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-300"
                  aria-label={`Buy ${product.title} now`}
                >
                  Buy Now
                </button>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  );
};

export default ProductDetails;
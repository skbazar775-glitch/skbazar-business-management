import React, { useState, useEffect, useRef } from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import { useSelector, useDispatch } from 'react-redux';
import { openCartModal } from '../../Redux/modalSlice';
import CartModal from './CartModal';
import { FiMenu, FiX, FiSearch, FiShoppingCart } from 'react-icons/fi';
import { FaBoxOpen,FaHandshake, FaUserCircle, FaStore } from 'react-icons/fa';

const SearchHeader = () => {
  const [searchQuery, setSearchQuery] = useState('');
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const dispatch = useDispatch();
  const cartItems = useSelector((state) => state.cart.items);
  const cartItemCount = cartItems.reduce((total, item) => total + item.quantity, 0);
  const { props } = usePage();
  const user = props.auth?.user;
  const searchInputRef = useRef(null); // Create a ref for the search input

  // Focus the search input when the component mounts
  useEffect(() => {
    searchInputRef.current?.focus();
  }, []);

  const handleSearch = (e) => {
    e.preventDefault();
    if (searchQuery.trim()) {
      router.visit(`/search?q=${encodeURIComponent(searchQuery)}`);
    }
  };

  const toggleMenu = () => {
    setIsMenuOpen(!isMenuOpen);
  };

  const handleCartClick = () => {
    dispatch(openCartModal({ modalType: 'full-width' }));
  };

  return (
    <>
      <header className="w-full bg-transparent backdrop-blur-xl fixed top-0 z-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between flex-wrap">
          <div className="flex items-center justify-between w-full md:w-auto">
            <a href="/" className="text-xl font-bold text-gray-900 flex items-center">
              <span className="bg-white/20 px-3 py-1 rounded-full backdrop-blur-md border border-white/30 shadow-sm text-gray-900 flex items-center">
<span className="bg-white/20 px-3 py-1 rounded-full backdrop-blur-md border border-white/30 shadow-sm text-gray-900 flex items-center">
  <img src="/logo/logo.png" alt="SkBazar Logo" className="h-6 w-auto" />
</span>

              </span>
            </a>
            <button
              className="md:hidden p-2 text-gray-900 hover:bg-white/20 rounded-full transition-all"
              onClick={toggleMenu}
              aria-label="Toggle navigation menu"
            >
              {isMenuOpen ? (
                <FiX className="h-6 w-6" />
              ) : (
                <FiMenu className="h-6 w-6" />
              )}
            </button>
          </div>

          <div className={`w-full md:flex-1 md:mx-8 md:max-w-xl ${isMenuOpen ? 'block' : 'hidden'} md:block mt-4 md:mt-0`}>
            <form onSubmit={handleSearch} className="relative">
              <input
                type="text"
                placeholder="Search products..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                ref={searchInputRef} // Attach the ref to the input
                className="w-full py-2.5 px-5 bg-white/15 text-gray-900 placeholder-gray-700 rounded-full border border-white/30 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-transparent backdrop-blur-md shadow-sm text-sm"
                aria-label="Search products"
              />
              <button
                type="submit"
                className="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-700 hover:text-gray-900"
                aria-label="Submit search"
              >
                <FiSearch className="h-5 w-5" />
              </button>
            </form>
          </div>

          <nav className={`w-full md:w-auto ${isMenuOpen ? 'block' : 'hidden'} md:flex items-center space-y-2 md:space-y-0 md:space-x-3 mt-4 md:mt-0`}>
            <Link
              href="/shop"
              className="flex items-center gap-2 w-full md:w-auto px-4 py-2 text-gray-900 hover:bg-white/20 rounded-full transition-all backdrop-blur-md border border-white/20 shadow-sm font-medium text-center"
              aria-label="Go to shop section"
            >
              <FaStore className="text-lg" />
              Shop Section
            </Link>

            {user && (
              <>
                <Link
                  href="/myaccount?tab=orders"
                  className="flex items-center gap-2 w-full md:w-auto px-4 py-2 text-gray-900 hover:bg-white/20 rounded-full transition-all backdrop-blur-md border border-white/20 shadow-sm font-medium text-center"
                  aria-label="View orders"
                >
                  <FaBoxOpen className="text-lg" />
                  MyOrders
                </Link>
                <Link
                  href="/myaccount?tab=bookservice"
                  className="flex items-center gap-2 w-full md:w-auto px-4 py-2 text-gray-900 hover:bg-white/20 rounded-full transition-all backdrop-blur-md border border-white/20 shadow-sm font-medium text-center"
                  aria-label="View orders"
                >
                  <FaHandshake  className="text-lg" />
                  Book Service
                </Link>
                <Link
                  href="/myaccount?tab=personal"
                  className="flex items-center gap-2 w-full md:w-auto px-4 py-2 text-gray-900 hover:bg-white/20 rounded-full transition-all backdrop-blur-md border border-white/20 shadow-sm font-medium text-center"
                  aria-label="Go to account settings"
                >
                  <FaUserCircle className="text-lg" />
                  MyAccount
                </Link>
              </>
            )}

            <button
              onClick={handleCartClick}
              className="relative block w-full md:w-auto px-4 py-2 text-gray-900 hover:bg-white/20 rounded-full transition-all backdrop-blur-md border border-white/20 shadow-sm text-center"
              aria-label={`Open cart with ${cartItemCount} items`}
            >
              <div className="flex items-center justify-center">
                <FiShoppingCart className="h-6 w-6" />
                {cartItemCount > 0 && (
                  <span
                    className="absolute -top-1 -right-1 bg-red-500/90 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium border border-white/30 shadow-sm"
                    aria-live="polite"
                  >
                    {cartItemCount}
                  </span>
                )}
              </div>
            </button>
          </nav>
        </div>
      </header>
      <CartModal />
    </>
  );
};

export default SearchHeader;
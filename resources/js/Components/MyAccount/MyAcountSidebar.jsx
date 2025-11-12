import React, { useState } from 'react';
import Swal from 'sweetalert2';
import { router } from '@inertiajs/react';
import { route } from 'ziggy-js';

const MyAccountSidebar = ({ activeMenu, onMenuChange }) => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  const handleLogout = () => {
    Swal.fire({
      title: 'Are you sure?',
      text: 'You will be logged out of your account!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, logout!',
    }).then((result) => {
      if (result.isConfirmed) {
        router.post(route('logout'));
      }
    });
  };

  const toggleMenu = () => {
    setIsMenuOpen(!isMenuOpen);
  };

  return (
    <>
      {/* Mobile Icon Bar */}
      <div className="lg:hidden fixed bottom-0 left-0 right-0 bg-white shadow-md p-4 flex justify-around items-center z-50">
        <button
          onClick={() => {
            onMenuChange('personal');
            setIsMenuOpen(false);
          }}
          className={`p-2 rounded-lg ${
            activeMenu === 'personal' ? 'bg-blue-100 text-blue-600' : 'text-gray-600'
          }`}
        >
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
        </button>
        <button
          onClick={() => {
            onMenuChange('orders');
            setIsMenuOpen(false);
          }}
          className={`p-2 rounded-lg ${
            activeMenu === 'orders' ? 'bg-blue-100 text-blue-600' : 'text-gray-600'
          }`}
        >
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
        </button>
        <button
          onClick={() => {
            onMenuChange('address');
            setIsMenuOpen(false);
          }}
          className={`p-2 rounded-lg ${
            activeMenu === 'address' ? 'bg-blue-100 text-blue-600' : 'text-gray-600'
          }`}
        >
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </button>
        <button
          onClick={() => {
            onMenuChange('alltest');
            setIsMenuOpen(false);
          }}
          className={`p-2 rounded-lg ${
            activeMenu === 'alltest' ? 'bg-blue-100 text-blue-600' : 'text-gray-600'
          }`}
        >
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </button>
        <button
          onClick={() => {
            onMenuChange('bookservice');
            setIsMenuOpen(false);
          }}
          className={`p-2 rounded-lg ${
            activeMenu === 'bookservice' ? 'bg-blue-100 text-blue-600' : 'text-gray-600'
          }`}
        >
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </button>
        <button
          onClick={() => {
            onMenuChange('bookedservi');
            setIsMenuOpen(false);
          }}
          className={`p-2 rounded-lg ${
            activeMenu === 'bookedservi' ? 'bg-blue-100 text-blue-600' : 'text-gray-600'
          }`}
        >
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </button>
        <button
          onClick={handleLogout}
          className="p-2 rounded-lg text-red-600"
        >
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 16l4-4m0 0l-4-4m4 4H7m5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h3a3 3 0 013 3v1" />
          </svg>
        </button>
      </div>

      {/* Full Screen Mobile Menu */}
      {isMenuOpen && (
        <div className="lg:hidden fixed inset-0 bg-white z-50 flex flex-col p-6">
          <div className="flex justify-between items-center mb-6">
            <h2 className="text-2xl font-bold text-gray-900">My Account</h2>
            <button onClick={toggleMenu} className="p-2">
              <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <nav className="space-y-2">
            <button
              onClick={() => {
                onMenuChange('personal');
                setIsMenuOpen(false);
              }}
              className={`w-full text-left px-4 py-2 rounded-lg text-lg ${
                activeMenu === 'personal' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'
              }`}
            >
              Personal Information
            </button>
            <button
              onClick={() => {
                onMenuChange('orders');
                setIsMenuOpen(false);
              }}
              className={`w-full text-left px-4 py-2 rounded-lg text-lg ${
                activeMenu === 'orders' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'
              }`}
            >
              My Orders
            </button>
            <button
              onClick={() => {
                onMenuChange('address');
                setIsMenuOpen(false);
              }}
              className={`w-full text-left px-4 py-2 rounded-lg text-lg ${
                activeMenu === 'address' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'
              }`}
            >
              My Address
            </button>

            <button
              onClick={() => {
                onMenuChange('bookservice');
                setIsMenuOpen(false);
              }}
              className={`w-full text-left px-4 py-2 rounded-lg text-lg ${
                activeMenu === 'bookservice' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'
              }`}
            >
              Book Service
            </button>
            <button
              onClick={() => {
                onMenuChange('bookedservi');
                setIsMenuOpen(false);
              }}
              className={`w-full text-left px-4 py-2 rounded-lg text-lg ${
                activeMenu === 'bookedservi' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'
              }`}
            >
             My Booked Services
            </button>
            <button
              onClick={handleLogout}
              className="w-full text-left px-4 py-2 rounded-lg text-lg text-red-600 hover:bg-red-100"
            >
              Logout
            </button>
          </nav>
        </div>
      )}

      {/* Desktop Sidebar */}
      <div className="hidden lg:block w-64 bg-white shadow-md h-screen p-6">
        <h2 className="text-2xl font-bold text-gray-900 mb-6">My Account</h2>
        <nav className="space-y-2">
          <button
            onClick={() => onMenuChange('personal')}
            className={`w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 ${
              activeMenu === 'personal' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'
            }`}
          >
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Personal Information
          </button>
          <button
            onClick={() => onMenuChange('orders')}
            className={`w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 ${
              activeMenu === 'orders' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'
            }`}
          >
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            My Orders
          </button>
          <button
            onClick={() => onMenuChange('address')}
            className={`w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 ${
              activeMenu === 'address' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'
            }`}
          >
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            My Address
          </button>

          <button
            onClick={() => onMenuChange('bookservice')}
            className={`w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 ${
              activeMenu === 'bookservice' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'
            }`}
          >
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Book Service
          </button>
           <button
            onClick={() => onMenuChange('bookedservi')}
            className={`w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 ${
              activeMenu === 'bookedservi' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'
            }`}
          >
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
           My Booked Service
          </button>         
          <button
            onClick={handleLogout}
            className="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 text-red-600 hover:bg-red-100"
          >
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 16l4-4m0 0l-4-4m4 4H7m5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h3a3 3 0 013 3v1" />
            </svg>
            Logout
          </button>
        </nav>
      </div>
    </>
  );
};

export default MyAccountSidebar;
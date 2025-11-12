import React, { useState, useEffect } from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { addToCart, removeFromCart, updateQuantity, setPaymentMethod, clearCart, setSelectedAddress } from '../../Redux/cartSlice';
import { closeCartModal, openLocationModal, setSelectedAddressId } from '../../Redux/modalSlice';
import { router, usePage } from '@inertiajs/react';
import SelectLocation from './SelectLocation';
import axios from 'axios';
import Swal from 'sweetalert2';

const CartModal = () => {
  const dispatch = useDispatch();
  const { isCartOpen, isLocationOpen, selectedAddressId } = useSelector((state) => state.modal);
  const { items: cartItems, selectedAddress, total_amount } = useSelector((state) => state.cart);
  const { addresses } = useSelector((state) => state.address); // Add addresses
  console.log('🛒 CartModal - Cart Items:', cartItems);
  console.log('🛒 CartModal - Selected Address:', selectedAddress);
  console.log('🛒 CartModal - Selected Address ID:', selectedAddressId);
  console.log('🛒 CartModal - Total Amount:', total_amount);

  const { auth } = usePage().props;
  const [showPaymentOptions, setShowPaymentOptions] = useState(false);

  // Sync selectedAddress with selectedAddressId
  useEffect(() => {
    if (selectedAddressId && !selectedAddress && addresses.length > 0) {
      const address = addresses.find((addr) => addr.id === parseInt(selectedAddressId));
      if (address) {
        console.log('🛒 Syncing selectedAddress with ID:', address);
        dispatch(setSelectedAddress(address));
      }
    }
  }, [selectedAddressId, selectedAddress, addresses, dispatch]);

  const handleOutsideClick = (e) => {
    if (e.target === e.currentTarget) {
      dispatch(closeCartModal());
      setShowPaymentOptions(false);
    }
  };

  const handleBuyNowClick = () => {
    if (!auth?.user) {
      dispatch(closeCartModal());
      router.visit('/login');
      return;
    }
    if (!selectedAddressId) {
      dispatch(openLocationModal());
    } else {
      setShowPaymentOptions((prev) => !prev);
    }
  };

const handlePaymentOptionClick = async (method) => {
  dispatch(setPaymentMethod(method));
  dispatch(closeCartModal());
  setShowPaymentOptions(false);

  if (!auth?.user) {
    router.visit('/login');
    return;
  }

  if (method === 'offline') {
    try {
        const response = await axios.post('/api/orders', {
          paymentMethod: method,
          total_amount,
          addressId: selectedAddressId,
          items: cartItems,
        });

      console.log('✅ Order Placed:', response.data);
      await Swal.fire({
        icon: 'success',
        title: 'Order Placed Successfully!',
        text: `Your order (${response.data.order.unique_order_id}) has been placed successfully.`,
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
      });

      dispatch(clearCart());
      router.visit('/myaccount?tab=orders', {
        method: 'GET',
      });
    } catch (error) {
      console.error('❌ Order Placement Failed:', error.response?.data?.error || error.message);
      Swal.fire({
        icon: 'error',
        title: 'Order Placement Failed',
        text: error.response?.data?.error || 'Something went wrong. Please try again.',
        confirmButtonText: 'OK',
        confirmButtonColor: '#d33',
      });
    }
  } else if (method === 'online') {
  try {
    const response = await axios.post('/api/create-order', {
      paymentMethod: method,
      total_amount,
      customer_mobile: selectedAddress.phone,
      addressId: selectedAddressId,
      items: cartItems,
    });

    console.log('✅ Order Placed:', response.data);

    if (response.data.success && response.data.payment_url) {
      // ✅ Redirect to UPI payment URL
      window.location.href = response.data.payment_url;
    } else {
      // 🔴 If payment_url is missing
      await Swal.fire({
        icon: 'error',
        title: 'Payment Error',
        text: response.data.message || 'Payment URL not received.',
        confirmButtonText: 'OK',
        confirmButtonColor: '#d33',
      });
    }
  } catch (error) {
    console.error('❌ Order Placement Failed:', error.response?.data?.error || error.message);
    Swal.fire({
      icon: 'error',
      title: 'Order Placement Failed',
      text: error.response?.data?.error || 'Something went wrong. Please try again.',
      confirmButtonText: 'OK',
      confirmButtonColor: '#d33',
    });
  }
}



};

  const handleChangeAddress = () => {
    dispatch(openLocationModal());
  };

  if (!isCartOpen) return null;

  return (
    <div
      className="fixed inset-0 z-50 flex justify-end"
      role="dialog"
      aria-modal="true"
      aria-labelledby="cart-modal-title"
      onClick={handleOutsideClick}
    >
      <div className="bg-white rounded-l-xl shadow-2xl p-6 relative max-h-[100vh] h-full w-full sm:w-[80%] md:w-[40%] lg:w-[25%] overflow-y-auto flex flex-col">
        <button
          onClick={() => {
            dispatch(closeCartModal());
            setShowPaymentOptions(false);
          }}
          className="absolute top-4 right-4 text-gray-600 hover:text-gray-900"
          aria-label="Close cart modal"
        >
          <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <h2 id="cart-modal-title" className="text-2xl font-bold text-gray-900 mb-6">Your Cart</h2>

        {cartItems.length === 0 ? (
          <p className="text-gray-600 flex-grow">Your cart is empty.</p>
        ) : (
          <div className="space-y-4 flex-grow overflow-y-auto">
            {cartItems.map((item) => (
              <CartItem key={`${item.id}-${item.addressId}`} item={item} dispatch={dispatch} />
            ))}
          </div>
        )}

        <div className="sticky bottom-0 bg-white pt-4 border-t">
          {selectedAddress && (
            <div className="mb-4 text-sm text-gray-600">
              <div className="flex items-center justify-between">
                <span className="font-medium">Deliver to:</span>
                <button
                  onClick={handleChangeAddress}
                  className="text-blue-600 hover:text-blue-800 font-medium text-sm"
                  aria-label="Change delivery address"
                >
                  Change
                </button>
              </div>
              <p className="font-medium">{selectedAddress.name}</p>
              <p>{selectedAddress.phone}</p>
              <p>
                {selectedAddress.area}, {selectedAddress.city}, {selectedAddress.district},{' '}
                {selectedAddress.pin_code || 'N/A'}
              </p>
              {selectedAddress.landmark && <p>Landmark: {selectedAddress.landmark}</p>}
            </div>
          )}
          <CartSummary totalPrice={total_amount} />
          {cartItems.length > 0 && (
            auth?.user ? (
              <>
                <BuyNowButton onClick={handleBuyNowClick} showPaymentOptions={showPaymentOptions} />
                {showPaymentOptions && selectedAddressId && (
                  <div className="mt-2 space-y-2">
                    <button
                      onClick={() => handlePaymentOptionClick('online')}
                      className="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-300"
                      aria-label="Pay online"
                    >
                      Pay Now
                    </button>
                    <button
                      onClick={() => handlePaymentOptionClick('offline')}
                      className="w-full px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors duration-300"
                      aria-label="Pay offline"
                    >
                      Cash On Delivery
                    </button>
                  </div>
                )}
              </>
            ) : (
              <LoginToOrderButton onClick={() => router.visit('/login')} />
            )
          )}
        </div>
      </div>
      {isLocationOpen && <SelectLocation />}
    </div>
  );
};

// ... (CartItem, CartSummary, BuyNowButton, LoginToOrderButton remain unchanged)

const CartItem = ({ item, dispatch }) => {
  return (
    <div className="flex items-center justify-between border-b pb-4">
      <div className="flex items-center space-x-4">
        <img src={item.image} alt={item.title} className="w-16 h-16 object-cover rounded-md" loading="lazy" />
        <div>
          <h3 className="text-lg font-medium text-gray-900">{item.title}</h3>
          <p className="text-sm text-gray-600">₹{item.price.toLocaleString('en-IN')}</p>
        </div>
      </div>
      <div className="flex items-center space-x-3">
        <button
          onClick={() =>
            dispatch(updateQuantity({ id: item.id, addressId: item.addressId, quantity: Math.max(1, item.quantity - 1) }))
          }
          className="px-2 py-1 bg-gray-200 rounded-full hover:bg-gray-300 disabled:opacity-50"
          disabled={item.quantity <= 1}
          aria-label={`Decrease quantity of ${item.title}`}
        >
          -
        </button>
        <span className="text-gray-900">{item.quantity}</span>
        <button
          onClick={() => dispatch(updateQuantity({ id: item.id, addressId: item.addressId, quantity: item.quantity + 1 }))}
          className="px-2 py-1 bg-gray-200 rounded-full hover:bg-gray-300"
          aria-label={`Increase quantity of ${item.title}`}
        >
          +
        </button>
        <button
          onClick={() => dispatch(removeFromCart({ id: item.id, addressId: item.addressId }))}
          className="text-red-600 hover:text-red-800"
          aria-label={`Remove ${item.title} from cart`}
        >
          <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth="2"
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
            />
          </svg>
        </button>
      </div>
    </div>
  );
};

const CartSummary = ({ totalPrice }) => (
  <div className="flex justify-between text-lg font-medium text-gray-900 mb-4">
    <span>Total:</span>
    <span>₹{totalPrice.toLocaleString('en-IN')}</span>
  </div>
);

const BuyNowButton = ({ onClick, showPaymentOptions }) => (
  <button
    onClick={onClick}
    className={`w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-300 ${
      showPaymentOptions ? 'transform -translate-y-2' : ''
    }`}
    aria-label="Proceed to select location or checkout"
  >
    Buy Now
  </button>
);

const LoginToOrderButton = ({ onClick }) => (
  <button
    onClick={onClick}
    className="w-full px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors duration-300"
    aria-label="Login to place order"
  >
    Login to Order
  </button>
);

export default CartModal;
import React, { useState } from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { closePaymentModal, openLocationModal } from '../../Redux/modalSlice';
import { router } from '@inertiajs/react';

const PaymentModal = () => {
  const dispatch = useDispatch();
  const { isPaymentOpen } = useSelector((state) => state.modal);
  const [paymentMethod, setPaymentMethod] = useState('cod'); // Default to Cash on Delivery
  const cartItems = useSelector((state) => state.cart.items);
  const selectedAddress = useSelector((state) => state.location.selectedAddress);
  const totalPrice = cartItems.reduce((total, item) => total + item.price * item.quantity, 0);

  const handleOutsideClick = (e) => {
    if (e.target === e.currentTarget) {
      dispatch(closePaymentModal());
    }
  };

  const handleConfirmOrder = () => {
    if (!selectedAddress) {
      dispatch(closePaymentModal());
      dispatch(openLocationModal());
      return;
    }
    dispatch(closePaymentModal());
    router.post('/order/confirm', {
      paymentMethod,
      cartItems,
      address: selectedAddress,
      totalPrice,
    });
  };

  if (!isPaymentOpen) return null;

  return (
    <div
      className="fixed inset-0 z-50 flex justify-end"
      role="dialog"
      aria-modal="true"
      aria-labelledby="payment-modal-title"
      onClick={handleOutsideClick}
    >
      <div className="bg-white rounded-l-xl shadow-2xl p-6 relative max-h-[100vh] h-full w-full sm:w-[80%] md:w-[40%] lg:w-[25%] overflow-y-auto flex flex-col">
        <button
          onClick={() => dispatch(closePaymentModal())}
          className="absolute top-4 right-4 text-gray-600 hover:text-gray-900"
          aria-label="Close payment modal"
        >
          <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <h2 id="payment-modal-title" className="text-2xl font-bold text-gray-900 mb-6">Select Payment Method</h2>

        <div className="space-y-4 flex-grow">
          <div className="flex items-center">
            <input
              type="radio"
              id="cod"
              name="paymentMethod"
              value="cod"
              checked={paymentMethod === 'cod'}
              onChange={() => setPaymentMethod('cod')}
              className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
              aria-label="Cash on Delivery"
            />
            <label htmlFor="cod" className="ml-3 text-sm font-medium text-gray-900">
              Cash on Delivery
            </label>
          </div>
          <div className="flex items-center">
            <input
              type="radio"
              id="online"
              name="paymentMethod"
              value="online"
              checked={paymentMethod === 'online'}
              onChange={() => setPaymentMethod('online')}
              className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
              aria-label="Online Payment"
            />
            <label htmlFor="online" className="ml-3 text-sm font-medium text-gray-900">
              Online Payment
            </label>
          </div>
        </div>

        <div className="sticky bottom-0 bg-white pt-4 border-t">
          <div className="flex justify-between text-lg font-medium text-gray-900 mb-4">
            <span>Total:</span>
            <span>₹{totalPrice.toLocaleString('en-IN')}</span>
          </div>
          <button
            onClick={handleConfirmOrder}
            className="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-300"
            aria-label="Confirm order"
          >
            Confirm Order
          </button>
        </div>
      </div>
    </div>
  );
};

export default PaymentModal;
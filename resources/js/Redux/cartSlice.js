import { createSlice } from '@reduxjs/toolkit';
import Cookies from 'js-cookie';

const cartSlice = createSlice({
  name: 'cart',
  initialState: {
    items: JSON.parse(Cookies.get('cartItems') || '[]'),
    selectedAddress: JSON.parse(Cookies.get('selectedCartAddress') || 'null'),
    total_amount: parseFloat(Cookies.get('totalAmount') || '0'),
    payment_method: Cookies.get('paymentMethod') || null,
    error: null,
  },
  reducers: {
    addToCart: (state, action) => {
      const { id, addressId, ...itemData } = action.payload;
      const existingItem = state.items.find(
        (item) => item.id === id && item.addressId === addressId
      );

      if (existingItem) {
        existingItem.quantity += 1;
      } else {
        state.items.push({ id, addressId, ...itemData, quantity: 1 });
      }

      state.error = null;
      state.total_amount = state.items.reduce(
        (total, item) => total + item.price * item.quantity,
        0
      );
      Cookies.set('cartItems', JSON.stringify(state.items), { expires: 7 });
      Cookies.set('totalAmount', state.total_amount.toString(), { expires: 7 });
      console.log('🛒 Added/Updated Cart Item:', {
        cartItems: state.items,
        selectedAddress: state.selectedAddress,
        totalAmount: state.total_amount,
        paymentMethod: state.payment_method,
      });
    },

    removeFromCart: (state, action) => {
      const { id, addressId } = action.payload;
      state.items = state.items.filter(
        (item) => !(item.id === id && item.addressId === addressId)
      );
      state.error = null;
      state.total_amount = state.items.reduce(
        (total, item) => total + item.price * item.quantity,
        0
      );
      Cookies.set('cartItems', JSON.stringify(state.items), { expires: 7 });
      Cookies.set('totalAmount', state.total_amount.toString(), { expires: 7 });
      console.log('🛒 Removed Cart Item:', {
        cartItems: state.items,
        selectedAddress: state.selectedAddress,
        totalAmount: state.total_amount,
        paymentMethod: state.payment_method,
      });
    },

    updateQuantity: (state, action) => {
      const { id, addressId, quantity } = action.payload;
      const item = state.items.find(
        (item) => item.id === id && item.addressId === addressId
      );

      if (item && quantity > 0) {
        item.quantity = Math.min(quantity, 100);
        state.error = null;
      } else if (item && quantity === 0) {
        state.items = state.items.filter(
          (i) => !(i.id === id && i.addressId === addressId)
        );
        state.error = null;
      } else {
        state.error = 'Invalid quantity';
      }

      state.total_amount = state.items.reduce(
        (total, item) => total + item.price * item.quantity,
        0
      );
      Cookies.set('cartItems', JSON.stringify(state.items), { expires: 7 });
      Cookies.set('totalAmount', state.total_amount.toString(), { expires: 7 });
      console.log('🛒 Updated Cart Quantity:', {
        cartItems: state.items,
        selectedAddress: state.selectedAddress,
        totalAmount: state.total_amount,
        paymentMethod: state.payment_method,
      });
    },

    setSelectedAddress: (state, action) => {
      state.selectedAddress = action.payload;
      Cookies.set('selectedCartAddress', JSON.stringify(action.payload), {
        expires: 7,
      });
      console.log('🛒 Set Selected Address:', {
        selectedAddress: state.selectedAddress,
        cartItems: state.items,
        totalAmount: state.total_amount,
        paymentMethod: state.payment_method,
      });
    },

    clearSelectedAddress: (state) => {
      state.selectedAddress = null;
      Cookies.remove('selectedCartAddress');
      console.log('🛒 Cleared Selected Address:', {
        selectedAddress: state.selectedAddress,
        cartItems: state.items,
        totalAmount: state.total_amount,
        paymentMethod: state.payment_method,
      });
    },

    setPaymentMethod: (state, action) => {
      state.payment_method = action.payload;
      Cookies.set('paymentMethod', action.payload, { expires: 7 });
      console.log('💳 Set Payment Method:', {
        paymentMethod: state.payment_method,
        cartItems: state.items,
        selectedAddress: state.selectedAddress,
        totalAmount: state.total_amount,
      });
    },

    clearCart: (state) => {
      state.items = [];
      state.total_amount = 0;
      state.payment_method = null;
      // Preserve selectedAddress and its cookie
      Cookies.set('cartItems', JSON.stringify([]), { expires: 7 });
      Cookies.set('totalAmount', '0', { expires: 7 });
      Cookies.remove('paymentMethod');
      console.log('🛒 Cart Cleared:', {
        cartItems: state.items,
        selectedAddress: state.selectedAddress,
        totalAmount: state.total_amount,
        paymentMethod: state.payment_method,
      });
    },
  },
});

export const {
  addToCart,
  removeFromCart,
  updateQuantity,
  setSelectedAddress,
  clearSelectedAddress,
  setPaymentMethod,
  clearCart,
} = cartSlice.actions;
export default cartSlice.reducer;
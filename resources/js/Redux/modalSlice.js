import { createSlice } from '@reduxjs/toolkit';
import Cookies from 'js-cookie';

const modalSlice = createSlice({
  name: 'modal',
  initialState: {
    isCartOpen: Cookies.get('isCartOpen') === 'true' || false,
    isLocationOpen: Cookies.get('isLocationOpen') === 'true' || false,
    isPaymentOpen: Cookies.get('isPaymentOpen') === 'true' || false,
    modalType: Cookies.get('modalType') || 'normal',
    selectedAddressId: Cookies.get('selectedAddressId') || null, // New field for address ID
  },
  reducers: {
    openCartModal: (state, action) => {
      state.isCartOpen = true;
      state.modalType = action.payload?.modalType || 'normal';
      Cookies.set('isCartOpen', 'true', { expires: 7 });
      Cookies.set('modalType', state.modalType, { expires: 7 });
    },
    closeCartModal: (state) => {
      state.isCartOpen = false;
      state.modalType = 'normal';
      Cookies.set('isCartOpen', 'false', { expires: 7 });
      Cookies.set('modalType', 'normal', { expires: 7 });
    },
    openLocationModal: (state) => {
      state.isLocationOpen = true;
      Cookies.set('isLocationOpen', 'true', { expires: 7 });
    },
    closeLocationModal: (state) => {
      state.isLocationOpen = false;
      Cookies.set('isLocationOpen', 'false', { expires: 7 });
    },
    openPaymentModal: (state) => {
      state.isPaymentOpen = true;
      Cookies.set('isPaymentOpen', 'true', { expires: 7 });
    },
    closePaymentModal: (state) => {
      state.isPaymentOpen = false;
      Cookies.set('isPaymentOpen', 'false', { expires: 7 });
    },
    setSelectedAddressId: (state, action) => {
      state.selectedAddressId = action.payload;
      Cookies.set('selectedAddressId', action.payload, { expires: 7 });
    },
    clearSelectedAddressId: (state) => {
      state.selectedAddressId = null;
      Cookies.remove('selectedAddressId');
    },
  },
});

export const {
  openCartModal,
  closeCartModal,
  openLocationModal,
  closeLocationModal,
  openPaymentModal,
  closePaymentModal,
  setSelectedAddressId,
  clearSelectedAddressId,
} = modalSlice.actions;
export default modalSlice.reducer;
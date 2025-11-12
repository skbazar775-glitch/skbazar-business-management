import { configureStore } from '@reduxjs/toolkit';
import cartReducer from './cartSlice';
import modalReducer from './modalSlice';
import locationReducer from './locationSlice';
import productReducer from './productSlice';
import authReducer from './authSlice';
import addAddressReducer from './addAddressSlice'; // 🆕

export const store = configureStore({
  reducer: {
    cart: cartReducer,
    modal: modalReducer,
    location: locationReducer,
    product: productReducer,
    auth: authReducer,
    address: addAddressReducer, // 🆕
  },
});

// locationSlice.js
import { createSlice } from '@reduxjs/toolkit';

const locationSlice = createSlice({
  name: 'location',
  initialState: {
    selectedAddress: null,
  },
  reducers: {
    setSelectedAddress: (state, action) => {
      state.selectedAddress = action.payload;
    },
    resetLocation: (state) => {
      state.selectedAddress = null;
    },
  },
});

export const { setSelectedAddress, resetLocation } = locationSlice.actions;

export default locationSlice.reducer;

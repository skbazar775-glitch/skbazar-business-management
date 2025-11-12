import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';
import axios from 'axios';

// Add Address
export const addAddress = createAsyncThunk(
  'address/addAddress',
  async (addressData, { rejectWithValue }) => {
    try {
      const response = await axios.post('/api/add-address', addressData);
      return response.data.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || 'Address submission failed');
    }
  }
);

// Fetch All Addresses
export const fetchAddresses = createAsyncThunk(
  'address/fetchAddresses',
  async (_, { rejectWithValue }) => {
    try {
      const response = await axios.get('/api/addresses');
      return response.data.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || 'Fetching addresses failed');
    }
  }
);

// Update Address
export const updateAddress = createAsyncThunk(
  'address/updateAddress',
  async ({ id, updatedData }, { rejectWithValue }) => {
    try {
      const response = await axios.put(`/api/update-address/${id}`, updatedData);
      return response.data.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || 'Update failed');
    }
  }
);

// Delete Address
export const deleteAddress = createAsyncThunk(
  'address/deleteAddress',
  async (id, { rejectWithValue }) => {
    try {
      await axios.delete(`/api/delete-address/${id}`);
      return id;
    } catch (error) {
      return rejectWithValue(error.response?.data || 'Delete failed');
    }
  }
);
// addAddressSlice.js
const addAddressSlice = createSlice({
  name: 'address',
  initialState: {
    loading: false,
    success: false,
    error: null,
    address: null,
    addresses: [],
  },
  reducers: {
    resetAddressState: (state) => {
      state.loading = false;
      state.success = false;
      state.error = null;
      state.address = null;
    },
  },
  extraReducers: (builder) => {
    builder
      // Add
      .addCase(addAddress.pending, (state) => {
        state.loading = true;
        state.success = false;
        state.error = null;
      })
      .addCase(addAddress.fulfilled, (state, action) => {
        state.loading = false;
        state.success = true;
        state.address = action.payload;
        state.addresses.unshift(action.payload);
      })
      .addCase(addAddress.rejected, (state, action) => {
        state.loading = false;
        state.success = false;
        state.error = action.payload;
      })

      // Fetch
      .addCase(fetchAddresses.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchAddresses.fulfilled, (state, action) => {
        state.loading = false;
        state.addresses = action.payload;
        // Do not set success to true for fetchAddresses
      })
      .addCase(fetchAddresses.rejected, (state, action) => {
        state.loading = false;
        state.error = action.payload;
      })

      // Update
      .addCase(updateAddress.pending, (state) => {
        state.loading = true;
        state.success = false;
        state.error = null;
      })
      .addCase(updateAddress.fulfilled, (state, action) => {
        state.loading = false;
        state.success = true;
        const index = state.addresses.findIndex((addr) => addr.id === action.payload.id);
        if (index !== -1) {
          state.addresses[index] = action.payload;
        }
      })
      .addCase(updateAddress.rejected, (state, action) => {
        state.loading = false;
        state.success = false;
        state.error = action.payload;
      })

      // Delete
      .addCase(deleteAddress.pending, (state) => {
        state.loading = true;
        state.success = false;
        state.error = null;
      })
      .addCase(deleteAddress.fulfilled, (state, action) => {
        state.loading = false;
        state.success = true;
        state.addresses = state.addresses.filter((addr) => addr.id !== action.payload);
      })
      .addCase(deleteAddress.rejected, (state, action) => {
        state.loading = false;
        state.success = false;
        state.error = action.payload;
      });
  },
});

export const { resetAddressState } = addAddressSlice.actions;
export default addAddressSlice.reducer;
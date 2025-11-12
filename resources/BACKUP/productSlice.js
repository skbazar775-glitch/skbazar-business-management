import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';
import axios from 'axios';

// Async thunk to fetch all products
export const fetchProducts = createAsyncThunk(
  'product/fetchProducts',
  async (_, { rejectWithValue }) => {
    try {
      const response = await axios.get(`https://skbazar.in/api/products`);
      const apiProducts = response.data.data;

      if (!apiProducts || !Array.isArray(apiProducts)) {
        console.error('API response does not contain valid product data:', response.data);
        return rejectWithValue('No products found');
      }

      return apiProducts
        .filter((apiProduct) => apiProduct.status !== 1)
        .map((apiProduct) => ({
          id: apiProduct.id,
          category_id: apiProduct.category_id, // Add category_id
          title: apiProduct.name || 'Unknown Product',
          sellinQUNATITY: apiProduct.sellin_quantity || 0,
          sellinQUNATITYunit: apiProduct.sellin_quantity_unit || 'Unit',
          price: apiProduct.price_s || 0,
          oldPrice: apiProduct.price_e || null,
          inStock: apiProduct.status !== 2,
          description: apiProduct.description || '',
          image: apiProduct.image
            ? `https://skbazar.in/storage/products/${apiProduct.image}`
            : 'https://via.placeholder.com/500',
          alt: apiProduct.name || 'Product Image',
          status_text: apiProduct.status_text || 'Unknown',
          features: [],
        }));
    } catch (error) {
      console.error('Error fetching products:', error);
      return rejectWithValue(error.response?.data?.message || 'Failed to fetch products');
    }
  }
);

// Async thunk to fetch categories
export const fetchCategories = createAsyncThunk(
  'product/fetchCategories',
  async (_, { rejectWithValue }) => {
    try {
      const response = await axios.get(`https://skbazar.in/api/categories`);
      const apiCategories = response.data.data;

      if (!apiCategories || !Array.isArray(apiCategories)) {
        console.error('API response does not contain valid category data:', response.data);
        return rejectWithValue('No categories found');
      }

      return apiCategories.map((category) => ({
        id: category.id,
        title: category.title,
        description: category.description || '',
        image: category.image
          ? `https://skbazar.in/category/${category.image}`
          : 'https://via.placeholder.com/150',
        slug: category.slug,
      }));
    } catch (error) {
      console.error('Error fetching categories:', error);
      return rejectWithValue(error.response?.data?.message || 'Failed to fetch categories');
    }
  }
);

const productSlice = createSlice({
  name: 'product',
  initialState: {
    products: [],
    categories: [], // Add categories to state
    selectedCategory: null, // Store selected category ID
    loading: false,
    error: null,
  },
  reducers: {
    clearProducts: (state) => {
      state.products = [];
      state.error = null;
      state.loading = false;
    },
    setSelectedCategory: (state, action) => {
      state.selectedCategory = action.payload;
    },
  },
  extraReducers: (builder) => {
    builder
      // Fetch Products
      .addCase(fetchProducts.pending, (state) => {
        state.loading = true;
        state.error = null;
        state.products = [];
      })
      .addCase(fetchProducts.fulfilled, (state, action) => {
        state.loading = false;
        state.products = action.payload;
      })
      .addCase(fetchProducts.rejected, (state, action) => {
        state.loading = false;
        state.error = action.payload;
      })
      // Fetch Categories
      .addCase(fetchCategories.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchCategories.fulfilled, (state, action) => {
        state.loading = false;
        state.categories = action.payload;
      })
      .addCase(fetchCategories.rejected, (state, action) => {
        state.loading = false;
        state.error = action.payload;
      });
  },
});

export const { clearProducts, setSelectedCategory } = productSlice.actions;
export default productSlice.reducer;
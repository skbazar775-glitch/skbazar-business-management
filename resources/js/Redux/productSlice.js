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
          category_id: apiProduct.category_id,
          title: apiProduct.name || 'Unknown Product',
          sellin_quantity: apiProduct.sellin_quantity || 0, // Fixed typo
          sellin_quantity_unit: apiProduct.sellin_quantity_unit || 'Unit', // Fixed typo
          price: apiProduct.price_s || 0,
          oldPrice: apiProduct.price_e || null,
          inStock: apiProduct.status !== 2,
          description: apiProduct.description || '',
          image: apiProduct.image
            ? `https://skbazar.in/uploaded/products/${apiProduct.image}`
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

// Async thunk to search products
export const searchProducts = createAsyncThunk(
  'product/searchProducts',
  async (query, { rejectWithValue }) => {
    try {
      const response = await axios.get(`https://skbazar.in/api/search`, {
        params: { q: query },
      });
      const { products, related_products } = response.data.data;

      if (!products || !Array.isArray(products)) {
        console.error('API response does not contain valid search data:', response.data);
        return rejectWithValue('No products found');
      }

      const mapProducts = (apiProducts) =>
        apiProducts.map((apiProduct) => ({
          id: apiProduct.id,
          category_id: apiProduct.category.id,
          title: apiProduct.name || 'Unknown Product',
          sellin_quantity: apiProduct.sellin_quantity || 0,
          sellin_quantity_unit: apiProduct.sellin_quantity_unit || 'Unit',
          price: apiProduct.price_s || 0,
          oldPrice: apiProduct.price_e || null,
          inStock: apiProduct.status !== 2,
          description: apiProduct.description || '',
          image: apiProduct.image
            ? `https://skbazar.in/storage/products/${apiProduct.image}`
            : 'https://via.placeholder.com/500',
          alt: apiProduct.name || 'Product Image',
          status_text: apiProduct.status || 'Unknown',
          category: {
            id: apiProduct.category.id,
            title: apiProduct.category.title,
            slug: apiProduct.category.slug,
          },
          features: [],
        }));

      return {
        searchResults: mapProducts(products),
        relatedProducts: mapProducts(related_products),
      };
    } catch (error) {
      console.error('Error searching products:', error);
      return rejectWithValue(error.response?.data?.message || 'Failed to search products');
    }
  }
);

const productSlice = createSlice({
  name: 'product',
  initialState: {
    products: [],
    categories: [],
    selectedCategory: null,
    searchResults: [],
    relatedProducts: [],
    loading: false,
    error: null,
  },
  reducers: {
    clearProducts: (state) => {
      state.products = [];
      state.searchResults = [];
      state.relatedProducts = [];
      state.error = null;
      state.loading = false;
    },
    setSelectedCategory: (state, action) => {
      state.selectedCategory = action.payload;
    },
  },
  extraReducers: (builder) => {
    builder
      .addCase(fetchProducts.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchProducts.fulfilled, (state, action) => {
        state.loading = false;
        state.products = action.payload;
      })
      .addCase(fetchProducts.rejected, (state, action) => {
        state.loading = false;
        state.error = action.payload;
      })
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
      })
      .addCase(searchProducts.pending, (state) => {
        state.loading = true;
        state.error = null;
        state.searchResults = [];
        state.relatedProducts = [];
      })
      .addCase(searchProducts.fulfilled, (state, action) => {
        state.loading = false;
        state.searchResults = action.payload.searchResults;
        state.relatedProducts = action.payload.relatedProducts;
      })
      .addCase(searchProducts.rejected, (state, action) => {
        state.loading = false;
        state.error = action.payload;
      });
  },
});

export const { clearProducts, setSelectedCategory } = productSlice.actions;
export default productSlice.reducer;
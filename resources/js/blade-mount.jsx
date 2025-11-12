import React from 'react';
import { createRoot } from 'react-dom/client';
import ProductsSection from './Components/ProductsSection';
// Mounting inside blade
const el = document.getElementById('products-section');
if (el) {
  createRoot(el).render(<ProductsSection />);
}

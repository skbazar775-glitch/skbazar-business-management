import React from 'react';
import Header from '@/Components/Ecoms/Header';
import Products from '@/Components/Ecoms/Products';
const Shop = () => {
  return (
    <div className="flex flex-col min-h-screen bg-gray-900">
      <Header />
      <main className="flex-grow">
        <Products />
      </main>
    </div>
  );
};

export default Shop;
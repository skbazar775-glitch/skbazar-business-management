import React from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { setSelectedCategory } from '../../Redux/productSlice';

const Category = () => {
  const dispatch = useDispatch();
  const { categories, selectedCategory } = useSelector((state) => state.product);

  const handleCategoryClick = (categoryId) => {
    dispatch(setSelectedCategory(categoryId));
  };

  return (
    <div className="w-full bg-white p-4 rounded-xl shadow-md mb-6">
      <h2 className="text-xl font-bold text-gray-900 mb-4 hidden lg:block">Categories</h2>
      
      {/* Horizontal Scroll for Mobile */}
      <div className="lg:hidden overflow-x-auto pb-2 mt-[20px]">
        <div className="flex space-x-2">
          {/* All Products Button */}
          <button
            onClick={() => handleCategoryClick(null)}
            className={`flex-shrink-0 px-4 py-2 rounded-lg flex items-center gap-2 ${
              selectedCategory === null
                ? 'bg-blue-600 text-white'
                : 'bg-gray-100 text-gray-900 hover:bg-gray-200'
            }`}
            aria-label="Show all products"
          >
            <div className="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                className="h-4 w-4 text-gray-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2}
                  d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                />
              </svg>
            </div>
            <span>All</span>
          </button>
          
          {/* Category Buttons */}
          {categories.map((category) => (
            <button
              key={category.id}
              onClick={() => handleCategoryClick(category.id)}
              className={`flex-shrink-0 px-4 py-2 rounded-lg flex items-center gap-2 ${
                selectedCategory === category.id
                  ? 'bg-blue-600 text-white'
                  : 'bg-gray-100 text-gray-900 hover:bg-gray-200'
              }`}
              aria-label={`Filter by ${category.title}`}
            >
              <img
                src={category.image || 'https://via.placeholder.com/40'}
                alt={category.title}
                className="w-6 h-6 rounded-full object-cover"
              />
              <span>{category.title}</span>
            </button>
          ))}
        </div>
      </div>
      
      {/* Vertical List for Desktop */}
      <ul className="hidden lg:block space-y-2">
        <li>
          <button
            onClick={() => handleCategoryClick(null)}
            className={`w-full text-left px-4 py-2 rounded-lg ${
              selectedCategory === null
                ? 'bg-blue-600 text-white'
                : 'bg-gray-100 text-gray-900 hover:bg-gray-200'
            }`}
            aria-label="Show all products"
          >
            <div className="flex items-center gap-3">
              <div className="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  className="h-5 w-5 text-gray-600"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                  />
                </svg>
              </div>
              <span>All Products</span>
            </div>
          </button>
        </li>
        {categories.map((category) => (
          <li key={category.id}>
            <button
              onClick={() => handleCategoryClick(category.id)}
              className={`w-full text-left px-4 py-2 rounded-lg flex items-center gap-3 ${
                selectedCategory === category.id
                  ? 'bg-blue-600 text-white'
                  : 'bg-gray-100 text-gray-900 hover:bg-gray-200'
              }`}
              aria-label={`Filter by ${category.title}`}
            >
              <img
                src={category.image || 'https://via.placeholder.com/40'}
                alt={category.title}
                className="w-8 h-8 rounded-full object-cover"
              />
              <span>{category.title}</span>
            </button>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default Category;
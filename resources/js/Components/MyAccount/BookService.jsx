import React, { useState, useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import axios from "axios";
import { fetchAddresses, resetAddressState } from "../../Redux/addAddressSlice";
import { route } from "ziggy-js";
import Swal from 'sweetalert2';

const BookService = () => {
  // State for categories, services, and booking form
  const [categories, setCategories] = useState([]);
  const [services, setServices] = useState([]);
  const [selectedCategory, setSelectedCategory] = useState("");
  const [selectedService, setSelectedService] = useState(null);
  const [bookingForm, setBookingForm] = useState({
    address_id: "",
    date: "",
  });
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);
  const [showBookingForm, setShowBookingForm] = useState(false);
  const [isLoading, setIsLoading] = useState(false);

  // Redux state for addresses
  const dispatch = useDispatch();
  const { addresses, loading, error: addressError } = useSelector((state) => state.address);

  // Fetch categories and services on mount and when category changes
  useEffect(() => {
    const fetchCategoriesAndServices = async () => {
      try {
        setIsLoading(true);
        setError(null);
        const response = await axios.get(route("api.services.index"), {
          params: { category_id: selectedCategory },
        });
        setCategories(response.data.data.categories);
        setServices(response.data.data.services);
      } catch (err) {
        setError(err.response?.data?.message || "Failed to fetch services.");
      } finally {
        setIsLoading(false);
      }
    };
    fetchCategoriesAndServices();
  }, [selectedCategory]);

  // Fetch addresses on mount
  useEffect(() => {
    dispatch(fetchAddresses());
    return () => {
      dispatch(resetAddressState());
    };
  }, [dispatch]);

  // Handle category selection
  const handleCategoryChange = (e) => {
    setSelectedCategory(e.target.value);
    setSelectedService(null);
    setShowBookingForm(false);
  };

  // Open booking form for a selected service
  const openBookingForm = (service) => {
    setSelectedService(service);
    setShowBookingForm(true);
    setBookingForm({ address_id: "", date: "" });
    setError(null);
    setSuccess(null);
  };

  // Handle form input changes
  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setBookingForm((prev) => ({ ...prev, [name]: value }));
  };

 // Handle booking submission - UPDATED WITH SWEETALERT
  const bookService = async (e) => {
    e.preventDefault();
    try {
      setError(null);
      setSuccess(null);
      setIsLoading(true);

      // Validate form
      if (!bookingForm.address_id || !bookingForm.date) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Please select an address and date.',
          confirmButtonColor: '#3085d6',
        });
        return;
      }

      const response = await axios.post(route("api.services.book"), {
        service_id: selectedService.id,
        address_id: bookingForm.address_id,
        date: bookingForm.date,
      });

      // Show success alert
      await Swal.fire({
        icon: 'success',
        title: 'Booking Confirmed!',
        text: response.data.message,
        showConfirmButton: true,
        confirmButtonColor: '#3085d6',
        timer: 3000
      });

      setShowBookingForm(false);
      setSuccess(response.data.message);
      
      // Optional: Refresh services after successful booking
      const refreshResponse = await axios.get(route("api.services.index"), {
        params: { category_id: selectedCategory },
      });
      setServices(refreshResponse.data.data.services);

    } catch (err) {
      // Show error alert
      await Swal.fire({
        icon: 'error',
        title: 'Booking Failed',
        text: err.response?.data?.message || "Failed to book service.",
        confirmButtonColor: '#3085d6',
      });
      setError(err.response?.data?.message || "Failed to book service.");
    } finally {
      setIsLoading(false);
    }
  };
  // Format date input to ensure compatibility
  const minDateTime = new Date().toISOString().slice(0, 16);

  return (
    <div className="container mx-auto px-4 py-8 max-w-7xl">
      <div className="text-center mb-10">
        <h1 className="text-3xl font-bold text-gray-800 mb-2">Book a Professional Service</h1>
        <p className="text-gray-600">Choose from our wide range of services and book at your convenience</p>
      </div>

      {/* Status Messages */}
      <div className="max-w-2xl mx-auto">
        {error && (
          <div className="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r">
            <div className="flex items-center">
              <div className="flex-shrink-0">
                <svg className="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                </svg>
              </div>
              <div className="ml-3">
                <p className="text-sm text-red-700">{error}</p>
              </div>
            </div>
          </div>
        )}

        {success && (
          <div className="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r">
            <div className="flex items-center">
              <div className="flex-shrink-0">
                <svg className="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                </svg>
              </div>
              <div className="ml-3">
                <p className="text-sm text-green-700">{success}</p>
              </div>
            </div>
          </div>
        )}
      </div>

      {/* Category Selection */}
      <div className="max-w-2xl mx-auto mb-10">
        <label htmlFor="category" className="block text-sm font-medium text-gray-700 mb-2">
          Filter by Category
        </label>
        <div className="relative">
          <select
            id="category"
            value={selectedCategory}
            onChange={handleCategoryChange}
            className="block w-full pl-4 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-lg shadow-sm appearance-none"
          >
            <option value="">All Categories</option>
            {categories.map((category) => (
              <option key={category.id} value={category.id}>
                {category.title}
              </option>
            ))}
          </select>
          <div className="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <svg className="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fillRule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clipRule="evenodd" />
            </svg>
          </div>
        </div>
      </div>

      {/* Services List */}
      {isLoading ? (
        <div className="flex justify-center items-center py-20">
          <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
        </div>
      ) : services.length > 0 ? (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          {services.map((service) => (
            <div key={service.id} className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
              <div className="p-6">
                <div className="flex items-center mb-4">
                  <div className="bg-blue-100 p-3 rounded-full mr-4">
                    <svg className="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                  </div>
                  <h3 className="text-lg font-semibold text-gray-800">{service.name}</h3>
                </div>
                <div className="space-y-2">
                  <p className="text-sm text-gray-600 flex items-center">
                    <svg className="h-4 w-4 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Category: {service.category?.title || "N/A"}
                  </p>
                  <p className="text-sm text-gray-600 flex items-center">
                    <svg className="h-4 w-4 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Price: ₹{service.price}
                  </p>
                </div>
                <button
                  onClick={() => openBookingForm(service)}
                  className="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-300 flex items-center justify-center"
                >
                  <svg className="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                  Book Now
                </button>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <div className="text-center py-12">
          <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <h3 className="mt-2 text-lg font-medium text-gray-900">No services found</h3>
          <p className="mt-1 text-sm text-gray-500">
            {selectedCategory ? "No services available for the selected category." : "No services available at the moment."}
          </p>
        </div>
      )}

      {/* Booking Form Modal */}
      {showBookingForm && (
        <div className="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 z-50">
          <div className="bg-white rounded-lg shadow-xl overflow-hidden w-full max-w-md">
            <div className="bg-blue-600 px-6 py-4">
              <h2 className="text-lg font-medium text-white">Book {selectedService?.name}</h2>
            </div>
            <form onSubmit={bookService} className="p-6">
              <div className="mb-6">
                <label htmlFor="address_id" className="block text-sm font-medium text-gray-700 mb-2">
                  Select Address
                </label>
                <select
                  id="address_id"
                  name="address_id"
                  value={bookingForm.address_id}
                  onChange={handleInputChange}
                  className="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md shadow-sm"
                  required
                >
                  <option value="">Select Address</option>
                  {addresses.map((address) => (
                    <option key={address.id} value={address.id}>
                      {address.street}, {address.city}, {address.state} {address.zip}
                    </option>
                  ))}
                </select>
                {addressError && (
                  <p className="mt-2 text-sm text-red-600">{addressError}</p>
                )}
                {loading && (
                  <div className="mt-2 flex items-center text-sm text-gray-500">
                    <svg className="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                      <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Loading addresses...
                  </div>
                )}
              </div>
              <div className="mb-6">
                <label htmlFor="date" className="block text-sm font-medium text-gray-700 mb-2">
                  Select Date & Time
                </label>
                <input
                  id="date"
                  name="date"
                  type="datetime-local"
                  value={bookingForm.date}
                  onChange={handleInputChange}
                  min={minDateTime}
                  className="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md shadow-sm"
                  required
                />
              </div>
              <div className="flex justify-end space-x-3">
                <button
                  type="button"
                  onClick={() => setShowBookingForm(false)}
                  className="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 flex items-center"
                  disabled={isLoading}
                >
                  {isLoading ? (
                    <>
                      <svg className="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      Processing...
                    </>
                  ) : (
                    "Confirm Booking"
                  )}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default BookService;
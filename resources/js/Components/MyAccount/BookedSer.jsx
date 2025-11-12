import React, { useState, useEffect } from "react";
import axios from "axios";
import { route } from "ziggy-js";
import Swal from 'sweetalert2';

const BookedSer = () => {
  const [bookings, setBookings] = useState([]);
  const [loading, setLoading] = useState(false);

  // Fetch bookings on component mount
  useEffect(() => {
    const fetchBookings = async () => {
      try {
        setLoading(true);
        const response = await axios.get(route("api.services.bookings"));
        setBookings(response.data.data.bookings);
      } catch (err) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: err.response?.data?.message || "Failed to fetch bookings.",
          confirmButtonColor: '#3085d6',
        });
      } finally {
        setLoading(false);
      }
    };
    fetchBookings();
  }, []);

  // Helper to format date
  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString("en-US", {
      weekday: 'short',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  };

  // Helper to get status text and styling
  const getStatusBadge = (status) => {
    const statusConfig = {
      0: { text: "Pending", color: "bg-yellow-100 text-yellow-800" },
      1: { text: "Confirmed", color: "bg-blue-100 text-blue-800" },
      2: { text: "Staff Assigned", color: "bg-purple-100 text-purple-800" },
      3: { text: "In Progress", color: "bg-orange-100 text-orange-800" },
      4: { text: "Completed", color: "bg-green-100 text-green-800" },
      5: { text: "Canceled", color: "bg-red-100 text-red-800" }
    };
    
    const config = statusConfig[status] || { text: "Unknown", color: "bg-gray-100 text-gray-800" };
    
    return (
      <span className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ₹{config.color}`}>
        {config.text}
      </span>
    );
  };

  return (
    <div className="container mx-auto px-4 py-8 max-w-7xl">
      <div className="text-center mb-10">
        <h1 className="text-3xl font-bold text-gray-800 mb-2">My Bookings</h1>
        <p className="text-gray-600">View all your scheduled services</p>
      </div>

      {/* Loading State */}
      {loading && (
        <div className="flex justify-center items-center py-20">
          <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
        </div>
      )}

      {/* Bookings List */}
      {!loading && bookings.length > 0 ? (
        <div className="bg-white shadow overflow-hidden sm:rounded-lg">
          <ul className="divide-y divide-gray-200">
            {bookings.map((booking) => (
              <li key={booking.id} className="p-6 hover:bg-gray-50 transition-colors duration-150">
                <div className="flex flex-col md:flex-row md:items-center md:justify-between">
                  <div className="flex-1 min-w-0">
                    <div className="flex items-center space-x-4 mb-2">
                      <div className="flex-shrink-0 bg-blue-100 p-3 rounded-full">
                        <svg className="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                      </div>
                      <div>
                        <h3 className="text-lg font-medium text-gray-900">{booking.service?.name || "N/A"}</h3>
                        <p className="text-sm text-gray-500">{booking.service?.category?.title || "N/A"}</p>
                      </div>
                    </div>
                    <div className="ml-16">
                      <div className="flex items-center text-sm text-gray-500 mb-1">
                        <svg className="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fillRule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clipRule="evenodd" />
                        </svg>
                        {formatDate(booking.date)}
                      </div>
                      <div className="flex items-center text-sm text-gray-500">
                        <svg className="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fillRule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clipRule="evenodd" />
                        </svg>
                        ₹{booking.service?.price || "N/A"}
                      </div>
                    </div>
                  </div>
                  <div className="mt-4 md:mt-0 md:ml-4">
                    {getStatusBadge(booking.status)}
                  </div>
                </div>
              </li>
            ))}
          </ul>
        </div>
      ) : (
        !loading && (
          <div className="text-center py-12">
            <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 className="mt-2 text-lg font-medium text-gray-900">No bookings found</h3>
            <p className="mt-1 text-sm text-gray-500">
              You haven't booked any services yet.
            </p>
          </div>
        )
      )}
    </div>
  );
};

export default BookedSer;
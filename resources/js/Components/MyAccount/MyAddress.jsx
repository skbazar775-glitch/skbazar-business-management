import React, { useEffect, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { deleteAddress, fetchAddresses, resetAddressState } from '../../Redux/addAddressSlice';
import Swal from 'sweetalert2';
import AddAddress from '../Ecoms/AddAddress';
import { MapPinIcon, PencilIcon, TrashIcon } from '@heroicons/react/24/outline';

const MyAddress = () => {
  const dispatch = useDispatch();
  const { addresses, loading, error, success } = useSelector((state) => state.address);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editAddressData, setEditAddressData] = useState(null);
  const [lastAction, setLastAction] = useState(null);

  const handleEdit = (addr) => {
    setEditAddressData(addr);
    setIsModalOpen(true);
    setLastAction('edit');
  };

  const handleCloseModal = () => {
    setIsModalOpen(false);
    setEditAddressData(null);
    setLastAction(null);
  };

  const handleDelete = (id) => {
    Swal.fire({
      title: 'Are you sure?',
      text: 'You won’t be able to revert this!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: 'var(--danger-color, #d33)',
      cancelButtonColor: 'var(--primary-color, #3085d6)',
      confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
      if (result.isConfirmed) {
        setLastAction('delete');
        dispatch(deleteAddress(id));
      }
    });
  };

  useEffect(() => {
    if (success && lastAction) {
      let title, text, icon;

      switch (lastAction) {
        case 'add':
          title = 'Added!';
          text = 'Address has been added successfully.';
          icon = 'success';
          break;
        case 'edit':
          title = 'Updated!';
          text = 'Address has been updated successfully.';
          icon = 'success';
          break;
        case 'delete':
          title = 'Deleted!';
          text = 'Address has been deleted successfully.';
          icon = 'success';
          break;
        default:
          return;
      }

      Swal.fire({
        title,
        text,
        icon,
        confirmButtonText: 'OK',
        timer: 2000,
        timerProgressBar: true,
      }).then(() => {
        dispatch(resetAddressState());
        setLastAction(null);
      });
    }
  }, [success, lastAction, dispatch]);

  useEffect(() => {
    dispatch(fetchAddresses());
  }, [dispatch]);

  return (
    <div className="min-h-screen w-full px-4 sm:px-6 lg:px-8 py-8 pb-24 lg:pb-8 flex justify-center items-start">
      <div className="w-full max-w-full sm:max-w-4xl">
        <div className="flex flex-col sm:flex-row justify-between items-center mb-6 sm:mb-8">
          <h2 className="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-0 text-center sm:text-left">My Addresses</h2>
          <button
            onClick={() => {
              setIsModalOpen(true);
              setLastAction('add');
            }}
            className="px-4 py-2 sm:px-5 sm:py-2.5 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors text-sm sm:text-base font-medium"
            aria-label="Add New Address"
          >
            + Add New Address
          </button>
        </div>

        {loading && (
          <div className="grid gap-4 sm:gap-6 grid-cols-1 sm:grid-cols-2 justify-items-center">
            {[...Array(4)].map((_, index) => (
              <div
                key={index}
                className="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100 w-full max-w-sm sm:max-w-md animate-pulse"
              >
                <div className="flex justify-between items-start">
                  <div className="w-full">
                    <div className="h-5 bg-gray-200 rounded w-3/4 mb-2"></div>
                    <div className="h-4 bg-gray-200 rounded w-1/2 mb-1"></div>
                    <div className="h-4 bg-gray-200 rounded w-2/3 mb-1"></div>
                    <div className="h-4 bg-gray-200 rounded w-1/3"></div>
                  </div>
                  <div className="flex space-x-2">
                    <div className="h-6 w-6 sm:h-8 sm:w-8 bg-gray-200 rounded"></div>
                    <div className="h-6 w-6 sm:h-8 sm:w-8 bg-gray-200 rounded"></div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        )}
        
        {error && (
          <div className="p-4 mb-6 bg-red-50 border-l-4 border-red-500 rounded">
            <p className="text-red-600 font-medium text-sm sm:text-base text-center sm:text-left">Error: {error}</p>
          </div>
        )}

        {addresses.length === 0 && !loading ? (
          <div className="text-center py-12 bg-white rounded-lg shadow-sm">
            <MapPinIcon className="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" aria-hidden="true" />
            <h3 className="mt-2 text-base sm:text-lg font-medium text-gray-900">No addresses saved</h3>
            <p className="mt-1 text-gray-500 text-sm sm:text-base">Get started by adding a new address.</p>
            <div className="mt-6">
              <button
                onClick={() => {
                  setIsModalOpen(true);
                  setLastAction('add');
                }}
                className="px-4 py-2 sm:px-5 sm:py-2.5 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors text-sm sm:text-base font-medium"
                aria-label="Add New Address"
              >
                Add Address
              </button>
            </div>
          </div>
        ) : (
          <div className="grid gap-4 sm:gap-6 grid-cols-1 sm:grid-cols-2 justify-items-center">
            {addresses.map((addr) => (
              <div
                key={addr.id}
                className="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition-shadow w-full max-w-sm sm:max-w-md"
              >
                <div className="flex justify-between items-start">
                  <div>
                    <h3 className="text-base sm:text-lg font-medium text-gray-900 mb-2 text-center sm:text-left">{addr.name}</h3>
                    <p className="text-gray-600 mb-1 text-xs sm:text-sm text-center sm:text-left">{addr.area}</p>
                    <p className="text-gray-600 mb-1 text-xs sm:text-sm text-center sm:text-left">
                      {addr.city}, {addr.district} - {addr.pin_code}
                    </p>
                    {addr.phone && <p className="text-gray-600 text-xs sm:text-sm text-center sm:text-left">Phone: {addr.phone}</p>}
                  </div>
                  <div className="flex space-x-2">
                    <button
                      onClick={() => handleEdit(addr)}
                      className="p-2 sm:p-3 text-indigo-600 hover:text-indigo-800 transition-colors"
                      aria-label={`Edit address for ${addr.name}`}
                    >
                      <PencilIcon className="h-5 w-5 sm:h-6 sm:w-6" aria-hidden="true" />
                    </button>
                    <button
                      onClick={() => handleDelete(addr.id)}
                      className="p-2 sm:p-3 text-red-600 hover:text-red-800 transition-colors"
                      aria-label={`Delete address for ${addr.name}`}
                    >
                      <TrashIcon className="h-5 w-5 sm:h-6 sm:w-6" aria-hidden="true" />
                    </button>
                  </div>
                </div>
              </div>
            ))}
          </div>
        )}

        {isModalOpen && (
          <AddAddress
            isOpen={isModalOpen}
            onClose={handleCloseModal}
            editData={editAddressData}
            setLastAction={setLastAction}
          />
        )}
      </div>
    </div>
  );
};

export default MyAddress;
import React, { useEffect, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { closeLocationModal, setSelectedAddressId } from '../../Redux/modalSlice';
import { setSelectedAddress } from '../../Redux/cartSlice';
import { fetchAddresses } from '../../Redux/addAddressSlice';
import AddAddress from './AddAddress';

const SelectLocation = () => {
  const dispatch = useDispatch();
  const { isLocationOpen } = useSelector((state) => state.modal);
  const { addresses } = useSelector((state) => state.address);

  const [isAddAddressOpen, setIsAddAddressOpen] = useState(false);

  useEffect(() => {
    console.log('📍 Fetching addresses...');
    dispatch(fetchAddresses());
  }, [dispatch]);

  useEffect(() => {
    console.log('📍 Addresses:', addresses);
  }, [addresses]);

  const handleSelectAddress = (address) => {
    console.log('📍 Selecting Address:', address);
    dispatch(setSelectedAddress(address));
    dispatch(setSelectedAddressId(address.id));
    dispatch(closeLocationModal());
  };

  const handleOutsideClick = (e) => {
    if (e.target === e.currentTarget) {
      dispatch(closeLocationModal());
    }
  };

  if (!isLocationOpen) return null;

  return (
    <>
      <div
        className="fixed inset-0 z-60 flex items-center justify-center bg-black bg-opacity-50"
        onClick={handleOutsideClick}
        role="dialog"
        aria-modal="true"
        aria-labelledby="location-modal-title"
      >
        <div className="bg-white rounded-lg p-6 w-full max-w-md">
          <div className="flex justify-between items-center mb-4">
            <h2 id="location-modal-title" className="text-xl font-bold text-gray-900">Select Delivery Address</h2>
            <button
              onClick={() => dispatch(closeLocationModal())}
              className="text-gray-600 hover:text-gray-900"
              aria-label="Close location modal"
            >
              <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <div className="ovietnamese
space-y-4 max-h-[300px] overflow-y-auto pr-1">
            {addresses.length === 0 ? (
              <p className="text-gray-500 text-sm">No addresses found. Please add one.</p>
            ) : (
              addresses.map((address) => (
                <div
                  key={address.id}
                  className="p-4 border rounded-md hover:bg-gray-50 cursor-pointer"
                  onClick={() => handleSelectAddress(address)}
                >
                  <p className="font-medium">{address.name}</p>
                  <p className="text-gray-600">{address.phone}</p>
                  <p className="text-gray-600">
                    {address.area}, {address.city}, {address.district}, {address.pin_code}
                  </p>
                  <p className="text-gray-600">Landmark: {address.landmark}</p>
                </div>
              ))
            )}
          </div>

          <button
            onClick={() => setIsAddAddressOpen(true)}
            className="mt-4 w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700"
            aria-label="Add new address"
          >
            Add New Address
          </button>
        </div>
      </div>

      <AddAddress
        isOpen={isAddAddressOpen}
        onClose={() => setIsAddAddressOpen(false)}
        showAlert={false}
      />
    </>
  );
};

export default SelectLocation;
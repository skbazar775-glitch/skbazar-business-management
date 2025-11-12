// AddAddress.jsx
import React, { useState, useEffect } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { addAddress, resetAddressState, updateAddress } from '../../Redux/addAddressSlice';
import Swal from 'sweetalert2';

const AddAddress = ({ isOpen, onClose, editData = null, showAlert = true, setLastAction, onAddressAdded }) => {
  const dispatch = useDispatch();
  const { loading, success, error } = useSelector((state) => state.address);

  const [newAddress, setNewAddress] = useState({
    name: '',
    phone: '',
    pin_code: '',
    district: '',
    city: '',
    area: '',
    landmark: '',
  });

  const handleChange = (e) => {
    const { name, value } = e.target;

    if (name === 'phone' && value.length > 10) return;
    if (name === 'pin_code' && value.length > 6) return;

    setNewAddress((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = () => {
    const { name, phone, pin_code, district, city, area, landmark } = newAddress;

    if (name && phone && pin_code && district && city && area && landmark) {
      if (editData) {
        dispatch(updateAddress({ id: editData.id, updatedData: newAddress }));
        if (setLastAction) setLastAction('edit');
      } else {
        dispatch(addAddress(newAddress));
        if (setLastAction) setLastAction('add');
      }
    } else {
      Swal.fire({
        title: 'Error!',
        text: 'Please fill in all required fields.',
        icon: 'error',
        confirmButtonText: 'OK',
      });
    }
  };

  const handleOutsideClick = (e) => {
    if (e.target === e.currentTarget) {
      onClose();
    }
  };

  // Reset and close on success
  useEffect(() => {
    if (success) {
      if (showAlert) {
        Swal.fire({
          title: 'Success!',
          text: editData ? 'Address updated successfully.' : 'Address added successfully.',
          icon: 'success',
          confirmButtonText: 'OK',
          timer: 2000,
          timerProgressBar: true,
        });
      }
      setNewAddress({
        name: '',
        phone: '',
        pin_code: '',
        district: '',
        city: '',
        area: '',
        landmark: '',
      });
      dispatch(resetAddressState());
      onClose(); // Close AddAddress modal
      if (onAddressAdded) onAddressAdded(); // Trigger callback to close SelectLocation
    }
  }, [success, dispatch, onClose, editData, showAlert, onAddressAdded]);

  // Handle error
  useEffect(() => {
    if (error && showAlert) {
      Swal.fire({
        title: 'Error!',
        text: typeof error === 'string' ? error : Object.values(error)?.flat().join(', '),
        icon: 'error',
        confirmButtonText: 'OK',
      });
      dispatch(resetAddressState());
    }
  }, [error, dispatch, showAlert]);

  // Populate form with editData
  useEffect(() => {
    if (editData) {
      setNewAddress(editData);
    } else {
      setNewAddress({
        name: '',
        phone: '',
        pin_code: '',
        district: '',
        city: '',
        area: '',
        landmark: '',
      });
    }
  }, [editData]);

  if (!isOpen) return null;

  return (
    <div
      className="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
      onClick={handleOutsideClick}
      role="dialog"
      aria-modal="true"
      aria-labelledby="add-address-modal-title"
    >
      <div className="bg-white rounded-lg p-6 w-full max-w-lg">
        <div className="flex justify-between items-center mb-4">
          <h2 id="add-address-modal-title" className="text-xl font-bold text-gray-900">
            {editData ? 'Edit Address' : 'Add New Address'}
          </h2>
          <button
            onClick={onClose}
            className="text-gray-600 hover:text-gray-900"
            aria-label="Close add address modal"
          >
            <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          {['name', 'phone', 'pin_code', 'district', 'city', 'area', 'landmark'].map((field) => (
            <div key={field} className={field === 'landmark' ? 'md:col-span-2' : ''}>
              <label htmlFor={field} className="block text-sm font-medium text-gray-700 capitalize">
                {field.replace('_', ' ')}
              </label>
              <input
                type={field === 'phone' || field === 'pin_code' ? 'number' : 'text'}
                id={field}
                name={field}
                value={newAddress[field]}
                onChange={handleChange}
                className="mt-1 w-full border rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500"
                required
              />
            </div>
          ))}
        </div>

        {error && showAlert && (
          <div className="mt-4 text-red-600 text-sm">
            {typeof error === 'string' ? error : Object.values(error)?.flat().join(', ')}
          </div>
        )}

        <div className="mt-6 flex justify-end space-x-2">
          <button
            onJamClick={onClose}
            className="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors"
          >
            Cancel
          </button>
          <button
            onClick={handleSubmit}
            disabled={loading}
            className={`px-4 py-2 text-white rounded-md transition-colors ${
              loading ? 'bg-indigo-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'
            }`}
          >
            {loading ? 'Saving...' : editData ? 'Update Address' : 'Save Address'}
          </button>
        </div>
      </div>
    </div>
  );
};

export default AddAddress;
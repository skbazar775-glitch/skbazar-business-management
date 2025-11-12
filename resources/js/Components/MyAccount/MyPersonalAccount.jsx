import React from 'react';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm';

const MyPersonalAccount = ({ mustVerifyEmail, status }) => {
  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-100">
      <div className="space-y-6">
        <div className="bg-white p-4 shadow sm:rounded-lg sm:p-8">
          <UpdateProfileInformationForm
            mustVerifyEmail={mustVerifyEmail}
            status={status}
            className="max-w-xl"
          />
        </div>

        <div className="bg-white p-4 shadow sm:rounded-lg sm:p-8">
          <UpdatePasswordForm className="max-w-xl" />
        </div>
      </div>
    </div>
  );
};

export default MyPersonalAccount;

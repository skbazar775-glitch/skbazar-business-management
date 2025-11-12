import React, { useState } from 'react';
import { usePage } from '@inertiajs/react';
import Header from '@/Components/Ecoms/Header';
import MyAccountSidebar from '@/Components/MyAccount/MyAcountSidebar';
import MyPersonalAccount from '@/Components/MyAccount/MyPersonalAccount';
import MyOrders from '@/Components/MyAccount/MyOrders';
import MyAddress from '@/Components/MyAccount/MyAddress';
import AllTest from '@/Components/MyAccount/AllTest';
import BookService from '@/Components/MyAccount/BookService';
import BookedSer from '@/Components/MyAccount/BookedSer';

const MyAccount = ({ mustVerifyEmail, status }) => {
  const { url } = usePage();
  const params = new URLSearchParams(url.split('?')[1]);
  const initialTab = params.get('tab') || 'personal';

  const [activeMenu, setActiveMenu] = useState(initialTab);

  const handleMenuChange = (menu) => {
    setActiveMenu(menu);
    window.history.replaceState({}, '', `/myaccount?tab=${menu}`);
  };

  const renderContent = () => {
    switch (activeMenu) {
      case 'personal':
        return <MyPersonalAccount mustVerifyEmail={mustVerifyEmail} status={status} />;
      case 'orders':
        return <MyOrders />;
      case 'address':
        return <MyAddress />;
              case 'alltest': 
        return <AllTest />;
                      case 'bookservice': 
        return <BookService />;
                              case 'bookedservi': 
        return <BookedSer />;
      default:
        return <MyPersonalAccount mustVerifyEmail={mustVerifyEmail} status={status} />;
    }
  };

  return (
    <div className="min-h-screen bg-gray-50 overflow-hidden">
      <Header />

      {/* Main layout below header */}
      <div className="flex flex-col lg:flex-row pt-24 h-[calc(100vh-6rem)]">
        {/* Sidebar - fixed width in desktop, hidden in mobile (handled by MyAccountSidebar) */}
        <div className="w-full lg:w-64 flex-shrink-0">
          <MyAccountSidebar activeMenu={activeMenu} onMenuChange={handleMenuChange} />
        </div>

        {/* Scrollable main content */}
        <div className="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 pb-20 lg:pb-8 w-full">
          {renderContent()}
        </div>
      </div>
    </div>
  );
};

export default MyAccount;
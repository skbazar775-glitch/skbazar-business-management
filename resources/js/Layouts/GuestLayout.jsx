export default function GuestLayout({ children }) {
    return (
        <div className="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-50 flex flex-col items-center justify-center p-4 sm:p-6">
            {/* Animated Background Elements */}
            <div className="fixed inset-0 overflow-hidden pointer-events-none">
                <div className="absolute -top-20 -left-20 w-80 h-80 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
                <div className="absolute top-40 -right-20 w-80 h-80 bg-indigo-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
                <div className="absolute -bottom-20 left-40 w-80 h-80 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
            </div>

            {/* Logo Section */}
            <div className="relative z-10 flex flex-col items-center mb-8 transform transition-transform duration-500 hover:scale-105">
                <div className="bg-white p-3 rounded-2xl shadow-lg mb-4 border border-gray-100">
                    <img
                        src="/logo/logo.png"
                        alt="App Logo"
                        className="h-20 w-auto mb-2 drop-shadow-md"
                    />
                </div>
                <h1 className="text-2xl font-bold text-gray-800 bg-white/80 backdrop-blur-sm px-4 py-2 rounded-full shadow-sm">Welcome Back</h1>
                <p className="text-sm text-gray-600 mt-2 bg-white/70 px-3 py-1 rounded-full">Sign in to access your account</p>
            </div>

            {/* Card */}
            <div className="relative z-10 w-full max-w-md transform transition-all duration-500 hover:translate-y-1">
                <div className="bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl overflow-hidden border border-white/20">
                    <div className="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-purple-400 via-indigo-500 to-blue-400"></div>
                    <div className="px-8 py-10">
                        {children}
                    </div>
                    <div className="px-8 py-4 bg-gray-50/50 border-t border-gray-100 text-center">
                        <p className="text-xs text-gray-500">
                            © {new Date().getFullYear()} Your Company • <a href="#" className="text-indigo-600 hover:text-indigo-800">Privacy Policy</a>
                        </p>
                    </div>
                </div>
            </div>

            {/* Footer Note */}
            <div className="relative z-10 mt-8 text-center">
                <p className="text-sm text-gray-600 bg-white/70 backdrop-blur-sm px-4 py-2 rounded-full inline-block">
                    Need help? <a href="#" className="font-medium text-indigo-600 hover:text-indigo-500">Contact support</a>
                </p>
            </div>

            {/* Add these to your global CSS */}
            <style jsx global>{`
                @keyframes blob {
                    0% { transform: translate(0px, 0px) scale(1); }
                    33% { transform: translate(30px, -50px) scale(1.1); }
                    66% { transform: translate(-20px, 20px) scale(0.9); }
                    100% { transform: translate(0px, 0px) scale(1); }
                }
                .animate-blob {
                    animation: blob 7s infinite;
                }
                .animation-delay-2000 {
                    animation-delay: 2s;
                }
                .animation-delay-4000 {
                    animation-delay: 4s;
                }
            `}</style>
        </div>
    );
}
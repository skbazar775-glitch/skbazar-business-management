import React, { useState, useEffect } from "react";
import axios from "axios";

const AllTest = () => {
  const [log, setLog] = useState(null);
  const [loading, setLoading] = useState(false);
  const [trigger, setTrigger] = useState(false);

  const fetchAuthStatus = async () => {
    setLoading(true);
    try {
      const cookies = document.cookie || "No cookies found";
      const response = await axios.get("/api/services", {
        withCredentials: true,
      });

      setLog({
        status: "Success",
        message: response.data.message || "No message provided",
        user: response.data.user || null,
        data: response.data, // Store full response data
        cookies,
        timestamp: new Date().toLocaleString(),
      });
    } catch (error) {
      const cookies = document.cookie || "No cookies found";
      setLog({
        status: "Error",
        message: error.response?.data?.message || error.message,
        statusCode: error.response?.status || "N/A",
        details: error.response?.data || {},
        cookies,
        timestamp: new Date().toLocaleString(),
      });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchAuthStatus();
  }, [trigger]);

  const handleTrigger = () => {
    setTrigger((prev) => !prev);
  };

  // Helper to render JSON data in a readable format
  const renderJsonData = (data) => {
    if (!data) return <span className="text-gray-500">No data</span>;
    return (
      <pre className="bg-gray-50 p-2 rounded-md text-sm text-gray-800 overflow-auto max-h-64">
        {JSON.stringify(data, null, 2)}
      </pre>
    );
  };

  return (
    <div className="p-6 max-w-4xl mx-auto">
      <h1 className="text-2xl font-bold mb-4">All Tests</h1>
      <button
        onClick={handleTrigger}
        disabled={loading}
        className={`mb-4 px-4 py-2 rounded-lg text-white ${
          loading ? "bg-gray-400 cursor-not-allowed" : "bg-blue-600 hover:bg-blue-700"
        }`}
      >
        {loading ? "Checking..." : "Check Authentication"}
      </button>
      <div className="bg-gray-100 p-4 rounded-lg shadow-md">
        <h2 className="text-lg font-semibold mb-2">API Log</h2>
        {loading && <p className="text-gray-600">Loading...</p>}
        {!loading && !log && (
          <p className="text-gray-600">No logs yet. Click the button to check authentication.</p>
        )}
        {!loading && log && (
          <div className="text-sm text-gray-800 space-y-2">
            <div>
              <strong>Status:</strong> {log.status}
            </div>
            <div>
              <strong>Message:</strong> {log.message}
            </div>
            {log.status === "Error" && (
              <>
                <div>
                  <strong>Status Code:</strong> {log.statusCode}
                </div>
                <div>
                  <strong>Details:</strong> {renderJsonData(log.details)}
                </div>
              </>
            )}
            {log.status === "Success" && (
              <>
                <div>
                  <strong>User:</strong> {renderJsonData(log.user)}
                </div>
                <div>
                  <strong>Full Response Data:</strong> {renderJsonData(log.data)}
                </div>
              </>
            )}
            <div>
              <strong>Cookies Sent:</strong> {renderJsonData(log.cookies)}
            </div>
            <div>
              <strong>Timestamp:</strong> {log.timestamp}
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default AllTest;
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ServiceList;
use App\Models\BookedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ApiServiceBookController extends Controller
{
    /**
     * Log an action with request and response details.
     *
     * @param string $action
     * @param Request $request
     * @param array $response
     * @param \Exception|null $exception
     * @return void
     */
    private function logAction(string $action, Request $request, array $response, ?\Exception $exception = null): void
    {
        $logData = [
            'timestamp' => Carbon::now()->toDateTimeString(),
            'user_id' => Auth::id() ?? 'guest',
            'action' => $action,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'request_data' => $this->sanitizeRequestData($request->all()),
            'response' => [
                'status' => $response['status'],
                'http_code' => $response['http_code'],
                'message' => $response['message'],
            ],
            'error' => $exception ? [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'trace' => config('app.debug') ? $exception->getTraceAsString() : null,
            ] : null,
        ];

        Log::channel('service-book')->info(json_encode($logData, JSON_PRETTY_PRINT));
    }

    /**
     * Sanitize request data to remove sensitive fields.
     *
     * @param array $data
     * @return array
     */
    private function sanitizeRequestData(array $data): array
    {
        // Remove sensitive fields like CSRF token or passwords
        return collect($data)->except(['_token', 'password', 'password_confirmation'])->toArray();
    }

    /**
     * Display categories and services, filtered by category if provided.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Log request start
            $this->logAction('service_index', $request, ['status' => 'started', 'http_code' => 0, 'message' => 'Request initiated']);

            // Fetch all categories
            $categories = Category::select('id', 'title')->get();

            // Get category_id from query parameters
            $category_id = $request->query('category_id');

            // Validate category_id if provided
            if ($category_id) {
                Validator::make(['category_id' => $category_id], [
                    'category_id' => 'exists:categories,id',
                ])->validate();
            }

            // Fetch services, filtered by category_id and active status
            $services = ServiceList::select('id', 'category_id', 'name', 'price')
                ->when($category_id, function ($query, $category_id) {
                    return $query->where('category_id', $category_id);
                })
                ->where('status', 0) // Only active services
                ->with(['category:id,title'])
                ->get();

            $response = [
                'status' => 'success',
                'data' => [
                    'categories' => $categories,
                    'services' => $services,
                    'selected_category' => $category_id ?: null,
                ],
                'message' => 'Services retrieved successfully.',
                'http_code' => 200,
            ];

            $this->logAction('service_index', $request, $response);

            return response()->json($response, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $response = [
                'status' => 'error',
                'message' => 'Invalid category ID.',
                'errors' => $e->errors(),
                'http_code' => 422,
            ];

            $this->logAction('service_index', $request, $response, $e);

            return response()->json($response, 422);
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'An error occurred while fetching services.',
                'error' => $e->getMessage(),
                'http_code' => 500,
            ];

            $this->logAction('service_index', $request, $response, $e);

            return response()->json($response, 500);
        }
    }

    /**
     * Book a service for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function book(Request $request): JsonResponse
    {
        try {
            // Log request start
            $this->logAction('service_book', $request, ['status' => 'started', 'http_code' => 0, 'message' => 'Request initiated']);

            // Validate request data
            $validated = $request->validate([
                'service_id' => 'required|exists:service_list,id',
                'address_id' => 'required|exists:addresses,id',
                'date' => 'required|date|after:now|before:'.Carbon::now()->addDays(30)->toDateTimeString(),
            ]);

            // Check if service is active
            $service = ServiceList::where('id', $validated['service_id'])
                ->where('status', 0)
                ->firstOrFail();

            // Check for duplicate bookings on the same date
            $existingBooking = BookedService::where('user_id', Auth::id())
                ->where('service_id', $validated['service_id'])
                ->where('date', $validated['date'])
                ->whereNotIn('status', [5]) // Exclude canceled bookings
                ->exists();

            if ($existingBooking) {
                $response = [
                    'status' => 'error',
                    'message' => 'You have already booked this service for the selected date.',
                    'http_code' => 422,
                ];

                $this->logAction('service_book', $request, $response);

                return response()->json($response, 422);
            }

            // Create booking
            $bookedService = BookedService::create([
                'service_id' => $validated['service_id'],
                'user_id' => Auth::id(),
                'address_id' => $validated['address_id'],
                'date' => Carbon::parse($validated['date']),
                'status' => 0, // Pending
            ]);

            // Load relationships for response
            $bookedService->load(['service:id,name,price,category_id', 'service.category:id,title']);

            $response = [
                'status' => 'success',
                'data' => [
                    'booking' => $bookedService,
                ],
                'message' => 'Service booked successfully.',
                'http_code' => 201,
            ];

            $this->logAction('service_book', $request, $response);

            return response()->json($response, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $response = [
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
                'http_code' => 422,
            ];

            $this->logAction('service_book', $request, $response, $e);

            return response()->json($response, 422);
        } catch (ModelNotFoundException $e) {
            $response = [
                'status' => 'error',
                'message' => 'Service not found or inactive.',
                'http_code' => 404,
            ];

            $this->logAction('service_book', $request, $response, $e);

            return response()->json($response, 404);
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'An error occurred while booking the service.',
                'error' => $e->getMessage(),
                'http_code' => 500,
            ];

            $this->logAction('service_book', $request, $response, $e);

            return response()->json($response, 500);
        }
    }

    /**
     * Retrieve all bookings for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bookings(Request $request): JsonResponse
    {
        try {
            // Log request start
            $this->logAction('service_bookings', $request, ['status' => 'started', 'http_code' => 0, 'message' => 'Request initiated']);

            // Fetch bookings with relationships
            $bookings = BookedService::where('user_id', Auth::id())
                ->select('id', 'service_id', 'address_id', 'date', 'status', 'created_at')
->with([
    'service:id,name,price,category_id',
    'service.category:id,title',
    'address:id,area,city,district,pin_code'
])

                ->latest()
                ->get();

            $response = [
                'status' => 'success',
                'data' => [
                    'bookings' => $bookings,
                ],
                'message' => 'Bookings retrieved successfully.',
                'http_code' => 200,
            ];

            $this->logAction('service_bookings', $request, $response);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'An error occurred while fetching bookings.',
                'error' => $e->getMessage(),
                'http_code' => 500,
            ];

            $this->logAction('service_bookings', $request, $response, $e);

            return response()->json($response, 500);
        }
    }

    /**
     * Cancel a booking (only if status is Pending or Confirmed).
     *
     * @param Request $request
     * @param BookedService $booking
     * @return JsonResponse
     */
    public function cancel(Request $request, BookedService $booking): JsonResponse
    {
        try {
            // Log request start
            $this->logAction('service_cancel', $request, ['status' => 'started', 'http_code' => 0, 'message' => 'Request initiated']);

            // Check if booking belongs to the user
            if ($booking->user_id !== Auth::id()) {
                $response = [
                    'status' => 'error',
                    'message' => 'Unauthorized to cancel this booking.',
                    'http_code' => 403,
                ];

                $this->logAction('service_cancel', $request, $response);

                return response()->json($response, 403);
            }

            // Check if booking can be canceled
            if (!in_array($booking->status, [0, 1])) {
                $response = [
                    'status' => 'error',
                    'message' => 'Cannot cancel booking in current status: ' . $booking->status_text,
                    'http_code' => 422,
                ];

                $this->logAction('service_cancel', $request, $response);

                return response()->json($response, 422);
            }

            // Update status to Canceled
            $booking->update(['status' => 5]);

            $response = [
                'status' => 'success',
                'message' => 'Booking canceled successfully.',
                'http_code' => 200,
            ];

            $this->logAction('service_cancel', $request, $response);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'An error occurred while canceling the booking.',
                'error' => $e->getMessage(),
                'http_code' => 500,
            ];

            $this->logAction('service_cancel', $request, $response, $e);

            return response()->json($response, 500);
        }
    }
}
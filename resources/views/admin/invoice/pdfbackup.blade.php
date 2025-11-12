```html
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tax Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #000000;
            margin: 20px;
            font-size: 12px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
        }
        .company-details, .customer-details {
            width: 45%;
            display: inline-block;
            vertical-align: top;
            margin-bottom: 20px;
        }
        .customer-details {
            margin-left: 10%;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .summary {
            width: 50%;
            float: right;
        }
        .summary div {
            margin-bottom: 10px;
        }
        .summary label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .footer {
            clear: both;
            border-top: 1px solid #000;
            padding-top: 10px;
            margin-top: 20px;
        }
        .text-right {
            text-align: right;
        }
        .text-bold {
            font-weight: bold;
        }
        .text-italic {
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tax Invoice</h1>
            <p>Invoice No: {{ $invoice->invoice_number }}</p>
            <p>Date: {{ $invoice->created_at->format('d/m/Y') }}</p>
        </div>

        <div class="company-details">
            <h3>From:</h3>
            <p><strong>Your Company Name</strong></p>
            <p>123 Business Street, City, State, ZIP</p>
            <p>GSTIN: 12ABCDE3456F7GH</p>
            <p>Phone: +91 12345 67890</p>
        </div>

        <div class="customer-details">
            <h3>To:</h3>
            <p><strong>{{ $invoice->customer_name }}</strong></p>
            <p>{{ $invoice->customer_address }}</p>
            <p>Phone: {{ $invoice->customer_mobile }}</p>
            <p>GST No: {{ $invoice->customer_gst_no }}</p>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>HSN Code</th>
                    <th>Rate (Inc. GST)</th>
                    <th>GST %</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->hsn_code }}</td>
                        <td>₹{{ number_format($item->rate_with_gst, 2) }}</td>
                        <td>{{ ['0', '12', '18', '28'][$item->gst_percent] }}%</td>
                        <td>₹{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <div><label>Total Price:</label> ₹{{ number_format($invoice->total_price, 2) }}</div>
            <div><label>Total GST:</label> ₹{{ number_format($invoice->total_gst, 2) }}</div>
            <div><label>SGST:</label> ₹{{ number_format($invoice->sgst, 2) }}</div>
            <div><label>CGST:</label> ₹{{ number_format($invoice->cgst, 2) }}</div>
            <div><label>Discount:</label> ₹{{ number_format($invoice->discount, 2) }}</div>
            <div><label>Advance Payment:</label> ₹{{ number_format($invoice->advance_payment, 2) }}</div>
            <div><label class="text-bold">Final Amount:</label> ₹{{ number_format($invoice->final_amount, 2) }}</div>
            <div><label>Total in Words:</label> <span class="text-italic">{{ $invoice->total_in_words }}</span></div>
            <div><label>Payment Terms:</label> {{ ['Fully Advance', 'Half Advance', 'Due'][$invoice->payment_terms] }}</div>
            <div><label>Payment Mode:</label> {{ ['Mobile Banking', 'Online Payment', 'Cash Payment', 'Cash on Delivery'][$invoice->payment_mode] }}</div>
        </div>

        <div class="footer">
            <p>Terms & Conditions: Payment due as per terms agreed. Thank you for your business!</p>
        </div>
    </div>
</body>
</html>
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.2;
            color: #000;
            background: #fff;
            padding: 10px;
        }
        
        .invoice-container {
            max-width: 100%;
            margin: 0 auto;
        }
        
        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #000;
        }
        
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .company-tagline {
            font-size: 12px;
            margin-bottom: 3px;
        }
        
        .company-details {
            font-size: 10px;
            line-height: 1.3;
        }
        
        /* Buyer Section */
        .buyer-section {
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #000;
        }
        
        .section-title {
            font-weight: bold;
            margin-bottom: 3px;
            font-size: 11px;
        }
        
        .buyer-details {
            font-size: 10px;
            line-height: 1.3;
        }
        
        /* Payment Details */
        .payment-section {
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #000;
        }
        
        .payment-details {
            font-size: 10px;
            line-height: 1.3;
        }
        
        /* Items Table */
        .table-section {
            margin-bottom: 8px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        
        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 4px 2px;
            text-align: center;
        }
        
        .items-table th {
            font-weight: bold;
            background: #f0f0f0;
        }
        
        .text-left {
            text-align: left;
        }
        
        /* Amount in Words */
        .amount-words {
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #000;
            font-size: 10px;
            font-weight: bold;
        }
        
        /* Notes Section */
        .notes-section {
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #000;
        }
        
        .notes-list {
            padding-left: 15px;
            font-size: 10px;
        }
        
        .notes-list li {
            margin-bottom: 2px;
        }
        
        /* Total Price */
        .total-price {
            text-align: right;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #000;
            font-size: 11px;
            font-weight: bold;
        }
        
        /* Amount Table */
        .amount-table {
            width: 150px;
            margin-left: auto;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 15px;
        }
        
        .amount-table td {
            padding: 3px 6px;
            border: 1px solid #000;
        }
        
        .amount-table tr:last-child {
            font-weight: bold;
        }
        
        /* Signature Section */
        .signature-section {
            text-align: right;
            margin-bottom: 10px;
        }
        
        .signature-line {
            margin-top: 25px;
            border-top: 1px solid #000;
            width: 150px;
            display: inline-block;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            padding-top: 8px;
            border-top: 1px solid #000;
            font-size: 9px;
            color: #666;
        }
        
        @media print {
            body {
                padding: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">SKBazar Solar System</div>
            <div class="company-tagline">SKBazar India Ka Apna Solar Bazar</div>
            <div class="company-details">
                Address: Barua, Power House, Beldanga, Murshidabad, WB, Pin-742189<br>
                Phone: 8597804890 | GSTIN: 19GZTPS2361Q1Z1
            </div>
        </div>
        
        <!-- Buyer Section -->
        <div class="buyer-section">
            <div class="section-title">Buyer</div>
            <div class="buyer-details">
                Name: {{ $invoice->customer_name }}<br>
                Address: {{ $invoice->customer_address }}<br>
                GSTIN: {{ $invoice->customer_gst_no ?: 'NA' }}<br>
                Phone: {{ $invoice->customer_mobile }}
            </div>
        </div>
        
        <!-- Payment Details -->
        <div class="payment-section">
            <div class="section-title">Company Payment Details:</div>
            <div class="payment-details">
                Bank: BHANDHAN BANK<br>
                Name: SAMRUL<br>
                A/C: 5017000125600<br>
                IFSC: BDBL0001107<br>
                Branch: BELDANGA
            </div>
        </div>
        
        <!-- Items Table -->
        <div class="table-section">
            <table class="items-table">
                <thead>
                    <tr>
                        <th width="5%">S.N</th>
                        <th width="35%">Description</th>
                        <th width="8%">GST %</th>
                        <th width="10%">GST Value</th>
                        <th width="8%">Qty</th>
                        <th width="12%">Rate</th>
                        <th width="12%">Price</th>
                        <th width="10%">Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoice->items as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="text-left">{{ $item->product_name }} @if($item->serial_number && $item->serial_number != 'NA') Serial: {{ $item->serial_number }} @endif</td>
                            <td>{{ $item->gst_percentage ?? 0 }}</td>
                            <td>{{ $invoice->currency_symbol }}{{ number_format($item->gst_value, 2) }}</td>
                            <td>{{ number_format($item->quantity, 2) }}</td>
                            <td>{{ $invoice->currency_symbol }}{{ number_format($item->rate_without_gst, 2) }}</td>
                            <td>{{ $invoice->currency_symbol }}{{ number_format($item->rate_with_gst, 2) }}</td>
                            <td>{{ $invoice->currency_symbol }}{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No items found</td>
                        </tr>
                    @endforelse
                    
                    <!-- Empty rows -->
                    @for($i = count($invoice->items); $i < 13; $i++)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        
        <!-- Amount in Words -->
        <div class="amount-words">
            Total Amount in Words: {{ $amountInWords ?? 'Six Thousand Three Hundred Eighteen Rupees Only' }}
        </div>
        
        <!-- Notes Section -->
        <div class="notes-section">
            <div class="section-title">Notes</div>
            <ol class="notes-list">
                <li>All Products Under Manufacturer Warranty</li>
                <li>Any Problem Inform us Time Take UpTo 30 Days</li>
                <li>Warranty Void on Physical Damage, Breakage</li>
                <li>Warranty Seal & Serial no. With Invoice Must Present</li>
                <li>Goods Sold Will Not Be Taken Back</li>
            </ol>
        </div>
        
        <!-- Total Price -->
        <div class="total-price">
            Total Price: {{ $invoice->currency_symbol }}{{ number_format($invoice->final_amount, 2) }}
        </div>
        
        <!-- Amount Table -->
        <table class="amount-table">
            <tr>
                <td>Discount:</td>
                <td>{{ $invoice->currency_symbol }}{{ number_format($invoice->discount, 2) }}</td>
            </tr>
            <tr>
                <td>Advance:</td>
                <td>{{ $invoice->currency_symbol }}{{ number_format($invoice->advance_payment, 2) }}</td>
            </tr>
            <tr>
                <td>Total:</td>
                <td>{{ $invoice->currency_symbol }}{{ number_format($invoice->final_amount - $invoice->advance_payment, 2) }}</td>
            </tr>
        </table>
        
        <!-- Signature Section -->
        <div class="signature-section">
            For SKBazar Solar System<br>
            <div class="signature-line"></div><br>
            Authorized Signatory
        </div>
        
        <!-- Footer -->
        <div class="footer">
            &copy; 2025 SKBazar Solar System. All Rights Reserved. | Powered by SKBazar
        </div>
    </div>
</body>
</html>
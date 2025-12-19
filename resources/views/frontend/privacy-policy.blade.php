@extends('frontend.layouts.app')

@section('title', 'Return & Exchange Policy')

@section('content')
    <style>
        .about-section {
            padding: 60px 0;
        }

        .about-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .about-header h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #333;
        }

        .about-header p {
            font-size: 1.1rem;
            color: #666;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .about-content-block {
            margin-bottom: 60px;
        }

        .about-content-block h2 {
            font-size: 2rem;
            margin-bottom: 30px;
            color: #333;
            text-align: center;
        }

        /* Reusing values-grid for policy sections if we want grid, or just cards */
        .policy-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
            margin-top: 40px;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        .policy-card {
            background: #fff7df;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .policy-card h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 15px;
        }

        .policy-card h3 {
            font-size: 1.2rem;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #444;
            font-weight: 600;
        }

        .policy-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .policy-card ul {
            list-style-type: disc;
            margin-left: 20px;
            margin-bottom: 15px;
            color: #666;
        }

        .policy-card ul li {
            margin-bottom: 8px;
            line-height: 1.5;
        }

        .founders-section {
            background: #c8c4b1;
            padding: 60px 0;
        }

        /* Alternating section style */
        .alt-section {
            background: #c8c4b1;
            /* Same as founders section background */
            padding: 60px 0;
        }

        .disclaimer-box {
            background: #fff0f0;
            border-left: 4px solid #d9534f;
            padding: 15px;
            margin-top: 30px;
            border-radius: 4px;
        }

        .disclaimer-box p {
            color: #a94442;
            margin: 0;
            font-size: 0.95rem;
        }
    </style>

    <div class="about-section">
        <div class="wrapper">
            <div class="about-header">
                <h1>Our Policies</h1>
                <p>Transparency and Trust at Savera Forever Private Limited</p>
            </div>

            <div class="policy-grid">
                <!-- Return Policy -->
                <div class="policy-card" id="returnPolicy">
                    <h2>Return Policy</h2>
                    <p><strong>Return Policy – Savera Forever Private Limited</strong></p>
                    <p>At Savera Forever Private Limited, each piece of jewellery is carefully crafted and quality-checked
                        before dispatch. We do not offer returns on our products.</p>

                    <h3>1. No Return Policy</h3>
                    <p>Savera Forever Private Limited does not accept returns or provide refunds in cash, bank transfer, or
                        original payment mode.</p>

                    <h3>2. Exchange in Place of Return</h3>
                    <p>In place of returns, we offer an exchange facility strictly under the conditions mentioned in our
                        Exchange Policy, subject to:</p>
                    <ul>
                        <li>Size-related issues</li>
                        <li>Manufacturing defects</li>
                    </ul>
                </div>

                <!-- Exchange Policy -->
                <div class="policy-card" id="exchangePolicy">
                    <h2>Exchange Policy</h2>
                    <p><strong>Exchange Policy – Savera Forever Private Limited</strong></p>
                    <p>At Savera Forever Private Limited, we ensure quality craftsmanship in every piece. Our exchange
                        policy is designed to provide clarity and fairness to our customers.</p>

                    <h3>1. Exchange Period</h3>
                    <p>Exchange requests must be raised within 10 days from the date of delivery.</p>

                    <h3>2. Eligible Reasons for Exchange</h3>
                    <p>Exchanges are permitted only in the following cases:</p>
                    <ul>
                        <li>Size-related issues</li>
                        <li>Manufacturing defects</li>
                    </ul>

                    <h3>3. Condition of Product</h3>
                    <p>The jewellery must be:</p>
                    <ul>
                        <li>Unused and unworn</li>
                        <li>In original condition</li>
                        <li>Accompanied by the original invoice and lab certification</li>
                    </ul>
                    <p>Any item showing signs of wear, alteration, or damage will not be eligible.</p>

                    <h3>4. Price Difference</h3>
                    <ul>
                        <li>If the exchanged product is of higher value, the customer must pay the difference.</li>
                        <li>If the exchanged product is of lower value, the balance amount will be issued as store credit
                            (no cash or bank refund).</li>
                    </ul>

                    <h3>5. Customized Jewellery</h3>
                    <p>Jewellery made to specific customer requirements (including custom sizes or engravings) is not
                        eligible for exchange, except in case of manufacturing defects.</p>

                    <h3>6. Final Approval</h3>
                    <p>All exchanges are subject to quality inspection and approval by Savera Forever Private Limited.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Using the alternate background color section for variety, similar to Founders section in About Us -->
    <div class="alt-section">
        <div class="wrapper">
            <div class="policy-grid">
                <!-- Buy-Back Policy -->
                <div class="policy-card" id="buyBackPolicy">
                    <h2>Buy-Back Policy</h2>
                    <p><strong>Buy-Back Policy – Savera Forever Private Limited</strong></p>
                    <p>Savera Forever Private Limited offers a transparent buy-back policy to provide long-term value to our
                        customers.</p>

                    <h3>1. Eligibility Period</h3>
                    <p>Buy-back requests can be initiated after a minimum period of 3 months from the original purchase
                        date.</p>

                    <h3>2. Diamond Buy-Back Value</h3>
                    <ul>
                        <li>Lab-grown diamonds will be bought back at 80% of the prevailing market value at the time of
                            buy-back.</li>
                    </ul>

                    <h3>3. Metal Buy-Back Value</h3>
                    <ul>
                        <li>Gold or platinum value will be calculated as per the current market rate on the date of
                            buy-back.</li>
                    </ul>

                    <h3>4. Making Charges & Deductions</h3>
                    <ul>
                        <li>Making charges and applicable deductions will be fully deducted during buy-back valuation.</li>
                    </ul>

                    <h3>5. Buy-Back Mode</h3>
                    <ul>
                        <li>The buy-back value will be provided only as store credit, redeemable against future purchases.
                        </li>
                        <li>No cash or bank transfer will be issued.</li>
                    </ul>

                    <h3>6. Certification Requirement</h3>
                    <ul>
                        <li>Submission of the original invoice and lab certificate is mandatory for buy-back eligibility.
                        </li>
                    </ul>

                    <h3>7. Final Valuation</h3>
                    <ul>
                        <li>The final buy-back amount will be determined after physical verification and quality assessment
                            by Savera Forever Private Limited.</li>
                    </ul>

                    <div class="disclaimer-box">
                        <p><strong>Disclaimer:</strong> Savera Forever Private Limited reserves the right to modify, amend,
                            or withdraw these policies at its sole discretion without prior notice.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="about-section">
        <div class="wrapper">
            <div class="policy-grid">
                <!-- Privacy Policy -->
                <div class="policy-card" id="privacyPolicy">
                    <h2>Privacy Policy</h2>
                    <p><strong>Privacy Policy – Savera Forever Private Limited</strong></p>
                    <p>Savera Forever Private Limited (“we”, “our”, “us”) is committed to protecting the privacy and
                        personal data of our customers. This Privacy Policy explains how we collect, use, store, and protect
                        your information when you visit or make a purchase from our website.</p>

                    <h3>2. Purpose of Data Collection</h3>
                    <p>Customer data is collected strictly for legitimate business purposes, including:</p>
                    <ul>
                        <li>Processing and delivering orders</li>
                        <li>Order confirmation, invoices, and customer support</li>
                        <li>Exchange, buy-back, and warranty verification</li>
                        <li>Legal, regulatory, and accounting compliance</li>
                        <li>Improving our products, services, and website experience</li>
                        <li>Sending order-related updates and service communications</li>
                    </ul>
                    <p>We do not sell or rent customer data to third parties.</p>

                    <h3>3. Data Sharing & Disclosure</h3>
                    <p>Customer information may be shared only when necessary, such as with:</p>
                    <ul>
                        <li>Logistics and delivery partners</li>
                        <li>Payment gateway providers</li>
                        <li>Legal or regulatory authorities, if required under Indian law</li>
                    </ul>
                    <p>All third parties are required to maintain confidentiality and data security.</p>

                    <h3>Cookies & Website Analytics</h3>
                    <p>Our website may use cookies and similar technologies to:</p>
                    <ul>
                        <li>Improve website functionality</li>
                        <li>Analyze traffic and user behavior</li>
                    </ul>
                    <p>Customers may disable cookies through their browser settings, though this may affect website
                        performance.</p>
                </div>

                <!-- Shipping Policy -->
                <div class="policy-card" id="shippingPolicy">
                    <h2>Shipping Policy</h2>
                    <p><strong>Shipping Policy – Savera Forever Private Limited</strong></p>

                    <h3>1. Shipping Coverage</h3>
                    <p>We currently deliver only within India.</p>

                    <h3>2. Shipping Charges</h3>
                    <p>All orders placed on our website are eligible for free shipping.</p>

                    <h3>3. Order Processing & Dispatch</h3>
                    <p>Orders are processed and dispatched within 1 working day after order confirmation.</p>

                    <h3>4. Delivery Timeline</h3>
                    <ul>
                        <li>Metro cities: Delivery within 2 working days after dispatch</li>
                        <li>Other locations: Delivery within 3–4 working days after dispatch</li>
                    </ul>
                    <p>Delivery timelines may vary due to location, weather, or unforeseen courier delays.</p>

                    <h3>5. Courier Partners</h3>
                    <p>Orders are shipped through trusted courier partners to ensure safe and secure delivery.</p>

                    <h3>6. Insurance & Safety</h3>
                    <p>All jewellery shipments are fully insured until delivery to the customer.</p>

                    <h3>7. Delivery Attempts & Re-shipping</h3>
                    <p>If delivery fails due to incorrect address, customer unavailability, or refusal to accept the order,
                        re-shipping charges will be borne by the customer.</p>

                    <h3>8. Order Tracking</h3>
                    <p>Tracking details will be shared with the customer once the order is dispatched.</p>

                    <p><em>Note: Savera Forever Private Limited reserves the right to update this Shipping Policy without
                            prior notice.</em></p>
                </div>
            </div>
        </div>
    </div>

@endsection
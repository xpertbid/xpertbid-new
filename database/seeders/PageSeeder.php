<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating static pages...');

        // Get existing data
        $tenant = DB::table('tenants')->first();

        if (!$tenant) {
            $this->command->error('Required data not found. Please run other seeders first.');
            return;
        }

        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<h1>About XpertBid</h1>
                <p>Welcome to XpertBid, your premier destination for online auctions, e-commerce, and marketplace services. Since our founding, we have been committed to providing a seamless, secure, and innovative platform that connects buyers and sellers from around the world.</p>
                
                <h2>Our Mission</h2>
                <p>Our mission is to revolutionize the online marketplace experience by offering cutting-edge technology, exceptional customer service, and a diverse range of products and services. We believe in creating opportunities for everyone, from individual sellers to large businesses.</p>
                
                <h2>What We Offer</h2>
                <ul>
                    <li><strong>Online Auctions:</strong> Participate in exciting auctions for unique and valuable items</li>
                    <li><strong>E-commerce Marketplace:</strong> Buy and sell products across multiple categories</li>
                    <li><strong>Real Estate Listings:</strong> Browse and list residential and commercial properties</li>
                    <li><strong>Vehicle Sales:</strong> Find your next car, truck, or motorcycle</li>
                    <li><strong>Multi-Vendor Support:</strong> Join our network of verified sellers</li>
                </ul>
                
                <h2>Our Values</h2>
                <p>We are committed to:</p>
                <ul>
                    <li>Transparency and fairness in all transactions</li>
                    <li>Security and protection of user data</li>
                    <li>Innovation and continuous platform improvement</li>
                    <li>Customer satisfaction and support</li>
                    <li>Community building and engagement</li>
                </ul>
                
                <h2>Get Started Today</h2>
                <p>Whether you\'re looking to buy, sell, or participate in auctions, XpertBid provides the tools and platform you need to succeed. Join our growing community and discover the future of online marketplaces.</p>',
                'meta_title' => 'About Us - XpertBid Marketplace',
                'meta_description' => 'Learn about XpertBid, your premier destination for online auctions, e-commerce, and marketplace services.',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h1>Privacy Policy</h1>
                <p><strong>Last Updated:</strong> ' . now()->format('F j, Y') . '</p>
                
                <h2>Introduction</h2>
                <p>XpertBid ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our website and services.</p>
                
                <h2>Information We Collect</h2>
                <h3>Personal Information</h3>
                <ul>
                    <li>Name and contact information</li>
                    <li>Email address and phone number</li>
                    <li>Billing and shipping addresses</li>
                    <li>Payment information</li>
                    <li>Account credentials</li>
                </ul>
                
                <h3>Usage Information</h3>
                <ul>
                    <li>Website activity and interactions</li>
                    <li>Device information and IP addresses</li>
                    <li>Browser type and version</li>
                    <li>Pages visited and time spent</li>
                    <li>Search queries and preferences</li>
                </ul>
                
                <h2>How We Use Your Information</h2>
                <p>We use the information we collect to:</p>
                <ul>
                    <li>Provide and maintain our services</li>
                    <li>Process transactions and payments</li>
                    <li>Communicate with you about your account</li>
                    <li>Improve our platform and user experience</li>
                    <li>Send promotional materials and updates</li>
                    <li>Ensure security and prevent fraud</li>
                </ul>
                
                <h2>Information Sharing</h2>
                <p>We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:</p>
                <ul>
                    <li>With your explicit consent</li>
                    <li>To comply with legal obligations</li>
                    <li>To protect our rights and property</li>
                    <li>With service providers who assist our operations</li>
                </ul>
                
                <h2>Data Security</h2>
                <p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
                
                <h2>Your Rights</h2>
                <p>You have the right to:</p>
                <ul>
                    <li>Access your personal information</li>
                    <li>Correct inaccurate data</li>
                    <li>Delete your information</li>
                    <li>Opt-out of marketing communications</li>
                    <li>Data portability</li>
                </ul>
                
                <h2>Contact Us</h2>
                <p>If you have any questions about this Privacy Policy, please contact us at privacy@xpertbid.com</p>',
                'meta_title' => 'Privacy Policy - XpertBid',
                'meta_description' => 'Learn how XpertBid protects your privacy and handles your personal information.',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => '<h1>Terms of Service</h1>
                <p><strong>Last Updated:</strong> ' . now()->format('F j, Y') . '</p>
                
                <h2>Acceptance of Terms</h2>
                <p>By accessing and using XpertBid, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>
                
                <h2>Description of Service</h2>
                <p>XpertBid provides an online marketplace platform that facilitates:</p>
                <ul>
                    <li>Online auctions for various items</li>
                    <li>E-commerce transactions between buyers and sellers</li>
                    <li>Real estate listings and transactions</li>
                    <li>Vehicle sales and purchases</li>
                    <li>Multi-vendor marketplace services</li>
                </ul>
                
                <h2>User Accounts</h2>
                <p>To access certain features, you must register for an account. You are responsible for:</p>
                <ul>
                    <li>Maintaining the confidentiality of your account</li>
                    <li>All activities under your account</li>
                    <li>Providing accurate and current information</li>
                    <li>Notifying us of any unauthorized use</li>
                </ul>
                
                <h2>Prohibited Uses</h2>
                <p>You may not use our service:</p>
                <ul>
                    <li>For any unlawful purpose or activity</li>
                    <li>To violate any international, federal, provincial, or state regulations, rules, laws, or local ordinances</li>
                    <li>To infringe upon or violate our intellectual property rights or the intellectual property rights of others</li>
                    <li>To harass, abuse, insult, harm, defame, slander, disparage, intimidate, or discriminate</li>
                    <li>To submit false or misleading information</li>
                    <li>To upload or transmit viruses or any other type of malicious code</li>
                </ul>
                
                <h2>Payment Terms</h2>
                <p>All payments are processed securely through our payment partners. You agree to pay all fees and charges associated with your use of the service.</p>
                
                <h2>Intellectual Property</h2>
                <p>The service and its original content, features, and functionality are owned by XpertBid and are protected by international copyright, trademark, patent, trade secret, and other intellectual property laws.</p>
                
                <h2>Limitation of Liability</h2>
                <p>In no event shall XpertBid, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses.</p>
                
                <h2>Termination</h2>
                <p>We may terminate or suspend your account immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.</p>
                
                <h2>Changes to Terms</h2>
                <p>We reserve the right to modify or replace these Terms at any time. If a revision is material, we will try to provide at least 30 days notice prior to any new terms taking effect.</p>
                
                <h2>Contact Information</h2>
                <p>If you have any questions about these Terms of Service, please contact us at legal@xpertbid.com</p>',
                'meta_title' => 'Terms of Service - XpertBid',
                'meta_description' => 'Read the terms and conditions for using XpertBid marketplace and auction services.',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'Shipping Information',
                'slug' => 'shipping-information',
                'content' => '<h1>Shipping Information</h1>
                
                <h2>Shipping Options</h2>
                <p>We offer various shipping options to meet your needs and budget:</p>
                
                <h3>Standard Shipping</h3>
                <ul>
                    <li><strong>Delivery Time:</strong> 5-7 business days</li>
                    <li><strong>Cost:</strong> $9.99 (Free on orders over $75)</li>
                    <li><strong>Tracking:</strong> Included</li>
                </ul>
                
                <h3>Express Shipping</h3>
                <ul>
                    <li><strong>Delivery Time:</strong> 2-3 business days</li>
                    <li><strong>Cost:</strong> $19.99 (Free on orders over $150)</li>
                    <li><strong>Tracking:</strong> Included with real-time updates</li>
                </ul>
                
                <h3>Overnight Shipping</h3>
                <ul>
                    <li><strong>Delivery Time:</strong> Next business day</li>
                    <li><strong>Cost:</strong> $39.99</li>
                    <li><strong>Availability:</strong> Limited to certain areas</li>
                </ul>
                
                <h2>International Shipping</h2>
                <p>We ship to most countries worldwide. International shipping rates and delivery times vary by destination. Please contact us for specific rates to your location.</p>
                
                <h2>Shipping Restrictions</h2>
                <p>Some items cannot be shipped due to size, weight, or legal restrictions:</p>
                <ul>
                    <li>Hazardous materials</li>
                    <li>Items over 70 lbs</li>
                    <li>Fragile items requiring special handling</li>
                    <li>Items restricted by local laws</li>
                </ul>
                
                <h2>Order Processing</h2>
                <p>Orders are typically processed within 1-2 business days. You will receive a confirmation email with tracking information once your order ships.</p>
                
                <h2>Delivery Issues</h2>
                <p>If you experience any delivery issues:</p>
                <ul>
                    <li>Check your tracking information first</li>
                    <li>Contact the shipping carrier directly</li>
                    <li>Contact our customer service team</li>
                    <li>Report lost or damaged packages immediately</li>
                </ul>
                
                <h2>Returns and Exchanges</h2>
                <p>Most items can be returned within 30 days of delivery. Please see our Return Policy for complete details.</p>
                
                <h2>Contact Us</h2>
                <p>For shipping questions or concerns, please contact our customer service team at shipping@xpertbid.com or call 1-800-XPERTBID.</p>',
                'meta_title' => 'Shipping Information - XpertBid',
                'meta_description' => 'Learn about shipping options, rates, and delivery times for XpertBid orders.',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'Return Policy',
                'slug' => 'return-policy',
                'content' => '<h1>Return Policy</h1>
                
                <h2>30-Day Return Guarantee</h2>
                <p>We stand behind our products with a 30-day return guarantee. If you\'re not completely satisfied with your purchase, you can return it for a full refund or exchange.</p>
                
                <h2>What Can Be Returned</h2>
                <p>Most items can be returned, including:</p>
                <ul>
                    <li>Unused items in original packaging</li>
                    <li>Items with manufacturing defects</li>
                    <li>Items that don\'t match their description</li>
                    <li>Damaged items received</li>
                </ul>
                
                <h2>Return Conditions</h2>
                <p>To qualify for a return, items must:</p>
                <ul>
                    <li>Be returned within 30 days of delivery</li>
                    <li>Be in original condition and packaging</li>
                    <li>Include all original accessories and documentation</li>
                    <li>Not show signs of use or damage</li>
                </ul>
                
                <h2>Items Not Eligible for Return</h2>
                <p>The following items cannot be returned:</p>
                <ul>
                    <li>Personalized or custom-made items</li>
                    <li>Digital downloads and software</li>
                    <li>Perishable items</li>
                    <li>Items damaged by misuse or normal wear</li>
                    <li>Auction items (special terms apply)</li>
                </ul>
                
                <h2>How to Return an Item</h2>
                <ol>
                    <li>Contact our customer service team to initiate a return</li>
                    <li>Receive a Return Authorization (RA) number</li>
                    <li>Package the item securely with the RA number</li>
                    <li>Ship the item back to us using the provided label</li>
                    <li>Wait for processing and refund</li>
                </ol>
                
                <h2>Return Shipping</h2>
                <p>Return shipping costs vary:</p>
                <ul>
                    <li><strong>Defective Items:</strong> Free return shipping</li>
                    <li><strong>Customer Change of Mind:</strong> Customer pays return shipping</li>
                    <li><strong>Our Error:</strong> Free return shipping</li>
                </ul>
                
                <h2>Refund Processing</h2>
                <p>Once we receive your returned item:</p>
                <ul>
                    <li>We inspect the item within 2-3 business days</li>
                    <li>Approved refunds are processed within 5-7 business days</li>
                    <li>Refunds are issued to the original payment method</li>
                    <li>You will receive email confirmation of the refund</li>
                </ul>
                
                <h2>Exchanges</h2>
                <p>For exchanges:</p>
                <ul>
                    <li>Follow the same return process</li>
                    <li>Specify the desired replacement item</li>
                    <li>We\'ll ship the new item once the return is processed</li>
                    <li>Price differences will be charged or refunded as applicable</li>
                </ul>
                
                <h2>International Returns</h2>
                <p>International returns may have additional restrictions and costs. Please contact us for specific information about returns from your country.</p>
                
                <h2>Contact Us</h2>
                <p>For return questions or to initiate a return, contact us at returns@xpertbid.com or call 1-800-XPERTBID.</p>',
                'meta_title' => 'Return Policy - XpertBid',
                'meta_description' => 'Learn about XpertBid\'s return policy, including eligibility, process, and refund information.',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'content' => '<h1>Contact Us</h1>
                <p>We\'re here to help! Reach out to us through any of the methods below, and we\'ll get back to you as soon as possible.</p>
                
                <h2>Customer Service</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 20px 0;">
                    <div>
                        <h3>📧 Email Support</h3>
                        <p><strong>General Inquiries:</strong> support@xpertbid.com</p>
                        <p><strong>Technical Issues:</strong> tech@xpertbid.com</p>
                        <p><strong>Billing Questions:</strong> billing@xpertbid.com</p>
                        <p><strong>Response Time:</strong> Within 24 hours</p>
                    </div>
                    
                    <div>
                        <h3>📞 Phone Support</h3>
                        <p><strong>Toll-Free:</strong> 1-800-XPERTBID</p>
                        <p><strong>International:</strong> +1-555-123-4567</p>
                        <p><strong>Hours:</strong> Monday-Friday, 8 AM - 8 PM EST</p>
                        <p><strong>Weekend:</strong> Saturday-Sunday, 10 AM - 6 PM EST</p>
                    </div>
                    
                    <div>
                        <h3>💬 Live Chat</h3>
                        <p>Available 24/7 on our website</p>
                        <p>Click the chat icon in the bottom right corner</p>
                        <p>Average response time: 2 minutes</p>
                        <p>Multilingual support available</p>
                    </div>
                    
                    <div>
                        <h3>📬 Mail</h3>
                        <p><strong>XpertBid Customer Service</strong><br>
                        123 Marketplace Avenue<br>
                        Suite 500<br>
                        New York, NY 10001<br>
                        United States</p>
                    </div>
                </div>
                
                <h2>Business Hours</h2>
                <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                    <tr style="background-color: #f5f5f5;">
                        <th style="padding: 10px; border: 1px solid #ddd;">Day</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Hours (EST)</th>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd;">Monday - Friday</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">8:00 AM - 8:00 PM</td>
                    </tr>
                    <tr style="background-color: #f9f9f9;">
                        <td style="padding: 10px; border: 1px solid #ddd;">Saturday - Sunday</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">10:00 AM - 6:00 PM</td>
                    </tr>
                </table>
                
                <h2>Specialized Support</h2>
                <ul>
                    <li><strong>Seller Support:</strong> sellers@xpertbid.com</li>
                    <li><strong>Auction Support:</strong> auctions@xpertbid.com</li>
                    <li><strong>Real Estate:</strong> realestate@xpertbid.com</li>
                    <li><strong>Vehicle Sales:</strong> vehicles@xpertbid.com</li>
                    <li><strong>Press Inquiries:</strong> press@xpertbid.com</li>
                </ul>
                
                <h2>Frequently Asked Questions</h2>
                <p>Before contacting us, you might find the answer to your question in our <a href="/faq">FAQ section</a> or by browsing our <a href="/help">Help Center</a>.</p>
                
                <h2>Feedback</h2>
                <p>We value your feedback and suggestions. Please send them to feedback@xpertbid.com. Your input helps us improve our services and platform.</p>
                
                <h2>Emergency Support</h2>
                <p>For urgent security issues or account problems, please call our emergency hotline at 1-800-URGENT-BID (24/7 availability).</p>',
                'meta_title' => 'Contact Us - XpertBid Support',
                'meta_description' => 'Get in touch with XpertBid customer support. Multiple contact methods available including phone, email, and live chat.',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'FAQ',
                'slug' => 'faq',
                'content' => '<h1>Frequently Asked Questions</h1>
                
                <h2>General Questions</h2>
                
                <h3>What is XpertBid?</h3>
                <p>XpertBid is a comprehensive online marketplace that combines e-commerce, auctions, real estate listings, and vehicle sales in one platform. We connect buyers and sellers from around the world.</p>
                
                <h3>How do I create an account?</h3>
                <p>Click the "Sign Up" button on our homepage and fill out the registration form with your email address, name, and password. You\'ll receive a confirmation email to verify your account.</p>
                
                <h3>Is it free to use XpertBid?</h3>
                <p>Creating an account and browsing listings is completely free. We only charge fees when you successfully buy or sell items on our platform.</p>
                
                <h2>Buying Questions</h2>
                
                <h3>How do I buy an item?</h3>
                <p>Browse our listings, add items to your cart, and proceed to checkout. For auction items, place your bid and wait for the auction to end. For regular items, complete the purchase immediately.</p>
                
                <h3>What payment methods do you accept?</h3>
                <p>We accept major credit cards (Visa, MasterCard, American Express), PayPal, and bank transfers. Some sellers may accept additional payment methods.</p>
                
                <h3>How long does shipping take?</h3>
                <p>Shipping times vary by item and location. Standard shipping typically takes 5-7 business days, while express shipping takes 2-3 business days. International shipping times vary by destination.</p>
                
                <h2>Selling Questions</h2>
                
                <h3>How do I become a seller?</h3>
                <p>Apply to become a seller through your account dashboard. We\'ll review your application and may require additional verification depending on what you plan to sell.</p>
                
                <h3>What fees do sellers pay?</h3>
                <p>Our fee structure varies by category and listing type. Generally, we charge a small percentage of the final sale price. Detailed fee information is available in your seller dashboard.</p>
                
                <h3>How do I list an item for sale?</h3>
                <p>Go to your seller dashboard and click "List New Item." Fill out the product information, upload photos, set your price, and publish your listing.</p>
                
                <h2>Auction Questions</h2>
                
                <h3>How do online auctions work?</h3>
                <p>Browse auction listings, place bids on items you\'re interested in, and monitor the bidding. The highest bidder when the auction ends wins the item.</p>
                
                <h3>Can I cancel a bid?</h3>
                <p>Bids are generally binding, but you may be able to cancel a bid under certain circumstances. Contact our support team immediately if you need to cancel a bid.</p>
                
                <h3>What happens if I win an auction?</h3>
                <p>You\'ll receive an email notification with payment instructions. Complete payment within the specified timeframe to secure your winning bid.</p>
                
                <h2>Account & Security</h2>
                
                <h3>How do I change my password?</h3>
                <p>Go to your account settings and click "Change Password." Enter your current password and your new password twice to confirm.</p>
                
                <h3>What should I do if I forget my password?</h3>
                <p>Click "Forgot Password" on the login page and enter your email address. We\'ll send you a link to reset your password.</p>
                
                <h3>How do I delete my account?</h3>
                <p>Contact our customer service team to request account deletion. We\'ll process your request after verifying your identity and ensuring all transactions are complete.</p>
                
                <h2>Technical Issues</h2>
                
                <h3>The website isn\'t loading properly. What should I do?</h3>
                <p>Try refreshing the page, clearing your browser cache, or using a different browser. If the problem persists, contact our technical support team.</p>
                
                <h3>I can\'t upload photos. What\'s wrong?</h3>
                <p>Check that your photos are in a supported format (JPG, PNG, GIF) and under the file size limit. If you continue having issues, contact our support team.</p>
                
                <h3>My payment was charged but the order didn\'t go through. What should I do?</h3>
                <p>Contact our billing support team immediately with your order number and payment details. We\'ll investigate and resolve the issue promptly.</p>
                
                <h2>Still Need Help?</h2>
                <p>If you can\'t find the answer to your question here, please <a href="/contact-us">contact our customer support team</a>. We\'re here to help!</p>',
                'meta_title' => 'FAQ - Frequently Asked Questions - XpertBid',
                'meta_description' => 'Find answers to common questions about using XpertBid marketplace, auctions, and services.',
                'status' => 'published',
                'is_featured' => false,
            ],
        ];

        foreach ($pages as $pageData) {
            DB::table('pages')->insert([
                'tenant_id' => $tenant->id,
                'title' => $pageData['title'],
                'slug' => $pageData['slug'],
                'content' => $pageData['content'],
                'excerpt' => substr(strip_tags($pageData['content']), 0, 200) . '...',
                'page_type' => 'static',
                'meta_data' => json_encode([
                    'meta_title' => $pageData['meta_title'],
                    'meta_description' => $pageData['meta_description']
                ]),
                'status' => $pageData['status'],
                'is_featured' => $pageData['is_featured'],
                'author_id' => 1, // Admin user
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info("Created page: {$pageData['title']}");
        }

        $this->command->info('Page seeding completed!');
    }
}

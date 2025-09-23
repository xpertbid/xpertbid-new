<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating blog categories and posts...');

        // Get existing data
        $tenant = DB::table('tenants')->first();
        $users = DB::table('users')->get();

        if (!$tenant || $users->isEmpty()) {
            $this->command->error('Required data not found. Please run other seeders first.');
            return;
        }

        // Create blog categories
        $blogCategories = [
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'Latest technology news, reviews, and insights',
                'is_active' => true,
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Fashion trends, style guides, and lifestyle tips',
                'is_active' => true,
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Home improvement, gardening, and interior design',
                'is_active' => true,
            ],
            [
                'name' => 'Sports & Fitness',
                'slug' => 'sports-fitness',
                'description' => 'Sports news, fitness tips, and workout guides',
                'is_active' => true,
            ],
            [
                'name' => 'Lifestyle',
                'slug' => 'lifestyle',
                'description' => 'General lifestyle content, tips, and inspiration',
                'is_active' => true,
            ],
            [
                'name' => 'Reviews',
                'slug' => 'reviews',
                'description' => 'Product reviews and buying guides',
                'is_active' => true,
            ],
        ];

        $categoryIds = [];
        foreach ($blogCategories as $categoryData) {
            $existing = DB::table('blog_categories')->where('slug', $categoryData['slug'])->first();
            if (!$existing) {
                $categoryId = DB::table('blog_categories')->insertGetId([
                    'tenant_id' => $tenant->id,
                    'name' => $categoryData['name'],
                    'slug' => $categoryData['slug'],
                    'description' => $categoryData['description'],
                    'color' => '#007bff',
                'icon' => 'fas fa-tag',
                    'is_active' => $categoryData['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $categoryIds[] = $categoryId;
                $this->command->info("Created blog category: {$categoryData['name']}");
            } else {
                $categoryIds[] = $existing->id;
                $this->command->info("Blog category {$categoryData['name']} already exists, skipping...");
            }
        }

        // Create blog posts
        $blogPosts = [
            // Technology posts
            [
                'title' => 'The Future of Smartphones: What to Expect in 2024',
                'slug' => 'future-smartphones-2024',
                'excerpt' => 'Explore the latest innovations in smartphone technology and what consumers can expect from next-generation devices.',
                'content' => 'The smartphone industry continues to evolve at a rapid pace, with manufacturers pushing the boundaries of what\'s possible. In 2024, we can expect to see significant improvements in several key areas.

**Camera Technology**
Modern smartphones are becoming increasingly sophisticated photography tools. The latest models feature multiple camera sensors, advanced image processing algorithms, and AI-powered features that rival dedicated cameras.

**Performance and Battery Life**
With the introduction of more efficient processors and larger battery capacities, smartphones are delivering better performance while maintaining longer battery life. This is particularly important for power users who rely on their devices throughout the day.

**Display Technology**
OLED and AMOLED displays continue to improve, offering better color accuracy, higher refresh rates, and more energy-efficient operation. Some manufacturers are even experimenting with foldable displays and other innovative form factors.

**5G Connectivity**
As 5G networks become more widespread, smartphones are being optimized to take advantage of faster data speeds and lower latency. This opens up new possibilities for mobile applications and services.

**Artificial Intelligence**
AI is being integrated into smartphones in increasingly sophisticated ways, from improved voice assistants to real-time language translation and advanced image recognition capabilities.

The future of smartphones looks bright, with continued innovation driving the industry forward.',
                'featured_image' => '/images/blog/posts/smartphones-2024.jpg',
                'status' => 'published',
                'is_featured' => true,
                'author_id' => $users->first()->id,
                'category_id' => $categoryIds[0], // Technology
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Gaming Consoles: PlayStation 5 vs Xbox Series X Comparison',
                'slug' => 'playstation-5-vs-xbox-series-x-comparison',
                'excerpt' => 'A detailed comparison of the latest gaming consoles to help you choose the right one for your gaming needs.',
                'content' => 'The gaming console wars have reached new heights with the release of the PlayStation 5 and Xbox Series X. Both consoles offer impressive performance and exclusive titles, making the choice between them challenging for gamers.

**Performance Specifications**
Both consoles feature custom AMD processors and graphics cards, but they differ in their specific configurations. The PlayStation 5 emphasizes high-speed SSD storage and 3D audio capabilities, while the Xbox Series X focuses on raw computational power and backward compatibility.

**Exclusive Games**
One of the most significant factors in choosing a console is the exclusive game library. PlayStation 5 offers titles like Spider-Man: Miles Morales and Demon\'s Souls, while Xbox Series X features Halo Infinite and Forza Horizon 5.

**Controller Design**
The PlayStation 5\'s DualSense controller introduces haptic feedback and adaptive triggers, providing more immersive gameplay experiences. The Xbox Series X controller maintains the familiar design while adding improved ergonomics and reduced latency.

**Storage and Expandability**
Both consoles offer expandable storage options, but the PlayStation 5\'s proprietary SSD expansion is more expensive than the Xbox Series X\'s standard NVMe expansion slot.

**Price and Availability**
Pricing is similar between both consoles, but availability has been a significant challenge due to supply chain issues and high demand.

Ultimately, the choice between PlayStation 5 and Xbox Series X depends on your gaming preferences, exclusive title preferences, and ecosystem considerations.',
                'featured_image' => '/images/blog/posts/gaming-consoles-comparison.jpg',
                'status' => 'published',
                'is_featured' => false,
                'author_id' => $users->first()->id,
                'category_id' => $categoryIds[0], // Technology
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Sustainable Technology: Green Gadgets for Eco-Conscious Consumers',
                'slug' => 'sustainable-technology-green-gadgets',
                'excerpt' => 'Discover environmentally friendly technology products that help reduce your carbon footprint without compromising on performance.',
                'content' => 'As environmental awareness grows, technology companies are increasingly focusing on sustainability. Here are some green gadgets and practices that can help reduce your environmental impact.

**Solar-Powered Devices**
Solar-powered chargers, speakers, and even smartphones are becoming more viable options for consumers who want to reduce their reliance on traditional power sources.

**Energy-Efficient Appliances**
Smart home devices with energy monitoring capabilities help users track and reduce their power consumption. LED lighting systems and smart thermostats can significantly lower energy bills.

**Recycled Materials**
Many manufacturers are incorporating recycled materials into their products, from aluminum casings to plastic components made from ocean waste.

**Repairability and Longevity**
Companies like Fairphone are designing smartphones with modular components that are easy to repair and upgrade, extending the device\'s lifespan.

**E-Waste Reduction**
Proper recycling programs and trade-in initiatives help reduce electronic waste. Many manufacturers now offer take-back programs for old devices.

**Cloud Computing Efficiency**
Cloud-based services can reduce the need for powerful local hardware, potentially lowering overall energy consumption.

**Renewable Energy Data Centers**
Tech companies are increasingly powering their data centers with renewable energy sources, reducing the environmental impact of cloud services.

By choosing sustainable technology products and adopting eco-friendly practices, consumers can enjoy the benefits of modern technology while minimizing their environmental footprint.',
                'featured_image' => '/images/blog/posts/sustainable-technology.jpg',
                'status' => 'published',
                'is_featured' => true,
                'author_id' => $users->first()->id,
                'category_id' => $categoryIds[0], // Technology
                'published_at' => now()->subDays(8),
            ],

            // Fashion posts
            [
                'title' => 'Spring 2024 Fashion Trends: What\'s Hot This Season',
                'slug' => 'spring-2024-fashion-trends',
                'excerpt' => 'Discover the hottest fashion trends for spring 2024, from bold colors to sustainable fashion choices.',
                'content' => 'Spring 2024 brings a refreshing wave of fashion trends that blend sustainability with bold style statements. Here\'s what\'s trending this season.

**Bold Color Palettes**
Vibrant colors are making a strong comeback, with electric blues, neon greens, and sunset oranges dominating runways and street style.

**Sustainable Fashion**
Eco-conscious consumers are driving demand for sustainable fashion, including clothing made from organic cotton, recycled polyester, and innovative materials like mushroom leather.

**Oversized Silhouettes**
Comfort continues to influence fashion, with oversized blazers, wide-leg pants, and roomy sweaters becoming wardrobe staples.

**Vintage Revival**
Nostalgic fashion from the 90s and early 2000s is experiencing a revival, with platform shoes, cargo pants, and chunky accessories making a comeback.

**Minimalist Accessories**
Clean, simple accessories with geometric shapes and neutral colors complement the bold clothing trends.

**Athleisure Evolution**
The athleisure trend continues to evolve, with fashion-forward activewear that seamlessly transitions from gym to street.

**Gender-Fluid Fashion**
Unisex designs and gender-neutral clothing options are becoming more mainstream, reflecting changing societal attitudes.

**Tech-Enhanced Clothing**
Smart textiles and wearable technology are integrating into everyday fashion, from temperature-regulating fabrics to LED-embedded garments.

These trends reflect a fashion industry that\'s becoming more inclusive, sustainable, and innovative while maintaining its focus on individual expression and style.',
                'featured_image' => '/images/blog/posts/spring-fashion-2024.jpg',
                'status' => 'published',
                'is_featured' => true,
                'author_id' => $users->count() > 1 ? $users[1]->id : $users->first()->id,
                'category_id' => $categoryIds[1], // Fashion
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Sneaker Culture: The Evolution of Athletic Footwear',
                'slug' => 'sneaker-culture-evolution-athletic-footwear',
                'excerpt' => 'Explore the fascinating history and cultural impact of sneakers, from sports performance to fashion statements.',
                'content' => 'Sneakers have evolved from simple athletic footwear to cultural icons that represent lifestyle, status, and personal expression.

**Historical Origins**
The sneaker\'s journey began in the late 19th century with rubber-soled shoes designed for sports. Companies like Converse and Keds pioneered early athletic footwear.

**Basketball Revolution**
The introduction of basketball-specific sneakers by companies like Nike and Adidas transformed the industry, creating performance-focused designs that also appealed to casual wearers.

**Hip-Hop Influence**
Hip-hop culture in the 1980s and 1990s elevated sneakers to fashion statements, with artists and fans alike using footwear to express identity and style.

**Collaboration Culture**
Sneaker collaborations between brands and celebrities, athletes, and artists have created limited-edition releases that drive collector culture and resale markets.

**Technology Integration**
Modern sneakers incorporate advanced materials, cushioning systems, and even smart technology to enhance performance and comfort.

**Sustainability Movement**
The sneaker industry is embracing sustainable practices, with brands using recycled materials and eco-friendly production methods.

**Global Impact**
Sneaker culture has become a global phenomenon, with dedicated communities, events, and markets worldwide.

**Future Trends**
The future of sneakers includes 3D printing, personalized fit technology, and continued focus on sustainability and performance.

Sneakers represent more than just footwear—they\'re a reflection of culture, innovation, and personal style that continues to evolve with each generation.',
                'featured_image' => '/images/blog/posts/sneaker-culture.jpg',
                'status' => 'published',
                'is_featured' => false,
                'author_id' => $users->count() > 1 ? $users[1]->id : $users->first()->id,
                'category_id' => $categoryIds[1], // Fashion
                'published_at' => now()->subDays(6),
            ],

            // Home & Garden posts
            [
                'title' => 'Smart Home Automation: Making Your Home Smarter',
                'slug' => 'smart-home-automation-guide',
                'excerpt' => 'Learn how to transform your home with smart automation technology for convenience, security, and energy efficiency.',
                'content' => 'Smart home automation is revolutionizing how we live, offering unprecedented convenience, security, and energy efficiency.

**Getting Started**
Begin with basic smart devices like smart bulbs, plugs, and thermostats. These provide immediate benefits while serving as a foundation for more complex automation.

**Voice Control Integration**
Voice assistants like Amazon Alexa and Google Assistant make controlling your smart home intuitive and hands-free.

**Security Systems**
Smart security systems offer remote monitoring, motion detection, and instant alerts, providing peace of mind whether you\'re home or away.

**Energy Management**
Smart thermostats and energy monitoring devices help reduce utility bills by optimizing heating, cooling, and electricity usage.

**Lighting Control**
Automated lighting systems can adjust based on time of day, occupancy, and natural light levels, creating the perfect ambiance while saving energy.

**Climate Control**
Smart HVAC systems maintain optimal temperature and air quality while learning your preferences and schedule.

**Entertainment Integration**
Smart entertainment systems can sync music, video, and lighting to create immersive experiences throughout your home.

**Privacy and Security**
As homes become smarter, it\'s important to prioritize cybersecurity and data privacy when choosing devices and services.

**Future Possibilities**
Emerging technologies like AI and machine learning promise even more sophisticated automation capabilities.

Smart home automation isn\'t just about convenience—it\'s about creating a living space that adapts to your lifestyle and enhances your daily experience.',
                'featured_image' => '/images/blog/posts/smart-home-automation.jpg',
                'status' => 'published',
                'is_featured' => true,
                'author_id' => $users->count() > 2 ? $users[2]->id : $users->first()->id,
                'category_id' => $categoryIds[2], // Home & Garden
                'published_at' => now()->subDays(4),
            ],
            [
                'title' => 'Indoor Gardening: Growing Fresh Herbs and Vegetables Year-Round',
                'slug' => 'indoor-gardening-herbs-vegetables',
                'excerpt' => 'Discover how to create a thriving indoor garden with herbs and vegetables, perfect for any space and climate.',
                'content' => 'Indoor gardening allows you to grow fresh herbs and vegetables year-round, regardless of outdoor weather conditions.

**Choosing the Right Plants**
Start with herbs like basil, mint, and parsley, which are relatively easy to grow indoors. Leafy greens like lettuce and spinach also work well in indoor environments.

**Lighting Requirements**
Most edible plants need 6-8 hours of bright light daily. LED grow lights provide the full spectrum of light needed for healthy plant growth.

**Container Selection**
Choose containers with good drainage and appropriate size for your plants. Self-watering containers can help maintain consistent moisture levels.

**Soil and Fertilizer**
Use high-quality potting soil specifically designed for container gardening. Regular fertilization ensures your plants get the nutrients they need.

**Watering Techniques**
Overwatering is a common mistake in indoor gardening. Check soil moisture before watering and ensure proper drainage.

**Temperature and Humidity**
Most herbs and vegetables prefer temperatures between 65-75°F and moderate humidity levels.

**Pest Management**
Indoor plants can still attract pests. Regular inspection and natural pest control methods help keep your garden healthy.

**Harvesting Tips**
Harvest herbs regularly to encourage new growth. For vegetables, pick when they reach optimal size and ripeness.

**Space Optimization**
Vertical gardening and compact varieties maximize your growing space, making indoor gardening possible even in small apartments.

Indoor gardening is a rewarding hobby that provides fresh, organic produce while adding natural beauty to your living space.',
                'featured_image' => '/images/blog/posts/indoor-gardening.jpg',
                'status' => 'published',
                'is_featured' => false,
                'author_id' => $users->count() > 2 ? $users[2]->id : $users->first()->id,
                'category_id' => $categoryIds[2], // Home & Garden
                'published_at' => now()->subDays(7),
            ],

            // Sports & Fitness posts
            [
                'title' => 'Home Workout Essentials: Building Your Perfect Home Gym',
                'slug' => 'home-workout-essentials-home-gym',
                'excerpt' => 'Create an effective home gym with essential equipment that fits your space and budget.',
                'content' => 'Building a home gym doesn\'t require a large space or massive budget. With the right equipment and planning, you can create an effective workout space at home.

**Essential Equipment**
Start with versatile equipment like resistance bands, dumbbells, and a yoga mat. These provide a foundation for countless exercises.

**Space Considerations**
Even a small corner can accommodate a home gym. Focus on equipment that can be easily stored or folded when not in use.

**Cardio Options**
Consider space-efficient cardio equipment like jump ropes, kettlebells, or compact treadmills and bikes.

**Strength Training**
Adjustable dumbbells and resistance bands offer multiple weight options without taking up excessive space.

**Flexibility and Recovery**
Include foam rollers, yoga blocks, and stretching straps for recovery and flexibility work.

**Technology Integration**
Fitness apps and streaming services provide guided workouts and tracking capabilities for home gym users.

**Safety Considerations**
Ensure proper form and technique, especially when working out alone. Consider mirrors for form checking.

**Progression Planning**
Plan for equipment upgrades as your fitness level improves and your space allows.

**Motivation Strategies**
Create a dedicated workout space with good lighting, ventilation, and motivational elements.

**Budget-Friendly Options**
Many effective exercises require no equipment at all. Bodyweight exercises can provide excellent workouts.

A well-planned home gym provides convenience, privacy, and the flexibility to work out on your schedule while saving money on gym memberships.',
                'featured_image' => '/images/blog/posts/home-workout-essentials.jpg',
                'status' => 'published',
                'is_featured' => true,
                'author_id' => $users->count() > 1 ? $users[1]->id : $users->first()->id,
                'category_id' => $categoryIds[3], // Sports & Fitness
                'published_at' => now()->subDays(1),
            ],

            // Lifestyle posts
            [
                'title' => 'Digital Detox: Reconnecting with the Real World',
                'slug' => 'digital-detox-reconnecting-real-world',
                'excerpt' => 'Learn how to take a break from digital devices and reconnect with the world around you.',
                'content' => 'In our hyperconnected world, taking time to disconnect from digital devices can have profound benefits for mental health and well-being.

**Recognizing the Need**
Signs that you might benefit from a digital detox include constant checking of devices, anxiety when separated from technology, and difficulty focusing on tasks.

**Planning Your Detox**
Start with small breaks—perhaps an hour without devices—and gradually increase the duration. Set specific times and goals for your digital detox.

**Creating Boundaries**
Establish device-free zones in your home, such as the bedroom or dining room, to create natural breaks from technology.

**Alternative Activities**
Use your newfound time for activities like reading, exercise, hobbies, or spending quality time with family and friends.

**Mindfulness Practices**
Digital detoxes provide an opportunity to practice mindfulness, meditation, or other stress-reduction techniques.

**Sleep Improvement**
Removing devices from the bedroom can significantly improve sleep quality and duration.

**Social Connection**
Digital detoxes can enhance face-to-face relationships and help you develop deeper connections with others.

**Productivity Benefits**
Reducing digital distractions can improve focus and productivity in both personal and professional tasks.

**Long-term Habits**
Use your digital detox experience to establish healthier long-term relationships with technology.

**Gradual Integration**
After your detox, gradually reintroduce technology with more mindful usage patterns.

A digital detox isn\'t about rejecting technology entirely—it\'s about creating a healthier, more balanced relationship with the digital world.',
                'featured_image' => '/images/blog/posts/digital-detox.jpg',
                'status' => 'published',
                'is_featured' => false,
                'author_id' => $users->first()->id,
                'category_id' => $categoryIds[4], // Lifestyle
                'published_at' => now()->subDays(9),
            ],

            // Reviews posts
            [
                'title' => 'Best Smartphones of 2024: Comprehensive Review',
                'slug' => 'best-smartphones-2024-review',
                'excerpt' => 'Our detailed review of the top smartphones available in 2024, covering performance, camera quality, and value.',
                'content' => '2024 has brought impressive smartphones from major manufacturers. Here\'s our comprehensive review of the best options available.

**iPhone 15 Pro Max**
Apple\'s flagship offers exceptional camera performance, powerful A17 Pro chip, and premium build quality. The titanium construction and improved battery life make it a top choice for iOS users.

**Samsung Galaxy S24 Ultra**
Samsung\'s flagship features an impressive 200MP camera, S Pen functionality, and powerful performance. The AI-powered features and display quality are standout characteristics.

**Google Pixel 8 Pro**
Google\'s latest offers outstanding computational photography, clean Android experience, and innovative AI features. The camera performance and software optimization are excellent.

**OnePlus 12**
OnePlus continues to offer flagship performance at competitive prices. The fast charging, smooth performance, and clean software make it a great value proposition.

**Xiaomi 14 Ultra**
Xiaomi\'s flagship delivers impressive camera capabilities and performance. The Leica partnership brings professional-grade photography features to mobile devices.

**Camera Performance**
All flagship smartphones now offer exceptional camera capabilities, with computational photography and AI enhancement becoming standard features.

**Performance and Battery**
Modern smartphones deliver all-day battery life and smooth performance for demanding applications and games.

**Software and Updates**
Consider the manufacturer\'s track record for software updates and support when choosing your next smartphone.

**Value and Pricing**
Flagship smartphones command premium prices, but mid-range options offer excellent value for most users\' needs.

**Final Recommendations**
Choose based on your ecosystem preferences, camera needs, and budget. All reviewed devices offer excellent performance and features.',
                'featured_image' => '/images/blog/posts/smartphone-review-2024.jpg',
                'status' => 'published',
                'is_featured' => true,
                'author_id' => $users->first()->id,
                'category_id' => $categoryIds[5], // Reviews
                'published_at' => now()->subDays(10),
            ],
        ];

        foreach ($blogPosts as $postData) {
            DB::table('blog_posts')->insert([
                'tenant_id' => $tenant->id,
                'category_id' => $postData['category_id'],
                'author_id' => $postData['author_id'],
                'title' => $postData['title'],
                'slug' => $postData['slug'],
                'excerpt' => $postData['excerpt'],
                'content' => $postData['content'],
                'featured_image' => $postData['featured_image'],
                'status' => $postData['status'],
                'is_featured' => $postData['is_featured'],
                'meta_title' => $postData['title'],
                'meta_description' => $postData['excerpt'],
                'published_at' => $postData['published_at'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info("Created blog post: {$postData['title']}");
        }

        $this->command->info('Blog seeding completed!');
    }
}

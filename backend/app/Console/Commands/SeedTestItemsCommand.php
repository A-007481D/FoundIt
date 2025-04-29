<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use App\Models\ItemImageFeature;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SeedTestItemsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:test-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with test items for the item detective feature';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding test items for item detective...');

        $systemUserId = $this->getSystemUserForTestItems();
        $this->info("Using system user ID: {$systemUserId} for test items");

        $testItems = [
            [
                'name' => 'Blue Backpack',
                'description' => 'A blue backpack found near the library',
                'status' => 'found',
                'location' => 'Library',
                'color' => 'Blue',
                'category' => 'Bag',
                'image_path' => 'items/blue-backpack.jpg'
            ],
            [
                'name' => 'Black Laptop',
                'description' => 'A black laptop found in the computer lab',
                'status' => 'found',
                'location' => 'Computer Lab',
                'color' => 'Black',
                'category' => 'Electronics',
                'image_path' => 'items/black-laptop.jpg'
            ],
            [
                'name' => 'Red Wallet',
                'description' => 'A red wallet found in the cafeteria',
                'status' => 'found',
                'location' => 'Cafeteria',
                'color' => 'Red',
                'category' => 'Accessory',
                'image_path' => 'items/red-wallet.jpg'
            ],
            [
                'name' => 'Green Jacket',
                'description' => 'A green jacket found in the gym',
                'status' => 'found',
                'location' => 'Gym',
                'color' => 'Green',
                'category' => 'Clothing',
                'image_path' => 'items/green-jacket.jpg'
            ],
            [
                'name' => 'White Headphones',
                'description' => 'White wireless headphones found in the music room',
                'status' => 'found',
                'location' => 'Music Room',
                'color' => 'White',
                'category' => 'Electronics',
                'image_path' => 'items/white-headphones.jpg'
            ],
            [
                'name' => 'Black Keys',
                'description' => 'A set of black keys found in the parking lot',
                'status' => 'found',
                'location' => 'Parking Lot',
                'color' => 'Black',
                'category' => 'Key',
                'image_path' => 'items/black-keys.jpg'
            ],
            [
                'name' => 'Purple Umbrella',
                'description' => 'A purple umbrella found at the bus stop',
                'status' => 'found',
                'location' => 'Bus Stop',
                'color' => 'Purple',
                'category' => 'Accessory',
                'image_path' => 'items/purple-umbrella.jpg'
            ],
            [
                'name' => 'Brown Notebook',
                'description' => 'A brown notebook found in the study hall',
                'status' => 'found',
                'location' => 'Study Hall',
                'color' => 'Brown',
                'category' => 'Accessory',
                'image_path' => 'items/brown-notebook.jpg'
            ]
        ];

        // Copy test images to the storage directory
        $this->copyPlaceholderImages();

        $count = 0;
        foreach ($testItems as $itemData) {
            // Create the item
            $item = new Item();
            $item->title = $itemData['name'];
            $item->description = $itemData['description'];
            $item->status = 'active';
            $item->location = $itemData['location'];
            $item->visible = true;
            $item->type = 'found';
            $item->user_id = $systemUserId;
            $item->category_id = 1;
            $item->found_date = now();
            $item->lost_date = now();
            $item->image = asset('storage/' . $itemData['image_path']);
            $item->save();

            // Create and associate image features
            $featureRecord = new ItemImageFeature();
            $featureRecord->item_id = $item->id;
            $featureRecord->color = $itemData['color'];
            $featureRecord->category = $itemData['category'];
            // Create a dummy feature vector (for testing)
            $featureRecord->feature_vector = $this->generateRandomFeatureVector();
            // Set the image path
            $featureRecord->image_path = $itemData['image_path'];
            $featureRecord->save();

            $count++;
        }

        $this->info("Created {$count} test items!");
        $this->info("You can now test the item detective with various images!");
    }

    /**
     * Generate a random feature vector for testing
     * 
     * @return array
     */
    private function generateRandomFeatureVector()
    {
        // Generate a random vector with 64 dimensions for testing
        $vector = [];
        for ($i = 0; $i < 64; $i++) {
            $vector[] = mt_rand(0, 100) / 100; // Random value between 0 and 1
        }
        return $vector;
    }

    /**
     * Copy placeholder images to the storage directory
     */
    private function copyPlaceholderImages()
    {
        $this->info('Copying placeholder images to storage directory...');

        // Create items directory if it doesn't exist
        if (!Storage::disk('public')->exists('items')) {
            Storage::disk('public')->makeDirectory('items');
        }

        // Create placeholder images with different colors
        $placeholders = [
            'blue-backpack.jpg',
            'black-laptop.jpg',
            'red-wallet.jpg',
            'green-jacket.jpg',
            'white-headphones.jpg',
            'black-keys.jpg',
            'purple-umbrella.jpg',
            'brown-notebook.jpg'
        ];

        foreach ($placeholders as $placeholder) {
            // Create or make sure the placeholder exists
            if (!Storage::disk('public')->exists('items/' . $placeholder)) {
                // Create a simple colored image with GD
                $color = explode('-', $placeholder)[0];
                $width = 300;
                $height = 300;
                
                $image = imagecreatetruecolor($width, $height);
                
                // Map color names to RGB values
                $colorMap = [
                    'blue' => [0, 0, 255],
                    'black' => [0, 0, 0],
                    'red' => [255, 0, 0],
                    'green' => [0, 255, 0],
                    'white' => [255, 255, 255],
                    'purple' => [128, 0, 128],
                    'brown' => [165, 42, 42],
                ];
                
                // Set the background color
                $rgb = $colorMap[strtolower($color)] ?? [100, 100, 100];
                $bgColor = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
                imagefill($image, 0, 0, $bgColor);
                
                // Add some text
                $textColor = imagecolorallocate($image, 255, 255, 255);
                // Use black text on light backgrounds
                if (in_array(strtolower($color), ['white', 'yellow'])) {
                    $textColor = imagecolorallocate($image, 0, 0, 0);
                }
                
                $item = str_replace('.jpg', '', explode('-', $placeholder)[1]);
                imagestring($image, 5, 100, $height/2, $item, $textColor);
                
                // Create a temporary file
                $tempFile = tempnam(sys_get_temp_dir(), 'img');
                imagejpeg($image, $tempFile, 90);
                
                // Save to storage
                $contents = file_get_contents($tempFile);
                Storage::disk('public')->put('items/' . $placeholder, $contents);
                
                // Clean up
                unlink($tempFile);
                imagedestroy($image);
                
                $this->line("Created placeholder image: {$placeholder}");
            }
        }
    }

    /**
     * Get or create a system user for test items
     *
     * @return int
     */
    private function getSystemUserForTestItems()
    {
        // Try to find existing system user
        $systemUser = \App\Models\User::where('email', 'system@foundit.test')->first();
        
        if ($systemUser) {
            return $systemUser->id;
        }
        
        // Create a new system user if it doesn't exist
        $systemUser = new \App\Models\User();
        $systemUser->firstname = 'System';
        $systemUser->lastname = 'Bot';
        $systemUser->email = 'system@foundit.test';
        $systemUser->password = bcrypt('SYSTEM_GENERATED_PASSWORD_' . Str::random(16));
        $systemUser->email_verified_at = now();
        $systemUser->role = 'user';
        $systemUser->status = 'active';
        $systemUser->save();
        
        $this->info('Created system user for test items');
        return $systemUser->id;
    }
} 